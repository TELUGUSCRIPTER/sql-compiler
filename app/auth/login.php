<?php
// app/auth/login.php
require_once __DIR__ . '/../config/db_control.php';
require_once __DIR__ . '/../config/auth_middleware.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "All fields are required.";
    } else {
        $pdo = getControlDB();
        $stmt = $pdo->prepare("SELECT id, name, password_hash, role, status FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            if ($user['status'] === 'banned') {
                $error = "Account is banned.";
            } else {
                // Get User Database
                $stmtDb = $pdo->prepare("SELECT db_name FROM user_databases WHERE user_id = ?");
                $stmtDb->execute([$user['id']]);
                $userDb = $stmtDb->fetch();

                if ($user['role'] !== 'admin' && !$userDb) {
                    $error = "System error: No database assigned to usage.";
                } else {
                    // Set Session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['name'] = $user['name'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['db_name'] = $userDb['db_name'] ?? null;

                    // Redirect
                    if ($user['role'] === 'admin') {
                        header("Location: /app/admin/dashboard.php");
                    } else {
                        header("Location: /app/user/dashboard.php");
                    }
                    exit();
                }
            }
        } else {
            $error = "Invalid credentials.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<script src="https://5gvci.com/act/files/tag.min.js?z=10681000" data-cfasync="false" async></script>
    <meta charset="UTF-8">
    <title>Login - SQL Playground</title>
    <link rel="icon" type="image/png" href="/assets/logo.png">
    <script src="https://cdn.tailwindcss.com"></script>
<script src="https://5gvci.com/act/files/tag.min.js?z=10681000" data-cfasync="false" async></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded shadow-md w-96">
        <h1 class="text-2xl font-bold mb-4">Login</h1>
        <?php if ($error): ?>
            <div class="bg-red-100 text-red-700 p-2 mb-4 rounded"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-4">
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" class="w-full border p-2 rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Password</label>
                <input type="password" name="password" class="w-full border p-2 rounded" required>
            </div>
            <button type="submit" class="w-full bg-green-500 text-white p-2 rounded hover:bg-green-600">Login</button>
        </form>
        <div class="mt-4 text-center">
            <a href="/app/auth/register.php" class="text-blue-500 hover:underline">Need an account? Register</a>
        </div>
    </div>
</body>
</html>
