<?php
session_start(); 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connect.php';

if (!isset($_SESSION['plumberID'])) {
    die("Unauthorized access. Please log in first.");
}

$plumberID = $_SESSION['plumberID'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['personalInfo'])) {
        $firstName = trim($_POST['firstName']);
        $lastName = trim($_POST['lastName']);
        $customerID = trim($_POST['customerID']);

        $sql = "SELECT CustomerID FROM Customers WHERE CustomerID = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $customerID);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "<script>alert('Customer already has an account'); window.history.back();</script>";
        } else {

            $insertSQL = "INSERT INTO Customers (CustomerID, FirstName, LastName, PlumberID) VALUES (?, ?, ?, ?)";
            $insertStmt = $con->prepare($insertSQL);
            $insertStmt->bind_param("issi", $customerID, $firstName, $lastName, $plumberID);
            if ($insertStmt->execute()) {
                echo "<script>
                        alert('Customer Account Created. You will be redirected to a form to enter the Personal information for the customer');
                        window.location.href = 'create_customer.php?customerID=$customerID';
                      </script>";
            } else {
                echo "Error creating customer: " . $insertStmt->error;
            }
            $insertStmt->close();
        }
        $stmt->close();
    } else {
        $customerID = $_POST['customerID'];
        $streetAddress = trim($_POST['streetAddress']);
        $city = trim($_POST['city']);
        $state = trim($_POST['state']);
        $zipCode = trim($_POST['zipCode']);
        $phoneNumber = trim($_POST['phoneNumber']);

        $insertInfoSQL = "INSERT INTO CustomerPersonalInfo (CustomerID, StreetAddress, City, State, ZipCode, PhoneNumber) VALUES (?, ?, ?, ?, ?, ?)";
        $insertInfoStmt = $con->prepare($insertInfoSQL);
        $insertInfoStmt->bind_param("isssss", $customerID, $streetAddress, $city, $state, $zipCode, $phoneNumber);
        if ($insertInfoStmt->execute()) {
            echo "<script>alert('Customer Personal Information Added'); window.location.href = 'validate_login.php';</script>";
        } else {
            echo "Error inserting customer personal info: " . $insertInfoStmt->error;
        }
        $insertInfoStmt->close();
    }
} else {
    if (isset($_GET['customerID'])) {
        $customerID = $_GET['customerID'];
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Enter Customer Personal Information</title>
            <link rel="stylesheet" href="style.css">
        </head>
        <body>
            <div class="nav-bar">
                <a href="index.html">Logout</a>
            </div>
            <div class="form-container">
                <h2>Enter Customer Personal Information</h2>
                <form method="POST" action="">
                    <input type="hidden" name="customerID" value="<?php echo htmlspecialchars($customerID); ?>">
                    <input type="hidden" name="personalInfo" value="1">

                    <label for="streetAddress">Street Address: <span style="color:red;">Required</span></label>
                    <input type="text" id="streetAddress" name="streetAddress" required>

                    <label for="city">City: <span style="color:red;">Required</span></label>
                    <input type="text" id="city" name="city" required>

                    <label for="state">State: <span style="color:red;">Required</span></label>
                    <input type="text" id="state" name="state" required>

                    <label for="zipCode">Zip Code: <span style="color:red;">Required</span></label>
                    <input type="text" id="zipCode" name="zipCode" required>

                    <label for="phoneNumber">Phone Number: <span style="color:red;">Required</span></label>
                    <input type="text" id="phoneNumber" name="phoneNumber" required>

                    <div class="buttons">
                        <button type="submit">Submit</button>
                        <button type="reset">Reset</button>
                    </div>
                </form>
            </div>
        </body>
        </html>
        <?php
    } else {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Create New Customer Account</title>
            <link rel="stylesheet" href="style.css">
        </head>
        <body>
            <div class="nav-bar">
                <a href="index">Logout</a>
            </div>
            <div class="form-container">
                <h2>Create New Customer Account</h2>
                <form method="POST" action="">
                    <label for="firstName">Customer First Name: <span style="color:red;">Required</span></label>
                    <input type="text" id="firstName" name="firstName" required>

                    <label for="lastName">Customer Last Name: <span style="color:red;">Required</span></label>
                    <input type="text" id="lastName" name="lastName" required>

                    <label for="customerID">Customer ID: <span style="color:red;">Required</span></label>
                    <input type="text" id="customerID" name="customerID" required>

                    <div class="buttons">
                        <button type="submit">Create Account</button>
                        <button type="reset">Reset</button>
                    </div>
                </form>
            </div>
        </body>
        </html>
        <?php
    }
}

$con->close();
?>
