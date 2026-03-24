<?php
// test_register.php - Simple test to isolate the issue
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>Registration System Test</h1>";
echo "<pre>";

// Test 1: Database Connection
echo "\n=== Test 1: Database Connection ===\n";
try {
    require_once __DIR__ . '/config/db_control.php';
    $pdo = getControlDB();
    echo "✅ Database connected successfully\n";
    echo "Database: " . DB_CONTROL_NAME . "\n";
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    die();
}

// Test 2: Load Auth Middleware
echo "\n=== Test 2: Auth Middleware ===\n";
try {
    require_once __DIR__ . '/config/auth_middleware.php';
    echo "✅ Auth middleware loaded\n";
} catch (Exception $e) {
    echo "❌ Auth middleware failed: " . $e->getMessage() . "\n";
    echo "Error on line: " . $e->getLine() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    die();
}

// Test 3: Load Telegram Helper
echo "\n=== Test 3: Telegram Helper ===\n";
try {
    require_once __DIR__ . '/config/telegram_helper.php';
    echo "✅ Telegram helper loaded\n";
} catch (Exception $e) {
    echo "❌ Telegram helper failed: " . $e->getMessage() . "\n";
    echo "Error on line: " . $e->getLine() . "\n";
    die();
}

// Test 4: Load Security Helpers
echo "\n=== Test 4: Security Helpers ===\n";
try {
    require_once __DIR__ . '/config/security_helpers.php';
    echo "✅ Security helpers loaded\n";
    
    // Test individual functions
    echo "\nTesting functions:\n";
    
    $testIP = getUserIP();
    echo "- getUserIP(): $testIP ✓\n";
    
    $isDisposable = isDisposableEmail('test@tempmail.com');
    echo "- isDisposableEmail('test@tempmail.com'): " . ($isDisposable ? 'true' : 'false') . " ✓\n";
    
} catch (Exception $e) {
    echo "❌ Security helpers failed: " . $e->getMessage() . "\n";
    echo "Error in file: " . $e->getFile() . "\n";
    echo "Error on line: " . $e->getLine() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
    die();
}

// Test 5: Check reCAPTCHA constants
echo "\n=== Test 5: reCAPTCHA Configuration ===\n";
try {
    define('RECAPTCHA_SITE_KEY', '6LcD5mcsAAAAABIqim8rYOr0uzWIopMDKXp8eGAk');
    define('RECAPTCHA_SECRET_KEY', '6LcD5mcsAAAAALjFZ6mglau3B-In8GDDjrmlQQ5_');
    echo "✅ reCAPTCHA constants defined\n";
    echo "Site Key: " . RECAPTCHA_SITE_KEY . "\n";
} catch (Exception $e) {
    echo "❌ reCAPTCHA setup failed: " . $e->getMessage() . "\n";
}

// Test 6: Session
echo "\n=== Test 6: Session ===\n";
try {
    if (session_status() === PHP_SESSION_NONE) session_start();
    echo "✅ Session started\n";
    echo "Session ID: " . session_id() . "\n";
} catch (Exception $e) {
    echo "❌ Session failed: " . $e->getMessage() . "\n";
}

// Test 7: Check database columns
echo "\n=== Test 7: Database Schema ===\n";
try {
    $stmt = $pdo->query("SHOW COLUMNS FROM users");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Existing columns in 'users' table:\n";
    foreach ($columns as $col) {
        echo "  - $col\n";
    }
    
    $requiredCols = ['registration_ip', 'device_fingerprint', 'verify_token', 'email_verified_at', 'last_registration_attempt'];
    echo "\nRequired columns status:\n";
    foreach ($requiredCols as $req) {
        $exists = in_array($req, $columns);
        echo "  - $req: " . ($exists ? "✅ exists" : "❌ missing") . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Database schema check failed: " . $e->getMessage() . "\n";
}

// Test 8: Check registration_attempts table
echo "\n=== Test 8: Registration Attempts Table ===\n";
try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'registration_attempts'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Table exists\n";
    } else {
        echo "⚠️  Table does not exist (not critical)\n";
    }
} catch (Exception $e) {
    echo "⚠️  Table check failed: " . $e->getMessage() . "\n";
}

echo "\n=== All Tests Complete ===\n";
echo "✅ If you see this message, all core files loaded successfully!\n";
echo "\nNext step: Try visiting register.php\n";
echo "If register.php still shows 500 error, the issue is in the form logic.\n";

echo "</pre>";
?>

<p><a href="register.php">→ Go to Register Page</a></p>
<p><a href="check_db_schema.php">→ Check Database Schema</a></p>
