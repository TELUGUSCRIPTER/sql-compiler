<?php
// config/csrf.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Generates a CSRF token and stores it in the session if one doesn't exist.
 * Returns the token.
 */
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validates a request token against the session token.
 * @param string $token
 * @return bool
 */
function validateCsrfToken($token) {
    if (!isset($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * HTML helper to output the hidden input field.
 */
function csrfInput() {
    $token = generateCsrfToken();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
}
