<?php
require_once __DIR__ . '/csrf.php';

function isLoggedIn() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isLoggedIn() && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: /login.php");
        exit();
    }
    // Check Force Logout & Track Activity
    if (isset($_SESSION['user_id'])) {
        require_once __DIR__ . '/db_control.php';
        $pdo = getControlDB();
        
        // Update Activity
        $pdo->prepare("UPDATE users SET last_activity = NOW() WHERE id = ?")->execute([$_SESSION['user_id']]);

        $stmt = $pdo->prepare("SELECT force_logout, status FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $u = $stmt->fetch();
        
        if ($u && ($u['force_logout'] == 1 || $u['status'] === 'banned')) {
            session_destroy();
            header("Location: /login.php?msg=session_expired");
            exit();
        }
    }
}

function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        die("Access Denied: Admins only.");
    }
}

function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

function getCurrentUserRole() {
    return $_SESSION['role'] ?? null;
}

function getCurrentUserDb() {
    return $_SESSION['db_name'] ?? null;
}
