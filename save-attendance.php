<?php
header('Content-Type: application/json');
require_once 'connection.php'; // Include your database connection

// Get incoming JSON data from the frontend fetch request
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['student_id']) || !isset($data['session_id'])) {
    echo json_encode(["status" => "error", "message" => "Invalid request data."]);
    exit();
}

$studentId = $data['student_id'];
$sessionId = $data['session_id'];

try {
    // 1. Check if the student has already scanned for this session (Duplicate Prevention)
    $checkStmt = $pdo->prepare("SELECT * FROM attendance_logs WHERE student_id = ? AND session_id = ?");
    $checkStmt->execute([$studentId, $sessionId]);

    if ($checkStmt->rowCount() > 0) {
        echo json_encode(["status" => "duplicate", "message" => "Attendance already recorded for this session! Duplicate attempt rejected."]);
        exit();
    }

    // 2. Insert new attendance record into MySQL
    $insertStmt = $pdo->prepare("INSERT INTO attendance_logs (student_id, session_id, timestamp, status) VALUES (?, ?, NOW(), 'Present')");
    $insertStmt->execute([$studentId, $sessionId]);

    echo json_encode(["status" => "success", "message" => "Attendance successfully marked and stored in MySQL!"]);

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Server error: " . $e->getMessage()]);
}
?>