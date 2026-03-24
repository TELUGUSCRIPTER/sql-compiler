<?php
// tests/test_ban_logic.php

// Mock the DB setting
$bannedStr = "DROP, DELETE, TRUNCATE, DROP TABLE";

echo "Settings: [$bannedStr]\n";

$bannedWords = array_map('trim', explode(',', strtolower($bannedStr)));
$sql = "drop table students;";

echo "Input SQL: [$sql]\n";

foreach ($bannedWords as $word) {
    if (!$word) continue;
    
    // Exact logic from run_sql.php
    $regexWord = preg_quote($word, '/');
    $regexWord = str_replace(' ', '\s+', $regexWord);
    
    echo "Checking regex: /\\b$regexWord\\b/i\n";
    
    if (preg_match('/\b' . $regexWord . '\b/i', $sql)) {
         echo "[MATCH] Detected banned word: '$word'\n";
         exit(0);
    }
}

echo "[FAIL] Did not detect banned word.\n";
?>
