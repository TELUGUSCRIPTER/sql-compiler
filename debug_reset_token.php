<?php
// debug_reset_token.php - Debug tool to check what's happening with reset tokens
require_once __DIR__ . '/config/db_control.php';

echo "<pre>";
echo "=== PASSWORD RESET DEBUG TOOL ===\n\n";

// Get email from URL parameter
$email = $_GET['email'] ?? '';

if (!$email) {
    echo "Usage: debug_reset_token.php?email=youremail@example.com\n";
    echo "\nThis will show you the reset token details for the email address.\n";
    exit;
}

try {
    $pdo = getControlDB();
    
    // Check if user exists
    $stmt = $pdo->prepare("SELECT id, email, reset_token_hash, reset_token_expires_at FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if (!$user) {
        echo "❌ User not found with email: $email\n";
        exit;
    }
    
    echo "✅ User found: {$user['email']}\n";
    echo "User ID: {$user['id']}\n\n";
    
    if ($user['reset_token_hash']) {
        echo "Token Hash in DB: {$user['reset_token_hash']}\n";
        echo "Token Expires: {$user['reset_token_expires_at']}\n";
        
        // Check if expired
        $now = new DateTime();
        $expires = new DateTime($user['reset_token_expires_at']);
        
        if ($now > $expires) {
            echo "\n❌ Token is EXPIRED\n";
            echo "Current time: " . $now->format('Y-m-d H:i:s') . "\n";
            echo "Expired at: " . $expires->format('Y-m-d H:i:s') . "\n";
        } else {
            echo "\n✅ Token is still VALID\n";
            echo "Current time: " . $now->format('Y-m-d H:i:s') . "\n";
            echo "Expires at: " . $expires->format('Y-m-d H:i:s') . "\n";
            $diff = $now->diff($expires);
            echo "Time remaining: {$diff->i} minutes\n";
        }
    } else {
        echo "❌ No reset token found for this user.\n";
        echo "User needs to request a password reset first.\n";
    }
    
    echo "\n--- HOW TO TEST ---\n";
    echo "1. Go to forgot_password.php and enter: $email\n";
    echo "2. Check your email for the reset link\n";
    echo "3. The link should look like: reset_password.php?token=XXXX&email=$email\n";
    echo "4. If it fails, run this script again to see the token details\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "</pre>";
