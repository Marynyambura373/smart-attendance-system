<?php
session_start();

if (!isset($_SESSION['fullname'])) {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Lecturer Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f4f7f6; }
        .dashboard-card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-width: 500px; }
        a { color: #d9534f; text-decoration: none; font-weight: bold; }
        a:hover { text-decoration: underline; }
        button { padding: 10px 15px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="dashboard-card">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?></h1>
        <h2>Lecturer Dashboard</h2>
        <hr>
        
        <h3>Generate Attendance QR Code</h3>
        <form action="../generate-qr.php" method="POST">
            <div style="margin-bottom: 15px;">
                <label for="course_code">Course Code / Name:</label><br>
                <input type="text" id="course_code" name="course_code" required placeholder="e.g., BIT 3101" style="width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box;">
            </div>
            <button type="submit">Generate QR Code</button>
        </form>

        <br>
        <p><a href="../logout.php">Logout</a></p>
    </div>
</body>
</html>