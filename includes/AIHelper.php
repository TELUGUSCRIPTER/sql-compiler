<?php
// includes/AIHelper.php

class AIHelper {
    private $pdo;
    private $apiKey;
    private $model = 'gemini-2.5-flash';

    public function __construct($pdo) {
        $this->pdo = $pdo;
        // Key provided by user (deduplicated)
        $this->apiKey = 'YOUR_GEMINI_API_KEY'; 
    }

    /**
     * Checks if the user has reached their daily limit.
     */
    public function checkLimit($userId) {
        $today = date('Y-m-d');
        $stmt = $this->pdo->prepare("SELECT usage_count FROM ai_usage WHERE user_id = ? AND usage_date = ?");
        $stmt->execute([$userId, $today]);
        $usage = $stmt->fetchColumn() ?: 0;
        return $usage < 5;
    }

    /**
     * Increments the user's daily usage count.
     */
    public function incrementUsage($userId) {
        $today = date('Y-m-d');
        $stmt = $this->pdo->prepare("INSERT INTO ai_usage (user_id, usage_date, usage_count) VALUES (?, ?, 1) ON DUPLICATE KEY UPDATE usage_count = usage_count + 1");
        $stmt->execute([$userId, $today]);
    }

    /**
     * Sends a request to Gemini to fix the SQL.
     */
    public function fixSql($sql, $error) {
        $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}";

        $prompt = "You are an expert SQL assistant. The user wrote a SQL query that might be incorrect or caused an error (e.g., wrong dialect like Postgres/Oracle). 
Please fix the query to be valid MariaDB/MySQL syntax.
Return ONLY the corrected SQL code. 
Do NOT use markdown code blocks. 
Do NOT add explanations.
Just the raw SQL.

Query:
$sql

Error (if any):
$error";

        $data = [
            "contents" => [
                [
                    "parts" => [
                        ["text" => $prompt]
                    ]
                ]
            ]
        ];

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if ($curlError = curl_error($ch)) {
            curl_close($ch);
            return ['error' => 'Connection Failed: ' . $curlError];
        }
        curl_close($ch);

        if ($httpCode !== 200) {
            return ['error' => 'AI Service Unavailable (' . $httpCode . ')', 'details' => $response];
        }

        $result = json_decode($response, true);
        $fixedSql = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';

        // Clean up markdown
        $fixedSql = preg_replace('/^```sql\s*|```$/', '', $fixedSql);
        $fixedSql = preg_replace('/^```\s*|```$/', '', $fixedSql);
        $fixedSql = trim($fixedSql);

        if (!$fixedSql) {
            return ['error' => 'AI could not generate a fix.'];
        }

        return ['sql' => $fixedSql];
    }
}
