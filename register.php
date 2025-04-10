<?php
session_start();
include 'db.php';

$registration_error = "";
$registration_success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $name = trim($_POST['name']);

    if (empty($username) || empty($password) || empty($confirm_password) || empty($name)) {
        $registration_error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $registration_error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $registration_error = "Password must be at least 6 characters long.";
    } else {
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $registration_error = "Username already exists. Please choose a different one.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_stmt = $conn->prepare("INSERT INTO users (username, password, name) VALUES (?, ?, ?)");
            $insert_stmt->bind_param("sss", $username, $hashed_password, $name);
            if ($insert_stmt->execute()) {
                $registration_success = "User registration successful! <a href='index.php'>Login here</a>";
            } else {
                $registration_error = "User registration failed. Please try again later.";
            }
            $insert_stmt->close();
        }
        $stmt->close();
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Traffic Violation System - User Registration</title>
    <style>
        body {
            background-color: #181818;
            color: #e0e0e0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 30px;
            line-height: 1.6;
        }
        .register-container {
            background-color: #2c2c2c;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 400px;
        }
        h2 {
            text-align: center;
            color: #00bcd4;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #ccc;
        }
        input[type="text"], input[type="password"] {
            width: calc(100% - 20px);
            padding: 12px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #333;
            color: #eee;
            box-sizing: border-box;
        }
        button {
            background-color: #28a745; /* Green for register */
            color: #fff;
            padding: 12px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #218838;
        }
        .error-message {
            color: #f44336;
            margin-top: 15px;
            text-align: center;
        }
        .success-message {
            color: #4caf50;
            margin-top: 15px;
            text-align: center;
        }
        .login-link {
            display: block;
            margin-top: 25px;
            text-align: center;
            color: #00bcd4;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .login-link:hover {
            color: #008ba7;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>User Registration</h2>
        <?php if ($registration_error): ?>
            <p class="error-message"><?= $registration_error ?></p>
        <?php endif; ?>
        <?php if ($registration_success): ?>
            <p class="success-message"><?= $registration_success ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <button type="submit">Register</button>
        </form>
        <a href="index.php" class="login-link">Already have an account? Login here</a>
    </div>
</body>
</html>