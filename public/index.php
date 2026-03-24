<?php
// public/index.php
require_once __DIR__ . '/../app/config/auth_middleware.php';

if (isLoggedIn()) {
    if (isAdmin()) {
        header("Location: /app/admin/dashboard.php");
    } else {
        header("Location: /app/user/dashboard.php");
    }
} else {
    header("Location: /app/auth/login.php");
}
exit();
