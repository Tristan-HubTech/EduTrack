<?php
header('Content-Type: application/json');

$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

if (!$data) {
    echo json_encode(["error" => true, "message" => "Invalid JSON"]);
    exit;
}

require "db_connect.php";

try {
    $stmt = $pdo->prepare("
        INSERT INTO activity_logs
        (event_type, page, room_id, step_index, query_text, user_agent, details)
        VALUES (:event_type, :page, :room_id, :step_index, :query_text, :user_agent, :details)
    ");

    $stmt->execute([
        ":event_type" => $data["eventType"] ?? null,
        ":page"       => $data["page"] ?? null,
        ":room_id"    => $data["roomId"] ?? null,
        ":step_index" => $data["stepIndex"] ?? null,
        ":query_text" => $data["queryText"] ?? null,
        ":user_agent" => $data["userAgent"] ?? null,
        ":details"    => json_encode($data["details"] ?? [])
    ]);

    echo json_encode(["success" => true]);

} catch (Exception $e) {
    echo json_encode(["error" => true, "message" => $e->getMessage()]);
}
?>
