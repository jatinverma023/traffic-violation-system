<?php
session_start();
include 'db.php';

if (!isset($_SESSION['officer_id']) || $_SESSION['role'] !== 'officer') {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vehicle_number = trim($_POST['vehicle_number']);
    $offender_name = trim($_POST['offender_name']);
    $offense = trim($_POST['offense']);
    $violation_date = trim($_POST['violation_date']);
    $violation_time = trim($_POST['violation_time']) ?? null;
    $location = trim($_POST['location']) ?? null;
    $fine_amount = filter_var($_POST['fine_amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $officer_id = $_SESSION['officer_id'];
    $officer_name = $_SESSION['name'];

    // Input Validation (Basic - Expand this!)
    if (empty($vehicle_number) || empty($offender_name) || empty($offense) || empty($violation_date) || !is_numeric($fine_amount) || $fine_amount <= 0) {
        $_SESSION['error_message'] = "Please fill in all required fields with valid data.";
        header("Location: add_violation.php");
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO violations (vehicle_number, offender_name, offense, violation_date, violation_time, location, fine_amount, officer_id, officer_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssdss", $vehicle_number, $offender_name, $offense, $violation_date, $violation_time, $location, $fine_amount, $officer_id, $officer_name);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Violation recorded successfully!";
        header("Location: view_violations.php");
    } else {
        $_SESSION['error_message'] = "Error recording violation: " . $stmt->error;
        header("Location: add_violation.php");
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: add_violation.php"); // Redirect if accessed directly
    exit();
}
?>