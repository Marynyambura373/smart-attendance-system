<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Student') {
    header("Location: Login.html");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'smart_attendance');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$token = $_GET['token'] ?? '';
$student_id = $_SESSION['user_id'];

if (empty($token)) {
    die("Invalid attendance session token.");
}

// Verify token and expiration window
$stmt = $conn->prepare("SELECT * FROM class_sessions WHERE qr_token = ? AND expires_at > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($session = $result->fetch_assoc()) {
    $session_id = $session['id'];
    $course_code = $session['course_code'];

    // Check for duplicate attendance
    $check_dup = $conn->prepare("SELECT * FROM attendance_logs WHERE session_id = ? AND student_id = ?");
    $check_dup->bind_param("ii", $session_id, $student_id);
    $check_dup->execute();
    
    if ($check_dup->get_result()->num_rows > 0) {
        echo "<script>alert('You have already marked attendance for this class!'); window.location.href='Dashboard/student_dashboard.php';</script>";
    } else {
        $insert = $conn->prepare("INSERT INTO attendance_logs (session_id, student_id, course_code, status) VALUES (?, ?, ?, 'Present')");
        $insert->bind_param("iis", $session_id, $student_id, $course_code);
        if ($insert->execute()) {
            echo "<script>alert('Attendance successfully recorded for $course_code!'); window.location.href='Dashboard/student_dashboard.php';</script>";
        }
    }
} else {
    echo "<script>alert('This QR code session has expired or is invalid.'); window.location.href='Dashboard/student_dashboard.php';</script>";
}

$conn->close();
?>