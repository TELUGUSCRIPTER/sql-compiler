<?php
// includes/SqlRewriter.php

class SqlRewriter {
    
    public static function prefixTables($sql, $userId) {
        $prefix = "user_{$userId}_";
        
        // Robust Separator Regex: 
        // Matches Whitespace, OR Block Comments (non-greedy), OR Line Comments (--, #)
        // (?:\s|/\*.*?\*/|--[^\r\n]*|#[^\r\n]*)
        $sep = '(?:\s|/\*.*?\*/|--[^\r\n]*|#[^\r\n]*)';
        
        // 1. Keywords that precede a table name:
        // FROM, JOIN, UPDATE, INTO
        // And composite keywords: CREATE TABLE, DROP TABLE, ALTER TABLE, TRUNCATE TABLE, RENAME TABLE
        // Note: We use the $sep pattern inside the composite keywords too.
        
        // Updated to handle "IF NOT EXISTS" and "IF EXISTS" clauses (e.g. CREATE TABLE IF NOT EXISTS tbl)
        $composite = "(?:CREATE|DROP|ALTER|TRUNCATE|RENAME){$sep}+TABLE(?:{$sep}+IF(?:{$sep}+NOT)?{$sep}+EXISTS)?";
        $keywords = "(?:FROM|JOIN|UPDATE|INTO|TO|$composite)";
        
        // Full Pattern: 
        // \b(KEYWORDS) [separator]+ `? (TableName) `?
        // We use /is modifier: case-insensitive, dot matches newline (for block comments)
        // Using ~ delimiter to avoid conflict with / in comments
        $pattern = "~\b({$keywords}){$sep}+`?([a-zA-Z0-9_]+)`?~is";
        
        $rewritten = preg_replace_callback($pattern, function($matches) use ($prefix) {
            $keywordGroup = $matches[1]; // e.g. "FROM" or "DROP/*...*/TABLE"
            $table = $matches[2];        // e.g. "users"
            
            // Don't prefix if it already has the specific user prefix (idempotency)
            if (strpos($table, $prefix) === 0) {
                return $matches[0]; // Return distinct full match to preserve formatting/comments
            }
            
            // Reconstruct the string. 
            // We match the whole block including separators in $matches[0] but we only captured the keyword and table.
            // Problem: If we just return "$keywordGroup $prefix$table", we lose the specific comments/separators used between keyword and table.
            // But preserving them is hard securely. Safer to force a single space.
            // However, $keywordGroup might contain comments itself (e.g. "DROP/*...*/TABLE").
            // So we simply replace the Table Name part of the full match.
            
            // Robust Replacement:
            // Find the table name position at the end of the match and inject prefix.
            
            $offset = strrpos($matches[0], $table);
            if ($offset !== false) {
                return substr($matches[0], 0, $offset) . $prefix . $table;
            }
            
            // Fallback (shouldn't happen given regex structure)
            return "$keywordGroup `$prefix$table`";
            
        }, $sql);
        
        return $rewritten;
    }

    public static function validateIsolation($sql, $userId) {
        // If we see 'user_' but not OUR user prefix, block it. 
        // This prevents accessing "user_2_table" if I am user 1.
        if (stripos($sql, 'user_') !== false && stripos($sql, "user_{$userId}_") === false) {
            if (preg_match('/user_(\d+)_/', $sql, $m)) {
                if ((int)$m[1] !== (int)$userId) {
                     return false;
                }
            }
        }
        return true;
    }
}
