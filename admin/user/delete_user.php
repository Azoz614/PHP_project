<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../../function.php';

require_admin();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Ensure database connection exists
if (!isset($conn) || !$conn instanceof mysqli) {
    die("❌ Database connection not available.");
}

// Check if user exists and is not an admin
$check = $conn->query("SELECT role FROM users WHERE id = $id");
$row = $check ? $check->fetch_assoc() : null;

if (!$row) {
    header("Location: users.php");
    exit;
}

// prevent deleting admins
if ($row['role'] === 'admin') {
    header("Location: users.php");
    exit;
}

// Delete user
$conn->query("DELETE FROM users WHERE id = $id");

header("Location: users.php");
exit;
?>
