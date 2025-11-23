<?php
// get_activity_logs.php
header('Content-Type: application/json');

// DB config – use your real credentials
$host = "localhost";
$db   = "edutrack";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // get latest 50 logs, newest first
    $stmt = $pdo->query("
        SELECT id,
               event_type,
               page,
               room_id,
               step_index,
               query_text,
               user_agent,
               details,
               created_at
        FROM activity_logs
        ORDER BY created_at DESC
        LIMIT 50
    ");

    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($logs);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "error" => true,
        "message" => $e->getMessage()
    ]);
}
