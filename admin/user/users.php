<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../function.php';

// Only admin can view this page
require_admin();

// Fetch users
$result = $conn->query("SELECT id, username, email, role, created_at FROM users ORDER BY id ASC");
if (!$result) {
    die("Database query failed: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Management — FLICK-FIX</title>
  <link href="../../css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #0b0b0b;
      color: #fff;
      font-family: Arial, sans-serif;
      padding: 30px;
    }
    .card {
      background: #121212;
      border: 1px solid #222;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: #1a1a1a;
      border-radius: 8px;
      overflow: hidden;
    }
    th, td {
      padding: 12px 10px;
      border-bottom: 1px solid #2a2a2a;
      text-align: left;
    }
    thead th {
      background: #007bff;
      color: #fff;
    }
    tr:hover td {
      background: #222;
    }
    .btn {
      padding: 6px 10px;
      border-radius: 4px;
      text-decoration: none;
      display: inline-block;
      font-size: 13px;
      margin-right: 6px;
    }
    .btn-primary { background: #007bff; color: #fff; }
    .btn-danger { background: #dc3545; color: #fff; }
    .muted { color: #999; font-size: 13px; }
  </style>
</head>
<body>

  <div class="card">
    <h3 class="mb-3">👤 User Management</h3>

    <p class="text-end">
      <a href="add_user.php" class="btn btn-primary">Add New User</a>
    </p>

    <div style="overflow-x:auto;">
      <table role="table" aria-label="Users table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Created</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($u = $result->fetch_assoc()): ?>
            <tr>
              <td><?= esc($u['id']) ?></td>
              <td><?= esc($u['username']) ?></td>
              <td><?= esc($u['email']) ?></td>
              <td><?= esc($u['role']) ?></td>
              <td><?= esc($u['created_at']) ?></td>
              <td>
                <a class="btn btn-primary" href="edit_user.php?id=<?= urlencode($u['id']) ?>">Edit</a>
                <?php if ($u['role'] !== 'admin'): ?>
                  <a class="btn btn-danger" href="delete_user.php?id=<?= urlencode($u['id']) ?>" onclick="return confirm('Delete this user?')">Delete</a>
                <?php else: ?>
                  <span class="muted">Admin</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

</body>
</html>
