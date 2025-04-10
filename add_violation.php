<?php
session_start();
if (!isset($_SESSION['officer_id']) || $_SESSION['role'] !== 'officer') {
    header("Location: index.php");
    exit();
}

$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db.php';

    $vehicle_number = trim($_POST['vehicle_number']);
    $offender_name = trim($_POST['offender_name']);
    $offense = trim($_POST['offense']);
    $violation_date = $_POST['violation_date'];
    $violation_time = $_POST['violation_time'] ?? null;
    $location = trim($_POST['location']) ?? null;
    $fine_amount = filter_var($_POST['fine_amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $officer_id = $_SESSION['officer_id'];
    $officer_name = $_SESSION['name'];

    if (empty($vehicle_number) || empty($offender_name) || empty($offense) || empty($violation_date) || !is_numeric($fine_amount) || $fine_amount <= 0) {
        $error_message = "All required fields must be filled in with valid data.";
    } else {
        $stmt = $conn->prepare("INSERT INTO violations (vehicle_number, offender_name, offense, violation_date, violation_time, location, fine_amount, officer_id, officer_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssdss", $vehicle_number, $offender_name, $offense, $violation_date, $violation_time, $location, $fine_amount, $officer_id, $officer_name);

        if ($stmt->execute()) {
            $success_message = "Violation recorded successfully!";
        } else {
            $error_message = "Error recording violation: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Record New Violation</title>
    <style>
        body { background-color: #1e1e1e; color: #d4d4d4; font-family: sans-serif; margin: 0; padding: 20px; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .form-container { background-color: #2d2d2d; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); width: 400px; }
        h2 { text-align: center; color: #eee; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #ccc; }
        input[type="text"], input[type="date"], input[type="time"], input[type="number"] { width: calc(100% - 12px); padding: 10px; border: 1px solid #555; border-radius: 4px; background-color: #333; color: #eee; box-sizing: border-box; }
        button { background-color: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; width: 100%; }
        button:hover { background-color: #0056b3; }
        .error-message { color: #f44336; margin-top: 10px; text-align: center; }
        .success-message { color: #4caf50; margin-top: 10px; text-align: center; }
        .back-link { display: block; margin-top: 20px; text-align: center; color: #ccc; text-decoration: none; }
        .back-link:hover { color: #eee; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Record New Violation</h2>
        <?php if ($error_message): ?>
            <p class="error-message"><?= $error_message ?></p>
        <?php endif; ?>
        <?php if ($success_message): ?>
            <p class="success-message"><?= $success_message ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="vehicle_number">Vehicle Number:</label>
                <input type="text" id="vehicle_number" name="vehicle_number" required>
            </div>
            <div class="form-group">
                <label for="offender_name">Offender Name:</label>
                <input type="text" id="offender_name" name="offender_name" required>
            </div>
            <div class="form-group">
                <label for="offense">Offense:</label>
                <input type="text" id="offense" name="offense" required>
            </div>
            <div class="form-group">
                <label for="violation_date">Violation Date:</label>
                <input type="date" id="violation_date" name="violation_date" required>
            </div>
            <div class="form-group">
                <label for="violation_time">Violation Time:</label>
                <input type="time" id="violation_time" name="violation_time">
            </div>
            <div class="form-group">
                <label for="location">Location:</label>
                <input type="text" id="location" name="location">
            </div>
            <div class="form-group">
                <label for="fine_amount">Fine Amount (₹):</label>
                <input type="number" step="0.01" id="fine_amount" name="fine_amount" required>
            </div>
            <button type="submit">Record Violation</button>
        </form>
        <a href="officer_dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>