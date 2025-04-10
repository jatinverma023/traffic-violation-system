<?php
session_start();
include 'db.php';

if (isset($_SESSION['officer_id'])) {
    header("Location: officer_dashboard.php");
    exit();
}
if (isset($_SESSION['user_id'])) {
    header("Location: user_dashboard.php");
    exit();
}
if (isset($_SESSION['admin_id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

// Initialize the $login_error variable
$login_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = $_POST['role'];

    if (empty($username) || empty($password) || empty($role)) {
        $login_error = "Please enter username, password, and select a role.";
    } else {
        if ($role === 'officer') {
            $stmt = $conn->prepare("SELECT officer_id, password, name FROM officers WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                if (password_verify($password, $row['password'])) {
                    $_SESSION['officer_id'] = $row['officer_id'];
                    $_SESSION['username'] = $username;
                    $_SESSION['name'] = $row['name'];
                    $_SESSION['role'] = 'officer';
                    header("Location: officer_dashboard.php");
                    exit();
                } else {
                    $login_error = "Invalid officer username or password.";
                }
            } else {
                $login_error = "Invalid officer username or password.";
            }
            $stmt->close();
        } elseif ($role === 'user') {
            $stmt = $conn->prepare("SELECT user_id, password, name FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                if (password_verify($password, $row['password'])) {
                    $_SESSION['user_id'] = $row['user_id'];
                    $_SESSION['username'] = $username;
                    $_SESSION['name'] = $row['name'];
                    $_SESSION['role'] = 'user';
                    header("Location: user_dashboard.php");
                    exit();
                } else {
                    $login_error = "Invalid user username or password.";
                }
            } else {
                $login_error = "Invalid user username or password.";
            }
            $stmt->close();
        } elseif ($role === 'admin') {
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
        } else {
            $login_error = "Please select a valid role.";
        }
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Traffic Violation System - Login</title>
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
        .login-container {
            background-color: #2c2c2c;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 350px;
            opacity: 0; /* Initial state for animation */
            transform: translateY(-20px); /* Initial state for animation */
            animation: fadeInSlideDown 0.5s ease-out forwards; /* Apply animation */
        }
        @keyframes fadeInSlideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
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
        input[type="text"], input[type="password"], select {
            width: calc(100% - 20px);
            padding: 12px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #333;
            color: #eee;
            box-sizing: border-box;
        }
        button {
            background-color: #00bcd4;
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
            opacity: 0; /* Initial state for animation */
            transform: translateY(-5px); /* Initial state for animation */
            animation: fadeInSlideDownError 0.3s ease-out forwards; /* Apply animation */
        }
        @keyframes fadeInSlideDownError {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .register-links {
            margin-top: 25px;
            text-align: center;
        }
        .register-links a {
            color: #00bcd4;
            text-decoration: none;
            margin: 0 10px;
            transition: color 0.3s ease;
        }
        .register-links a:hover {
            color: #008ba7;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
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
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="role">Role:</label>
                <select id="role" name="role">
                    <option value="">-- Select Role --</option>
                    <option value="officer">Officer</option>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit">Login</button>
        </form>
        <div class="register-links">
            <a href="register.php">Register as User</a>
            <a href="officer_register.php">Register as Officer</a>
        </div>
    </div>
</body>
</html>