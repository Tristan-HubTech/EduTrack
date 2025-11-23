<?php
// log_activity.php

$host = 'localhost';
$db   = 'edutrack';
$user = 'admin';
$pass = 'admin';

$dsn  = "mysql:host=$host;dbname=$db;charset=utf8mb4";

header('Content-Type: application/json');

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'DB connection failed']);
    exit;
}

$raw  = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON']);
    exit;
}

$EXPECTED_TOKEN = 'EDUTRACK_TOUR_V1';

if (!isset($data['token']) || $data['token'] !== $EXPECTED_TOKEN) {
    http_response_code(403);
    echo json_encode(['error' => 'Invalid token']);
    exit;
}

$eventType = $data['eventType'] ?? 'unknown';
$page      = $data['page'] ?? 'tour';
$roomId    = $data['roomId'] ?? null;
$stepIndex = isset($data['stepIndex']) ? (int)$data['stepIndex'] : null;
$queryText = $data['queryText'] ?? null;
$details   = isset($data['details']) ? json_encode($data['details']) : null;
$userAgent = $data['userAgent'] ?? '';
$createdAt = $data['timestamp'] ?? date('Y-m-d H:i:s');
$token     = $data['token'];

$stmt = $pdo->prepare("
    INSERT INTO activity_logs
      (event_type, page, room_id, step_index, query_text, details, user_agent, created_at, token)
    VALUES
      (:event_type, :page, :room_id, :step_index, :query_text, :details, :user_agent, :created_at, :token)
");

$stmt->execute([
    ':event_type' => $eventType,
    ':page'       => $page,
    ':room_id'    => $roomId,
    ':step_index' => $stepIndex,
    ':query_text' => $queryText,
    ':details'    => $details,
    ':user_agent' => $userAgent,
    ':created_at' => $createdAt,
    ':token'      => $token,
]);

echo json_encode(['status' => 'ok']);
