<?php
session_start();
include 'php/connection.php'; // Adjust to 'connection.php' if authenticate_new.php is inside php/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit();
}

$login_input = trim($_POST['username'] ?? $_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');
$selected_role = trim($_POST['role'] ?? '');

if ($login_input === '' || $password === '') {
    $_SESSION['login_error'] = "Please enter both email/username and password.";
    header("Location: login.php");
    exit();
}

try {
    // Supports matching by email or username column if present; adjust column if needed
    $stmt = $conn->prepare("SELECT id, email, password, role FROM users WHERE email = ? LIMIT 1");
    if (!$stmt) {
        throw new Exception("Database prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $login_input);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result ? $result->fetch_assoc() : null;
    $stmt->close();

    if (!$user) {
        $_SESSION['login_error'] = "No account found with those credentials.";
        header("Location: login.php");
        exit();
    }

    $password_valid = password_verify($password, $user['password']) || ($password === $user['password']);
    if (!$password_valid) {
        $_SESSION['login_error'] = "Invalid password.";
        header("Location: login.php");
        exit();
    }

    if ($selected_role !== '' && strcasecmp($user['role'], $selected_role) !== 0) {
        $_SESSION['login_error'] = "Selected role does not match this account.";
        header("Location: login.php");
        exit();
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['role'];

    switch (strtolower(trim($user['role']))) {
        case 'admin':
            header("Location: admin_dashboard.php");
            exit();
        case 'lecturer':
            header("Location: lecturer_dashboard.php");
            exit();
        case 'student':
            header("Location: Dashboard/Student.php");
            exit();
        default:
            $_SESSION['login_error'] = "Unrecognized user role.";
            header("Location: login.php");
            exit();
    }
} catch (Throwable $e) {
    $_SESSION['login_error'] = "Authentication error: " . $e->getMessage();
    header("Location: login.php");
    exit();
}