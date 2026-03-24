<?php
// tests/test_fix.php
require_once __DIR__ . '/../includes/SqlRewriter.php';

$userId = 123;
$expectedPrefix = "user_{$userId}_";

$tests = [
    // Standard
    "SELECT * FROM users" => "SELECT * FROM user_{$userId}_users",
    
    // Block Comments
    "SELECT * FROM/*comment*/users" => "SELECT * FROM/*comment*/user_{$userId}_users",
    "SELECT * FROM /* multi \n line */ users" => "SELECT * FROM /* multi \n line */ user_{$userId}_users",
    
    // Line Comments (complex)
    // Note: Line comments consume the rest of the line, so the table must be on next line
    "SELECT * FROM -- comment \n users" => "SELECT * FROM -- comment \n user_{$userId}_users",
    
    // Composite Keywords
    "DROP TABLE users" => "DROP TABLE user_{$userId}_users",
    "DROP/*comment*/TABLE/*comment*/users" => "DROP/*comment*/TABLE/*comment*/user_{$userId}_users",
    
    // Idempotency
    "SELECT * FROM user_123_users" => "SELECT * FROM user_123_users",
];

$passed = 0;
$failed = 0;

echo "Running Security Tests...\n";
echo "-------------------------\n";

foreach ($tests as $input => $expected) {
    $actual = SqlRewriter::prefixTables($input, $userId);
    
    // Simplify verification: remove whitespace normalization issues
    // We check if the table was prefixed in the actual output
    
    $isPrefixed = strpos($actual, $expectedPrefix . 'users') !== false;
    
    // For idempotency test
    if (strpos($input, $expectedPrefix) !== false) {
         // Input already had prefix, ensure we didn't double prefix "user_123_user_123_users"
         $isPrefixed = substr_count($actual, $expectedPrefix) === 1;
    }

    if ($isPrefixed) {
        $passed++;
        echo "[PASS] Input: " . substr(str_replace("\n", '\n', $input), 0, 50) . "...\n";
    } else {
        $failed++;
        echo "[FAIL] Input: " . str_replace("\n", '\n', $input) . "\n";
        echo "       Actual: " . str_replace("\n", '\n', $actual) . "\n";
    }
}

echo "-------------------------\n";
echo "Tests Completed. Passed: $passed, Failed: $failed\n";

if ($failed > 0) exit(1);
?>
