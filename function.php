<?php
require_once __DIR__ . '/db.php';

// Escape HTML safely
function esc($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user']);
}

// Get current user
function currentUser() {
    return $_SESSION['user'] ?? null;
}

// Check if user is admin
function isAdmin() {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
}

// Require admin role
function require_admin() {
    if (!isAdmin()) {
        header("Location: ../../login.php");
        exit;
    }
}

// Redirect helper
function redirect($url) {
    header("Location: $url");
    exit;
}
?>
