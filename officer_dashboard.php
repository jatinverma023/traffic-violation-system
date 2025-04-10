<?php
session_start();
if (!isset($_SESSION['officer_id']) || $_SESSION['role'] !== 'officer') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Officer Dashboard</title>
    <style>
        /* Your modern dark theme styles for officer dashboard */
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
            background-color: #f44336;
        }

        .logout-button:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h2>Officer Dashboard</h2>
        <p>Welcome, Officer <?= htmlspecialchars($_SESSION['name'] ?? 'Officer') ?>!</p>

        <div class="dashboard-links">
            <a href="add_violation.php">Record New Violation</a>
            <a href="view_violations.php">View All Violations</a>
            <a href="logout.php" class="logout-button">Logout</a>
        </div>
    </div>
</body>
</html>