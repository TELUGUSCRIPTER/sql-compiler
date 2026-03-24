<?php
// tests/test_drop_allowed.php
require_once __DIR__ . '/../includes/SqlRewriter.php';

// Mock Ban Logic (Simulating run_sql.php)
$defaultBanned = ['DROP DATABASE', 'GRANT', 'REVOKE']; // DROP TABLE removed
$sql = "drop table my_table";
$userId = 123;

// 1. Check Ban Logic
foreach ($defaultBanned as $word) {
    if (stripos($sql, $word) !== false) {
        die("[FAIL] Banned word detected: $word\n");
    }
}
echo "[PASS] 'DROP TABLE' is not banned.\n";

// 2. Check Rewrite Logic
$rewritten = SqlRewriter::prefixTables($sql, $userId);
$expected = "drop table user_{$userId}_my_table";

if (stripos($rewritten, $expected) !== false) {
    echo "[PASS] Rewritten correctly: $rewritten\n";
} else {
    echo "[FAIL] Rewrite failed. Got: $rewritten\n";
}
?>
