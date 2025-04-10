<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <style>
        body {
            background-color: #181818;
            color: #e0e0e0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            line-height: 1.6;
        }

        .dashboard-container {
            background-color: #2c2c2c;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        h2 {
            color: #00bcd4;
            margin-bottom: 30px;
        }

        p {
            color: #ccc;
            margin-bottom: 20px;
        }

        .dashboard-links a {
            display: inline-block;
            background-color: #00bcd4;
            color: #fff;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px;
            transition: background-color 0.3s ease;
        }

        .dashboard-links a:hover {
            background-color: #008ba7;
        }

        .logout-button {
            background-color: #f44336; /* Red for logout */
        }

        .logout-button:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h2>User Dashboard</h2>
        <p>Welcome, User <?= htmlspecialchars($_SESSION['name'] ?? 'Guest') ?>!</p>

        <div class="dashboard-links">
            <a href="view_violations.php">View My Violations</a>
            <a href="logout.php" class="logout-button">Logout</a>
        </div>
    </div>
</body>
</html>