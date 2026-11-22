<?php
session_start();
include 'connection.php'; // Database connection

// Ensure only Administrators can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Administrator') {
    header("Location: Login.php");
    exit();
}

// Fetch total counts for statistics overview
$user_count = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
$session_count = $conn->query("SELECT COUNT(*) FROM sessions")->fetchColumn();
$attendance_count = $conn->query("SELECT COUNT(*) FROM attendance")->fetchColumn();

// Fetch recent users list
$users_result = $conn->query("SELECT id, email, role FROM users ORDER BY id DESC LIMIT 10");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - AI Smart Attendance System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-dark px-4">
        <span class="navbar-brand mb-0 h1">Administrator Portal</span>
        <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </nav>

    <div class="container my-4">
        <h2>Welcome, Administrator</h2>
        <p class="text-muted">Here is an overview of the system statistics and user management.</p>

        <!-- Statistics Cards -->
        <div class="row my-4">
            <div class="col-md-4">
                <div class="card text-white bg-primary shadow-sm mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <h3><?php echo $user_count; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success shadow-sm mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Sessions</h5>
                        <h3><?php echo $session_count; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-info shadow-sm mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Attendance Records</h5>
                        <h3><?php echo $attendance_count; ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Management Table -->
        <div class="card shadow-sm p-4">
            <h4 class="mb-3">Registered Users Overview</h4>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Email Address</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $users_result->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><span class="badge bg-secondary"><?php echo htmlspecialchars($row['role']); ?></span></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>