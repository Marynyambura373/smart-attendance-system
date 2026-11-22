<?php
session_start();
if (!isset($_SESSION['fullname'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['course_code'])) {
    $course_code = urlencode(trim($_POST['course_code']));
    
    // URL the student will visit when scanning the QR code
    $attendance_url = "http://localhost/smart-attendance-system/mark_attendance.php?course=" . $course_code;
    
    // Generate QR image using QR Server API
    $qr_image = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" . urlencode($attendance_url);
} else {
    header("Location: lecturer_dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Attendance QR Code</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin-top: 50px; background: #f4f7f6; }
        .qr-box { background: white; padding: 30px; display: inline-block; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="qr-box">
        <h2>Course: <?php echo htmlspecialchars($_POST['course_code']); ?></h2>
        <p>Students: Scan this code to mark your attendance</p>
        <img src="<?php echo $qr_image; ?>" alt="Attendance QR Code">
        <br><br>
        <a href="<?php echo $attendance_url; ?>" target="_blank">Test Attendance Link</a> | 
        <a href="lecturer_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>