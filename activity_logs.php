<?php
session_start();

// ========== SECURITY TOKEN ==========
$ADMIN_SECURITY_TOKEN = "EDUTRACK_ADMIN_PANEL__2025_TOKEN_9832";

// require token + login
if (!isset($_GET['token']) || $_GET['token'] !== $ADMIN_SECURITY_TOKEN) {
    http_response_code(403);
    echo "ACCESS DENIED (Invalid token)";
    exit;
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo "<script>window.location.href='admin-login.php';</script>";
    exit;
}

// ========== DATABASE ==========
$host = "localhost";
$db   = "edutrack";
$user = "root";
$pass = "";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $stmt = $pdo->query("SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT 500");
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>EduTrack • Activity Logs</title>

<style>
:root {
  --navy:#0b2a72;
  --navy-dark:#020617;
  --glass:#0a1a46e8;
  --border:#2f3d62;
  --text:#e5e7eb;
  --soft:#9ca3af;
  --accent:#1f4fb2;
}

body {
  background: radial-gradient(circle at top, #16348f 0, #020617 50%, #000);
  font-family: "Inter", Arial, sans-serif;
  margin: 0;
  padding: 0;
  color: var(--text);
}

.header {
  background: linear-gradient(90deg, rgba(10,42,120,0.9), rgba(4,9,30,0.9));
  padding: 18px 28px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid var(--border);
  box-shadow: 0 10px 25px rgba(0,0,0,0.6);
}

.header-title {
  font-size: 20px;
  font-weight: 700;
}

.btn-back {
  background: var(--accent);
  padding: 8px 18px;
  border-radius: 999px;
  color: white;
  text-decoration: none;
  font-size: 12px;
  font-weight: 600;
  text-transform: uppercase;
  box-shadow: 0 10px 20px rgba(0,0,0,0.5);
}
.btn-back:hover { filter: brightness(1.1); }

.container {
  max-width: 1100px;
  margin: 30px auto;
  padding: 0 20px;
}

.log-wrapper {
  margin-top: 20px;
  background: var(--glass);
  padding: 20px;
  border-radius: 18px;
  border: 1px solid var(--border);
  height: 80vh;
  overflow-y: auto;
  box-shadow: 0 20px 50px rgba(0,0,0,0.8);
}

.log {
  background: rgba(15,23,42,0.9);
  border: 1px solid #374151;
  padding: 12px 14px;
  margin-bottom: 10px;
  border-radius: 14px;
  font-size: 13px;
}

.log b { color: #fff; }
.log small { color: var(--soft); }
</style>
</head>

<body>

<div class="header">
  <div class="header-title">📜 EduTrack Activity Logs</div>

  <a class="btn-back" href="admin-dashboard.php?token=<?php echo $ADMIN_SECURITY_TOKEN; ?>">
    ← Back to Dashboard
  </a>
</div>

<div class="container">
  <h3>Total Logs: <?php echo count($logs); ?></h3>

  <div class="log-wrapper">
    <?php foreach ($logs as $row): ?>
    <div class="log">
      <b>Event:</b> <?php echo htmlspecialchars($row['event_type']); ?><br>
      <b>Room:</b> <?php echo htmlspecialchars($row['room_id']); ?><br>
      <b>Details:</b> <?php echo htmlspecialchars($row['details']); ?><br>
      <b>Query:</b> <?php echo htmlspecialchars($row['query_text']); ?><br>
      <small><b>Date:</b> <?php echo htmlspecialchars($row['created_at']); ?></small>
    </div>
    <?php endforeach; ?>
  </div>
</div>

</body>
</html>
