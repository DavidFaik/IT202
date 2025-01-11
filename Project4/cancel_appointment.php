<?php
session_start(); 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connect.php';

if (!isset($_SESSION['plumberID'])) {
    die("Unauthorized access. Please log in first.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $serviceID = trim($_POST['serviceID']);

    $sql = "SELECT ServiceID FROM ServiceRecords WHERE ServiceID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $serviceID);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>
                if (confirm('You are about to CANCEL this service appointment. Canceling this service appointment will also cancel any supplies ordered for the service. Are you want to do so?')) {
                    window.location.href = 'cancel_appointment.php?confirm=yes&serviceID=$serviceID';
                } else {
                    window.location.href = 'cancel_appointment.php';
                }
              </script>";
    } else {
        echo "<script>alert('Appointment does not exist.'); window.history.back();</script>";
    }
    $stmt->close();
} elseif (isset($_GET['confirm']) && $_GET['confirm'] == 'yes' && isset($_GET['serviceID'])) {
    $serviceID = $_GET['serviceID'];

    $deleteSuppliesSQL = "DELETE FROM SuppliesRecords WHERE ServiceID = ?";
    $deleteSuppliesStmt = $con->prepare($deleteSuppliesSQL);
    $deleteSuppliesStmt->bind_param("i", $serviceID);
    $deleteSuppliesStmt->execute();
    $deleteSuppliesStmt->close();

    $deleteSQL = "DELETE FROM ServiceRecords WHERE ServiceID = ?";
    $deleteStmt = $con->prepare($deleteSQL);
    $deleteStmt->bind_param("i", $serviceID);
    if ($deleteStmt->execute()) {
        echo "<script>alert('Service appointment canceled successfully.'); window.location.href = 'validate_login.php';</script>";
    } else {
        echo "Error canceling appointment: " . $deleteStmt->error;
    }
    $deleteStmt->close();
} else {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Cancel Service Appointment</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="nav-bar">
            <a href="index">Logout</a>
        </div>
        <div class="form-container">
            <h2>Cancel Service Appointment</h2>
            <form method="POST" action="">
                <label for="serviceID">Service Appointment ID: <span style="color:red;">Required</span></label>
                <input type="text" id="serviceID" name="serviceID" required>
                <div class="buttons">
                    <button type="submit">Cancel Appointment</button>
                    <button type="reset">Reset</button>
                </div>
            </form>
        </div>
    </body>
    </html>
    <?php
}

$con->close();
?>
