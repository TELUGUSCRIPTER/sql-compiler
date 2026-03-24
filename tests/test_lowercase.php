<?php
// tests/test_lowercase.php
require_once __DIR__ . '/../includes/SqlRewriter.php';

$userId = 123;
$input = "drop table students";
$expected = "user_{$userId}_students";

$actual = SqlRewriter::prefixTables($input, $userId);

echo "Input:  [$input]\n";
echo "Actual: [$actual]\n";

if (strpos($actual, $expected) !== false) {
    echo "[PASS] Prefixed correctly.\n";
} else {
    echo "[FAIL] Not prefixed.\n";
}

// Also test mixed case
$input2 = "DrOp TaBlE students";
$actual2 = SqlRewriter::prefixTables($input2, $userId);
echo "Input:  [$input2]\n";
echo "Actual: [$actual2]\n";
if (strpos($actual2, $expected) !== false) {
    echo "[PASS] Prefixed correctly.\n";
} else {
    echo "[FAIL] Not prefixed.\n";
}
?>
