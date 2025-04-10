<?php
session_start();
include 'db.php';

if (isset($_SESSION['admin_id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$login_error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT admin_id, password, name FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_id'] = $row['admin_id'];
            $_SESSION['username'] = $username;
            $_SESSION['name'] = $row['name'];
            $_SESSION['role'] = 'admin';
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $login_error = "Invalid admin username or password.";
        }
    } else {
        $login_error = "Invalid admin username or password.";
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Traffic Violation System - Admin Login</title>
    <style>
        body { background-color: #1e1e1e; color: #d4d4d4; font-family: sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .login-container { background-color: #2d2d2d; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); width: 300px; }
        h2 { text-align: center; color: #eee; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #ccc; }
        input[type="text"], input[type="password"] { width: calc(100% - 12px); padding: 10px; border: 1px solid #555; border-radius: 4px; background-color: #333; color: #eee; box-sizing: border-box; }
        button { background-color: #ffc107; color: #333; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; width: 100%; }
        button:hover { background-color: #e0a800; }
        .error-message { color: #f44336; margin-top: 10px; text-align: center; }
        .back-link { display: block; margin-top: 15px; text-align: center; color: #ccc; text-decoration: none; }
        .back-link:hover { color: #eee; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <?php if ($login_error): ?>
            <p class="error-message"><?= $login_error ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password"