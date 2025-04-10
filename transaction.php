<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error_message'] = "Invalid request: Missing or invalid violation ID.";
    header("Location: view_violations.php");
    exit();
}

$violation_id = intval($_GET['id']);

// Fetch the violation details for display
$stmt = $conn->prepare("SELECT id, vehicle_number, offense, fine_amount FROM violations WHERE id = ? AND offender_name = ?");
$stmt->bind_param("is", $violation_id, $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    $_SESSION['error_message'] = "Violation not found or unauthorized access.";
    header("Location: view_violations.php");
    exit();
}

$violation = $result->fetch_assoc();
$stmt->close();

// Handle the "Confirm Payment" action
if (isset($_POST['confirm_payment'])) {
    $update_stmt = $conn->prepare("UPDATE violations SET fine_status = 'paid' WHERE id = ?");
    $update_stmt->bind_param("i", $violation_id);

    if ($update_stmt->execute()) {
        $_SESSION['success_message'] = "Fine paid successfully!";
    } else {
        $_SESSION['error_message'] = "Failed to update fine status: " . $update_stmt->error;
    }

    $update_stmt->close();
    $conn->close();
    header("Location: view_violations.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Confirm Payment</title>
    <style>
        body { background-color: #1e1e1e; color: #d4d4d4; font-family: sans-serif; margin: 0; padding: 20px; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .transaction-container { background-color: #2d2d2d; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); width: 400px; }
        h2 { text-align: center; color: #eee; margin-bottom: 20px; }
        .violation-details { margin-bottom: 20px; }
        .detail-row { margin-bottom: 10px; color: #ccc; }
        .detail-label { font-weight: bold; color: #eee; }
        .payment-options { margin-bottom: 20px; }
        .payment-options label { display: block; margin-bottom: 5px; color: #ccc; }
        button { background-color: #28a745; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; width: 100%; }
        button:hover { background-color: #218838; }
        .back-link { display: block; margin-top: 20px; text-align: center; color: #ccc; text-decoration: none; }
        .back-link:hover { color: #eee; }
        .error-message { color: #f44336; margin-top: 10px; text-align: center; }
    </style>
</head>
<body>
    <div class="transaction-container">
        <h2>Confirm Payment</h2>
        <?php if (isset($_SESSION['error_message'])): ?>
            <p class="error-message"><?= $_SESSION['error_message'] ?></p>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <div class="violation-details">
            <div class="detail-row"><span class="detail-label">Violation ID:</span> <?= htmlspecialchars($violation['id']) ?></div>
            <div class="detail-row"><span class="detail-label">Vehicle Number:</span> <?= htmlspecialchars($violation['vehicle_number']) ?></div>
            <div class="detail-row"><span class="detail-label">Offense:</span> <?= htmlspecialchars($violation['offense']) ?></div>
            <div class="detail-row"><span class="detail-label">Fine Amount:</span> ₹<?= htmlspecialchars($violation['fine_amount']) ?></div>
            <p style="color: #ccc;">Select a payment method (not actually implemented in this basic version):</p>
            <div class="payment-options">
                <label><input type="radio" name="payment_method" value="credit_card"> Credit Card</label><br>
                <label><input type="radio" name="payment_method" value="debit_card"> Debit Card</label><br>
                <label><input type="radio" name="payment_method" value="upi"> UPI</label>
            </div>
        </div>

        <form method="POST" action="">
            <button type="submit" name="confirm_payment">Confirm Payment</button>
        </form>

        <a href="view_violations.php" class="back-link">Back to My Violations</a>
    </div>
</body>
</html>