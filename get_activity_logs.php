<?php
header("Content-Type: application/json");

/* SECURED TOKEN CHECK */
$VALID_TOKEN = "EDUTRACK_ADMIN_PANEL__2025_SECURE_TOKEN_8934_AX";

if (!isset($_GET["token"]) || $_GET["token"] !== $VALID_TOKEN) {
    http_response_code(403);
    echo json_encode(["error" => "Invalid admin token"]);
    exit;
}

/* DB CONFIG */
$pdo = new PDO("mysql:host=localhost;dbname=edutrack;charset=utf8mb4", "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$stmt = $pdo->query("
  SELECT id, event_type, page, room_id, step_index, query_text, details, created_at
  FROM activity_logs
  ORDER BY created_at DESC
  LIMIT 50
");

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
