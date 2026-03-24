<?php
// app/auth/register.php
require_once __DIR__ . '/../config/db_control.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($name) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } else {
        $pdo = getControlDB();
        
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email already registered.";
        } else {
            // Begin Transaction
            $pdo->beginTransaction();
            try {
                // 1. Create User
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)");
                $stmt->execute([$name, $email, $hash]);
                $userId = $pdo->lastInsertId();

                // 2. Create Database
                $dbName = "sql_user_" . $userId;
                // Note: Parameters cannot be used for database names in PDO
                $pdo->exec("CREATE DATABASE `$dbName`");

                // 3. Record DB mapping
                $stmt = $pdo->prepare("INSERT INTO user_databases (user_id, db_name) VALUES (?, ?)");
                $stmt->execute([$userId, $dbName]);

                $pdo->commit();
                $success = "Registration successful! You can now login.";
                
                // Optional: Create basic tables in user DB?
                // For now, leave it empty as per requirements ("User-created tables")
                
            } catch (Exception $e) {
                $pdo->rollBack();
                $error = "Registration failed: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<script src="https://5gvci.com/act/files/tag.min.js?z=10681000" data-cfasync="false" async></script>
    <meta charset="UTF-8">
    <title>Register - SQL Playground</title>
    <link rel="icon" type="image/png" href="/assets/logo.png">
    <script src="https://cdn.tailwindcss.com"></script>
<script src="https://5gvci.com/act/files/tag.min.js?z=10681000" data-cfasync="false" async></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded shadow-md w-96">
        <h1 class="text-2xl font-bold mb-4">Register</h1>
        <?php if ($error): ?>
            <div class="bg-red-100 text-red-700 p-2 mb-4 rounded"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="bg-green-100 text-green-700 p-2 mb-4 rounded"><?= htmlspecialchars($success) ?></div>
            <a href="/app/auth/login.php" class="block text-center text-blue-500 hover:underline">Go to Login</a>
        <?php else: ?>
            <form method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700">Name</label>
                    <input type="text" name="name" class="w-full border p-2 rounded" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Email</label>
                    <input type="email" name="email" class="w-full border p-2 rounded" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Password</label>
                    <input type="password" name="password" class="w-full border p-2 rounded" required>
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">Register</button>
            </form>
            <div class="mt-4 text-center">
                <a href="/app/auth/login.php" class="text-blue-500 hover:underline">Already have an account? Login</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
