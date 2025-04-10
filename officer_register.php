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
    $badge_number = trim($_POST['badge_number']);

    if (empty($username) || empty($password) || empty($confirm_password) || empty($name) || empty($badge_number)) {
        $registration_error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $registration_error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $registration_error = "Password must be at least 6 characters long.";
    } else {
        // Check if username already exists
        $stmt_username = $conn->prepare("SELECT officer_id FROM officers WHERE username = ?");
        $stmt_username->bind_param("s", $username);
        $stmt_username->execute();
        $result_username = $stmt_username->get_result();

        if ($result_username->num_rows > 0) {
            $registration_error = "Username already exists. Please choose a different one.";
            $stmt_username->close();
        } else {
            $stmt_username->close(); // Close the username check statement

            // Check if badge number already exists
            $stmt_badge = $conn->prepare("SELECT officer_id FROM officers WHERE badge_number = ?");
            $stmt_badge->bind_param("s", $badge_number);
            $stmt_badge->execute();
            $result_badge = $stmt_badge->get_result();

            if ($result_badge->num_rows > 0) {
                $registration_error = "Badge number already exists. Please choose a different one.";
                $stmt_badge->close();
            } else {
                $stmt_badge->close(); // Close the badge number check statement

                // Insert the new officer
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $insert_stmt = $conn->prepare("INSERT INTO officers (username, password, name, badge_number) VALUES (?, ?, ?, ?)");
                $insert_stmt->bind_param("ssss", $username, $hashed_password, $name, $badge_number);

                if ($insert_stmt->execute()) {
                    $registration_success = "Officer registration successful! <a href='index.php'>Login here</a>";
                } else {
                    $registration_error = "Officer registration failed. Please try again later.";
                }
                $insert_stmt->close(); // Close the insert statement
            }
        }
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Traffic Violation System - Officer Registration</title>
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
            background-color: #00bcd4; /* Accent color for officer register */
            color: #fff;
            padding: 12px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #008ba7;
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
        <h2>Officer Registration</h2>
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
            <div class="form-group">
                <label for="badge_number">Badge Number:</label>
                <input type="text" id="badge_number" name="badge_number" required>
            </div>
            <button type="submit">Register</button>
        </form>
        <a href="index.php" class="login-link">Already have an account? Login here</a>
    </div>
</body>
</html>