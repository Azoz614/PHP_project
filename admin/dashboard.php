<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/function.php';

require_admin();

// Stats
$moviesCount = (int)($conn->query("SELECT COUNT(*) AS c FROM movies")->fetch_assoc()['c'] ?? 0);
$usersCount  = (int)($conn->query("SELECT COUNT(*) AS c FROM users")->fetch_assoc()['c'] ?? 0);
$adminsCount = (int)($conn->query("SELECT COUNT(*) AS c FROM users WHERE role='admin'")->fetch_assoc()['c'] ?? 0);

// Recent movies
$recent = [];
$res = $conn->query("SELECT id, title, year, release_date, director, poster FROM movies ORDER BY created_at DESC LIMIT 8");
while ($r = $res->fetch_assoc()) $recent[] = $r;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Admin Dashboard — FLICK-FIX</title>
  <link href="../css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    :root{
      --bg-1: #050507;
      --bg-2: #0d0d10;
      --card: rgba(255,255,255,0.03);
      --accent: #ff2d2d;
      --muted: rgba(255,255,255,0.55);
    }
    body{
      margin:0;
      min-height:100vh;
      font-family: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      background: linear-gradient(180deg,var(--bg-1),var(--bg-2));
      color:#fff;
      -webkit-font-smoothing:antialiased;
      padding:28px 34px;
    }

    /* Topbar with big logo and title */
    .topbar{
      display:flex;
      align-items:center;
      justify-content:space-between;
      padding:16px 18px;
      border-bottom: 1px solid rgba(255,255,255,0.03);
      margin-bottom:26px;
    }
    .logo-wrap{
      display:flex;
      align-items:center;
      gap:18px;
    }
    .logo-large{
      width:120px;
      height:auto;
      border-radius:10px;
      box-shadow:0 12px 40px rgba(255,45,45,0.1);
      transition:transform .25s ease;
    }
    .logo-large:hover{transform:scale(1.03);}
    .dash-title{
      font-size:26px;
      font-weight:700;
      letter-spacing:-0.5px;
    }

    .top-actions{
      display:flex;
      align-items:center;
      gap:10px;
    }
    .btn-ghost{
      background:transparent;
      border:1px solid rgba(255,255,255,0.06);
      color:#fff;
      padding:8px 12px;
      border-radius:8px;
      font-weight:600;
    }
    .btn-ghost:hover{transform:translateY(-3px);}
    .meta{font-size:13px;color:var(--muted);}

    /* Layout */
    .layout{
      display:grid;
      grid-template-columns:1fr 420px;
      gap:26px;
      align-items:start;
    }

    .left{display:flex;flex-direction:column;gap:22px;}
    .hero-row{display:flex;gap:18px;}
    .hero-card{
      flex:1;
      border-radius:16px;
      padding:28px;
      background:rgba(255,255,255,0.02);
      border:1px solid rgba(255,255,255,0.04);
      display:flex;
      flex-direction:column;
      justify-content:space-between;
      gap:12px;
      box-shadow:0 8px 30px rgba(0,0,0,0.6);
      transition:transform .18s ease, box-shadow .18s ease;
    }
    .hero-card:hover{transform:translateY(-6px);box-shadow:0 20px 60px rgba(0,0,0,0.6);}
    .hero-title{font-size:20px;color:var(--muted);}
    .hero-value{font-size:44px;font-weight:800;letter-spacing:-0.5px;}

    /* Table */
    .card{
      border-radius:14px;
      padding:18px;
      background:rgba(255,255,255,0.02);
      border:1px solid rgba(255,255,255,0.03);
    }
    .table-recent{width:100%;border-collapse:collapse;font-size:15px;}
    .table-recent th, .table-recent td{
      padding:14px 10px;
      border-bottom:1px solid rgba(255,255,255,0.03);
      vertical-align:middle;
    }
    .table-recent th{font-weight:700;color:var(--muted);font-size:13px;text-align:left;}
    .poster{width:70px;height:70px;object-fit:cover;border-radius:8px;}

    /* Right column */
    .right{display:flex;flex-direction:column;gap:18px;}
    .quick-card{
      border-radius:12px;
      padding:16px;
      background:rgba(255,255,255,0.02);
      border:1px solid rgba(255,255,255,0.03);
    }
    .quick-card a{
      display:block;
      padding:12px;
      border-radius:10px;
      text-decoration:none;
      color:#fff;
      margin-bottom:8px;
      background:linear-gradient(90deg,rgba(255,255,255,0.01),rgba(255,255,255,0.00));
      border:1px solid rgba(255,255,255,0.02);
      font-weight:700;
    }
    .quick-card a:hover{transform:translateY(-4px);box-shadow:0 20px 40px rgba(0,0,0,0.5);}
    @media(max-width:1100px){.layout{grid-template-columns:1fr;}.logo-large{width:96px;}}
  </style>
</head>
<body>

  <!-- TOP BAR -->
  <div class="topbar">
    <div class="logo-wrap">
      <a href="../index.php"> <img class="logo-large" src="logo.jpg" alt="FlickFix Logo"> </a>
      <div class="dash-title">Admin Dashboard</div>
    </div>
    <div class="top-actions">
      <span class="meta">Signed in as <strong><?= esc($_SESSION['user']['username'] ?? $_SESSION['user']['email'] ?? 'Admin') ?></strong></span>
      <a class="btn-ghost" href="../logout.php">Logout</a>
    </div>
  </div>

  <!-- LAYOUT -->
  <div class="layout">
    <div class="left">
      <div class="hero-row">
        <div class="hero-card">
          <div>
            <div class="hero-title">Total Movies</div>
            <div class="hero-value"><?= number_format($moviesCount) ?></div>
          </div>
          <div class="meta">Manage all movies and details</div>
        </div>
        <div class="hero-card">
          <div>
            <div class="hero-title">Total Users</div>
            <div class="hero-value"><?= number_format($usersCount) ?></div>
          </div>
          <div class="meta">Admins: <?= number_format($adminsCount) ?></div>
        </div>
      </div>

      <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px">
          <h3 style="margin:0;font-size:18px">Recent Movies</h3>
        </div>

        <div style="overflow:auto">
          <table class="table-recent">
            <thead>
              <tr><th>Poster</th><th>Title</th><th>Year</th><th>Release</th><th>Director</th></tr>
            </thead>
            <tbody>
            <?php if (empty($recent)): ?>
              <tr><td colspan="5" style="text-align:center;color:var(--muted);padding:32px">No movies yet</td></tr>
            <?php else: foreach ($recent as $m): ?>
              <tr>
                <td>
                  <?php if (!empty($m['poster']) && file_exists(__DIR__ . '/../uploads/' . $m['poster'])): ?>
                    <img class="poster" src="../uploads/<?= htmlspecialchars($m['poster']) ?>" alt="">
                  <?php else: ?>
                    <div class="poster" style="display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,0.02);color:#999">—</div>
                  <?php endif; ?>
                </td>
                <td style="font-weight:600"><?= esc($m['title']) ?></td>
                <td><?= esc($m['year']) ?></td>
                <td><?= esc($m['release_date']) ?></td>
                <td><?= esc($m['director']) ?></td>
              </tr>
            <?php endforeach; endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="right">
      <div class="quick-card">
        <h4 style="margin:0 0 8px 0">Quick Actions</h4>
        <a href="movie/add_movie.php">➕ Add Movie</a>
        <a href="user/add_user.php">➕ Add User</a>
        <a href="movie/movies.php">🎬 Manage Movies</a>
        <a href="user/users.php">👥 Manage Users</a>
      </div>

      <div class="quick-card">
        <h4 style="margin:0 0 8px 0">System Info</h4>
        <div class="meta">PHP: <?= phpversion() ?> · MySQL: <?= $conn->server_info ?></div>
        <div class="meta" style="margin-top:8px"><?= date('Y-m-d H:i') ?></div>
      </div>
    </div>
  </div>

</body>
</html>
