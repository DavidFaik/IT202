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
    $customerID = trim($_POST['customerID']);
    $streetAddress = trim($_POST['streetAddress']);
    $state = trim($_POST['state']);
    $zipCode = trim($_POST['zipCode']);
    $phoneNumber = trim($_POST['phoneNumber']);

    $sql = "SELECT CustomerID FROM Customers WHERE CustomerID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $customerID);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>
                if (confirm('You are about to UPDATE the record of the customer. Are you sure you want to do so?')) {
                    window.location.href = 'update_customer.php?confirm=yes&customerID=$customerID&streetAddress=" . urlencode($streetAddress) . "&state=$state&zipCode=$zipCode&phoneNumber=$phoneNumber';
                } else {
                    window.location.href = 'update_customer.php';
                }
              </script>";
    } else {
        echo "<script>
                alert('Customer Does Not Exist. You Will Be Redirected To Create A Client Account Form');
                window.location.href = 'create_customer.php';
              </script>";
    }
    $stmt->close();
} elseif (isset($_GET['confirm']) && $_GET['confirm'] == 'yes') {
    $customerID = $_GET['customerID'];
    $streetAddress = $_GET['streetAddress'];
    $state = $_GET['state'];
    $zipCode = $_GET['zipCode'];
    $phoneNumber = $_GET['phoneNumber'];

    $updateSQL = "UPDATE CustomerPersonalInfo SET StreetAddress = ?, State = ?, ZipCode = ?, PhoneNumber = ? WHERE CustomerID = ?";
    $updateStmt = $con->prepare($updateSQL);
    $updateStmt->bind_param("ssssi", $streetAddress, $state, $zipCode, $phoneNumber, $customerID);

    if ($updateStmt->execute()) {
        echo "<script>alert('Data Updated'); window.location.href = 'validate_login.php';</script>";
    } else {
        echo "Error updating customer record: " . $updateStmt->error;
    }
    $updateStmt->close();
} else {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Update Customer Records</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="nav-bar">
            <a href="index">Logout</a>
        </div>
        <div class="form-container">
            <h2>Update Customer Records</h2>
            <form method="POST" action="">
                <label for="customerID">Customer ID: <span style="color:red;">Required</span></label>
                <input type="text" id="customerID" name="customerID" required>

                <label for="streetAddress">Customer Address: <span style="color:red;">Required</span></label>
                <input type="text" id="streetAddress" name="streetAddress" required>

                <label for="state">Customer State: <span style="color:red;">Required</span></label>
                <input type="text" id="state" name="state" required>

                <label for="zipCode">Customer Zip Code: <span style="color:red;">Required</span></label>
                <input type="text" id="zipCode" name="zipCode" required>

                <label for="phoneNumber">Customer Phone Number: <span style="color:red;">Required</span></label>
                <input type="text" id="phoneNumber" name="phoneNumber" required>

                <div class="buttons">
                    <button type="submit">Update Customer</button>
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
