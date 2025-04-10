<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) && !isset($_SESSION['officer_id'])) {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'] ?? null;
$role = $_SESSION['role'] ?? null;

if ($role === 'officer') {
    $stmt = $conn->prepare("SELECT * FROM violations ORDER BY violation_date DESC");
} elseif ($role === 'user' && $username) {
    $stmt = $conn->prepare("SELECT * FROM violations WHERE offender_name = ? ORDER BY violation_date DESC");
    $stmt->bind_param("s", $username);
} else {
    header("Location: index.php");
    exit();
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= ($role === 'user') ? 'My Violations' : 'All Violations' ?></title>
    <style>
        body {
            background-color: #181818; /* Darker background */
            color: #e0e0e0; /* Lighter text */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Modern font */
            margin: 0;
            padding: 30px; /* Increased padding */
            line-height: 1.6;
        }

        h2 {
            color: #00bcd4; /* Accent color */
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #2c2c2c; /* Darker table background */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Subtle shadow */
            border-radius: 8px; /* Rounded corners */
            overflow: hidden;
        }

        th, td {
            padding: 15px; /* Increased padding */
            border-bottom: 1px solid #444; /* Darker border */
            text-align: left;
        }

        th {
            background-color: #333;
            color: #fff;
            font-weight: bold;
            text-transform: uppercase; /* Uppercase headers */
        }

        tr:hover {
            background-color: #3a3a3a; /* Slightly lighter hover */
        }

        .button {
            display: inline-block;
            background-color: #00bcd4; /* Accent button color */
            color: #fff;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease; /* Smooth transition */
        }

        .button:hover {
            background-color: #008ba7; /* Darker hover */
        }

        .paid {
            color: #4caf50; /* Green for paid */
            font-weight: bold;
        }

        .unpaid {
            color: #f44336; /* Red for unpaid */
            font-weight: bold;
        }

        p a {
            color: #00bcd4;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        p a:hover {
            color: #008ba7;
        }
    </style>
</head>
<body>
    <h2><?= ($role === 'user') ? 'My Violations' : 'All Recorded Violations' ?></h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Vehicle Number</th>
                <th>Offender</th>
                <th>Offense</th>
                <th>Fine (₹)</th>
                <th>Date</th>
                <th>Time</th>
                <th>Location</th>
                <th>Officer</th>
                <th>Status</th>
                <?php if ($role === 'user'): ?>
                    <th>Action</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['vehicle_number']) ?></td>
                    <td><?= htmlspecialchars($row['offender_name']) ?></td>
                    <td><?= htmlspecialchars($row['offense']) ?></td>
                    <td><?= htmlspecialchars($row['fine_amount']) ?></td>
                    <td><?= htmlspecialchars($row['violation_date']) ?></td>
                    <td><?= htmlspecialchars($row['violation_time']) ?></td>
                    <td><?= htmlspecialchars($row['location']) ?></td>
                    <td><?= htmlspecialchars($row['officer_name']) ?></td>
                    <td class="<?= htmlspecialchars($row['fine_status']) ?>"><?= htmlspecialchars(ucfirst($row['fine_status'])) ?></td>
                    <?php if ($role === 'user'): ?>
                        <td>
                            <?php if ($row['fine_status'] === 'unpaid'): ?>
                                <a href="pay_fine.php?id=<?= htmlspecialchars($row['id']) ?>" class="button">Pay Fine</a>
                            <?php else: ?>
                                <span class="paid">Paid</span>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endwhile; ?>
            <?php if ($result->num_rows === 0): ?>
                <tr><td colspan="<?= ($role === 'user') ? '11' : '10' ?>" style="text-align: center; padding: 20px; font-style: italic; color: #aaa;">
                    <?= ($role === 'user') ? 'No violations found for your account.' : 'No violations recorded yet.' ?>
                </td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <p style="margin-top: 30px; text-align: center;"><a href="<?= ($role === 'user') ? 'user_dashboard.php' : 'officer_dashboard.php' ?>">Back to Dashboard</a></p>
</body>
</html>