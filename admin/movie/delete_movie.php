<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../function.php';

require_admin(); // restrict to admin

if (!isset($_GET['id'])) {
    die("❌ No movie ID provided.");
}

$id = (int)$_GET['id'];

// Fetch movie before delete (to delete poster)
$stmt = $conn->prepare("SELECT poster FROM movies WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("❌ Movie not found.");
}

$movie = $result->fetch_assoc();
$stmt->close();

// Delete poster file if exists
if (!empty($movie['poster']) && $movie['poster'] !== 'default.png') {
    $poster_path = __DIR__ . '/../../uploads/' . $movie['poster'];
    if (file_exists($poster_path)) {
        unlink($poster_path);
    }
}

// Delete from database
$stmt = $conn->prepare("DELETE FROM movies WHERE id=?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    // redirect to movie list
    header("Location: movies.php?deleted=1");
    exit;
} else {
    die("❌ Failed to delete movie.");
}

$stmt->close();
?>
