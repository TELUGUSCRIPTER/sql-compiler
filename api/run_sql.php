<?php
// api/run_sql.php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db_control.php';
require_once __DIR__ . '/../config/auth_middleware.php';

// DEBUGGING: Enable Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isLoggedIn()) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// RATE LIMITING (Sliding Window)
$limit = 60; // requests per minute
$window = 60; // seconds

if (!isset($_SESSION['api_rate_limit'])) {
    $_SESSION['api_rate_limit'] = [];
}

// Filter out old requests
$currentTime = time();
$_SESSION['api_rate_limit'] = array_filter($_SESSION['api_rate_limit'], function ($timestamp) use ($currentTime, $window) {
    return ($currentTime - $timestamp) < $window;
});

if (count($_SESSION['api_rate_limit']) >= $limit) {
    echo json_encode(['error' => 'Rate limit exceeded. Please wait a moment.']);
    exit;
}

// Log current request
$_SESSION['api_rate_limit'][] = $currentTime;

$userDbName = $_SESSION['db_name'];
$userId = $_SESSION['user_id'];
$rawInput = file_get_contents("php://input");
// DEBUG: Log input
error_log("Raw Input: " . substr($rawInput, 0, 100)); // Log first 100 chars

$input = json_decode($rawInput, true);
$sql = trim($input['sql'] ?? '');

if (empty($sql)) {
    // Debug info: Show what we received
    $jsonError = json_last_error_msg();
    $debugInfo = "Raw Input Length: " . strlen($rawInput) . ". JSON Error: $jsonError. Content: " . substr($rawInput, 0, 100);
    echo json_encode(['error' => 'Query is empty. ' . $debugInfo]);
    exit;
}

// Auto-convert PostgreSQL and Oracle syntax to MariaDB compatibility in CREATE TABLE
if (stripos($sql, 'CREATE TABLE') !== false) {
    // PostgreSQL BIGSERIAL → BIGINT UNSIGNED AUTO_INCREMENT
    $sql = preg_replace('/\bBIGSERIAL\b/i', 'BIGINT UNSIGNED AUTO_INCREMENT', $sql);

    // PostgreSQL SERIAL → INT UNSIGNED AUTO_INCREMENT
    $sql = preg_replace('/\bSERIAL\b/i', 'INT UNSIGNED AUTO_INCREMENT', $sql);

    // PostgreSQL SMALLSERIAL → SMALLINT UNSIGNED AUTO_INCREMENT
    $sql = preg_replace('/\bSMALLSERIAL\b/i', 'SMALLINT UNSIGNED AUTO_INCREMENT', $sql);

    // Oracle NUMBER → INT (or DECIMAL if precision specified)
    $sql = preg_replace('/\bNUMBER\b/i', 'INT', $sql);

    // PostgreSQL BYTEA → BLOB
    $sql = preg_replace('/\bBYTEA\b/i', 'BLOB', $sql);

    // PostgreSQL TEXT types
    $sql = preg_replace('/\bCHARACTER\s+VARYING\b/i', 'VARCHAR', $sql);

    // Fix TIMESTAMP with ON UPDATE - add NULL for better compatibility
    $sql = preg_replace(
        '/\bTIMESTAMP\s+DEFAULT\s+CURRENT_TIMESTAMP\s+ON\s+UPDATE\s+CURRENT_TIMESTAMP\b/i',
        'TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        $sql
    );

// Note: BOOLEAN is supported in MariaDB natively, no conversion needed
}

// FEATURE: Silently Convert TRUNCATE to DELETE FROM (Standard SQL Equivalent)
// This happens BEFORE the security check, so TRUNCATE will not trigger the ban.
// Robust Regex for Separators (Whitespace/Comments)
$sep = '(?:\s|/\*.*?\*/|--[^\r\n]*|#[^\r\n]*)';
if (stripos($sql, 'TRUNCATE') !== false) {
    // Match "TRUNCATE [sep] [TABLE] [sep] `table`"
    // $1 = Separator after TRUNCATE
    // $2 = "TABLE" (optional)
    // $3 = Table Name
    $pattern = "~^\s*TRUNCATE({$sep}+)(?:TABLE({$sep}+))?(`?[a-zA-Z0-9_]+`?)~is";
    $sql = preg_replace($pattern, 'DELETE FROM $3', $sql);
}

$pdoControl = getControlDB();

// 1. Check Security Policies (Banned Keywords)
// Uses Regex to catch bypasses like "DROP  DATABASE" (double spaces)
$stmt = $pdoControl->prepare("SELECT setting_value FROM system_settings WHERE setting_key = 'banned_keywords'");
$stmt->execute();
$bannedStr = $stmt->fetchColumn();

// Fail-Secure Defaults: Always ban these even if DB config is missing/empty
$defaultBanned = ['DROP DATABASE', 'GRANT', 'REVOKE', 'ALTER USER', 'FLUSH', 'TRUNCATE'];

$bannedWords = $defaultBanned;
if ($bannedStr) {
    $dbBanned = array_map('trim', explode(',', strtolower($bannedStr)));
    $bannedWords = array_unique(array_merge($bannedWords, $dbBanned));
}

foreach ($bannedWords as $word) {
    if (!$word)
        continue;

    // Escape special regex chars in the word (like .) but convert spaces to \s+
    $regexWord = preg_quote($word, '/');
    $regexWord = str_replace(' ', '\s+', $regexWord);

    // \b ensures word boundary, so "drop" matches "drop database" but not "raindrop"
    if (preg_match('/\b' . $regexWord . '\b/i', $sql)) {
        // Log violation
        $pdoControl->prepare("INSERT INTO query_logs (user_id, sql_text, status, error_message, execution_time_ms) VALUES (?, ?, 'error', 'Policy Violation: Banned keyword', 0)")->execute([$userId, $sql]);
        echo json_encode(['error' => "Security Policy Violation: Query contains banned keyword '$word'."]);
        exit;
    }
}

// RENAME TABLE is now supported via SqlRewriter prefixing
// The SqlRewriter will automatically prefix both source and destination table names

// Simulate CREATE DATABASE (No-op) - Handles comments/whitespace
// Match "CREATE DATABASE" even if preceded by comments (-- or /* */)
$normalizedSql = preg_replace('!/\*.*?\*/!s', '', $sql); // remove multiline comments
$normalizedSql = preg_replace('#^\s*--.*$#m', '', $normalizedSql); // remove single line comments
if (preg_match('/^\s*CREATE\s+DATABASE\b/i', $normalizedSql)) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Database created successfully.',
        'type' => 'affected',
        'count' => 0,
        'duration' => 0.001, // Mock duration
        'preview' => [],
        'table' => null
    ]);
    exit;
}

// 2. Connect to User DB (Actually Control DB in Single DB Mode)
try {
    $dsnUser = "mysql:host=" . DB_HOST . ";dbname=" . DB_CONTROL_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];
    $pdoUser = new PDO($dsnUser, DB_USER, DB_PASS, $options);

    // Strict Mode for Safety
    $pdoUser->exec("SET SESSION sql_mode = 'STRICT_ALL_TABLES,NO_ENGINE_SUBSTITUTION'");
}
catch (PDOException $e) {
    echo json_encode(['error' => "Database Connection Error: " . $e->getMessage()]);
    exit;
}

require_once __DIR__ . '/../includes/SqlRewriter.php';

// Rewrite the SQL
$originalSql = $sql;
$sql = SqlRewriter::prefixTables($sql, $userId);
if (empty($sql)) {
    echo json_encode(['error' => 'Internal Error: SQL Rewriter returned empty string.']);
    exit;
}

// Verify isolation (double check)
if (!SqlRewriter::validateIsolation($sql, $userId)) {
    echo json_encode(['error' => "Security Violation: Accessing other user's tables is forbidden."]);
    exit;
}

$startTime = microtime(true);
$status = 'success';
$errorMessage = null;
$result = [];

try {
    // 3. Execute Query
    $stmt = $pdoUser->prepare($sql);
    $stmt->execute();

    // 4. Handle Results (SELECT vs INSERT/UPDATE)
    if ($stmt->columnCount() > 0) {
        $data = $stmt->fetchAll();

        // Output Sanitization: Remove user_{id}_ from all values and keys
        // recursive function not needed strictly for fetchAll(ASSOC) but good for safety
        $sanitizedData = [];
        foreach ($data as $row) {
            $cleanRow = [];
            foreach ($row as $key => $val) {
                // Sanitize Value
                if (is_string($val)) {
                    $val = str_replace("user_{$userId}_", "", $val);
                }
                // Sanitize Key (optional, but good if columns are aliased weirdly)
                $cleanKey = str_replace("user_{$userId}_", "", $key);
                $cleanRow[$cleanKey] = $val;
            }
            $sanitizedData[] = $cleanRow;
        }

        $result = ['type' => 'result', 'data' => $sanitizedData];
    }
    else {
        $rowCount = $stmt->rowCount();

        // Auto-fetch logic for modifications
        $table = null;
        if (preg_match('/(INSERT INTO|UPDATE|DELETE FROM|CREATE TABLE|RENAME TABLE)\s+`?([a-zA-Z0-9_]+)`?/i', $sql, $matches)) {
            $table = $matches[2];
        }

        // For RENAME TABLE, extract the destination table name (after TO)
        if (stripos($sql, 'RENAME TABLE') !== false) {
            if (preg_match('/\bTO\s+`?([a-zA-Z0-9_]+)`?/i', $sql, $renameMatches)) {
                $table = $renameMatches[1]; // Use the new table name for preview
            }
        }

        $previewData = [];
        if ($table) {
            try {
                // Determine prefix again for the preview query
                $prefix = "user_{$userId}_";
                $prefixedTable = (strpos($table, $prefix) === 0) ? $table : $prefix . $table;

                $previewStmt = $pdoUser->query("SELECT * FROM `$prefixedTable` LIMIT 25");
                $previewData = $previewStmt->fetchAll();
            }
            catch (Exception $e) { /* Ignore if table doesn't exist yet or error */
            }
        }

        $result = [
            'type' => 'affected',
            'count' => $rowCount,
            'message' => "Query executed successfully. Rows affected: $rowCount",
            'preview' => $previewData,
            'table' => $table // Send back table name so UI can refresh specific table
        ];
    }

}
catch (PDOException $e) {
    // AUTO-FIX LOGIC REMOVED
    // The user requested to remove the automatic Gemini query generation on error.
    // We fall through to standard error reporting below.

    $status = 'error';
    // Clean up SQLSTATE for user
    $rawError = $e->getMessage();

    // 1. Remove SQLSTATE and codes (e.g. "SQLSTATE[42S02]: ...: 1051 ")
    $cleanError = preg_replace('/SQLSTATE\[\w+\]:.*?: \d+ /', '', $rawError);

    // Additional cleanup for just "SQLSTATE[...]: " if the above didn't catch everything
    $cleanError = preg_replace('/SQLSTATE\[\w+\]:\s*/', '', $cleanError);

    // 2. Remove Internal DB Name (e.g. "unmqwlgl_sql.")
    // converting DB_CONTROL_NAME to avoid hardcoding check
    if (defined('DB_CONTROL_NAME')) {
        $cleanError = str_replace(DB_CONTROL_NAME . '.', '', $cleanError);
    }

    // 3. Remove User Prefix (e.g. "user_123_")
    $cleanError = str_replace("user_{$userId}_", "", $cleanError);

    $errorMessage = trim($cleanError);

    // Fix for "NUMBER" type error common confusion
    if (stripos($errorMessage, 'near') !== false && stripos($sql, 'NUMBER') !== false) {
        $errorMessage .= " (Hint: 'NUMBER' is not a valid MariaDB type. Try 'INT', 'DECIMAL', or 'FLOAT'.)";
    }

    echo json_encode(['error' => $errorMessage]);
}

$duration = (microtime(true) - $startTime) * 1000;

// 5. Log Query
try {
    $logStmt = $pdoControl->prepare("INSERT INTO query_logs (user_id, sql_text, status, error_message, execution_time_ms) VALUES (?, ?, ?, ?, ?)");
    $logStmt->execute([$userId, $originalSql, $status, $errorMessage, $duration]);
}
catch (Exception $e) {
// Silent fail on logging
}

if ($status === 'success') {
    $result['duration'] = round($duration, 2);
    echo json_encode($result);
}
?>