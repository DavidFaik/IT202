<?php
session_start(); 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connect.php';

$firstName = trim($_POST['firstName']);
$lastName = trim($_POST['lastName']);
$password = trim($_POST['password']);
$plumberID = trim($_POST['idNumber']);
$phoneNumber = trim($_POST['phoneNumber']);
$email = trim($_POST['email']);
$transaction = $_POST['transaction'];

if (empty($firstName) || empty($lastName) || empty($password) || empty($plumberID) || empty($phoneNumber)) {
    die("All fields are required.");
}

$sql = "SELECT PlumberID FROM Plumbers WHERE PlumberID = ? AND FirstName = ? AND LastName = ? AND Password = ? AND PhoneNumber = ?";
$stmt = $con->prepare($sql);

if (!$stmt) {
    die("SQL prepare failed: " . $con->error);
}

$stmt->bind_param("issss", $plumberID, $firstName, $lastName, $password, $phoneNumber);

if (!$stmt->execute()) {
    die("SQL execute failed: " . $stmt->error);
}

$stmt->bind_result($resultPlumberID);

if ($stmt->fetch()) {
    $_SESSION['plumberID'] = $resultPlumberID;

    switch ($transaction) {
        case "search":
            header("Location: search_plumber.php");
            exit();
        case "schedule":
            header("Location: schedule_appointment.php");
            exit();
        case "cancel":
            header("Location: cancel_appointment.php");
            exit();
        case "supplies":
            header("Location: request_supplies.php");
            exit();
        case "update":
            header("Location: update_customer.php");
            exit();
        case "create":
            header("Location: create_customer.php");
            exit();
        default:
            die("Invalid transaction type.");
    }
} else {
    echo "<script>alert('Invalid credentials! Please try again.'); window.history.back();</script>";
}

$stmt->close();
$con->close();
?>
