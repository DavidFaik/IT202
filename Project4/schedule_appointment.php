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

    if (!isset($_POST['validated'])) {
        $customerFirstName = trim($_POST['customerFirstName']);
        $customerLastName = trim($_POST['customerLastName']);
        $customerID = trim($_POST['customerID']);


        $sql = "SELECT CustomerID FROM Customers WHERE CustomerID = ? AND FirstName = ? AND LastName = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("iss", $customerID, $customerFirstName, $customerLastName);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {

            $stmt->close();
            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <title>Schedule Service Appointment</title>
                <link rel="stylesheet" href="style.css">
            </head>
            <body>
                <div class="nav-bar">
                    <a href="index.html">Logout</a>
                </div>
                <div class="form-container">
                    <h2>Schedule Service Appointment</h2>
                    <form method="POST" action="">
                        <input type="hidden" name="customerID" value="<?php echo htmlspecialchars($customerID); ?>">
                        <input type="hidden" name="validated" value="1">
                        <label for="dateOfService">Service Appointment Date: <span style="color:red;">Required</span></label>
                        <input type="date" id="dateOfService" name="dateOfService" required>

                        <label for="typeOfService">Service Type: <span style="color:red;">Required</span></label>
                        <input type="text" id="typeOfService" name="typeOfService" required>

                        <label for="costOfService">Cost: <span style="color:red;">Required</span></label>
                        <input type="number" step="0.01" id="costOfService" name="costOfService" required>

                        <div class="buttons">
                            <button type="submit">Schedule Appointment</button>
                            <button type="reset">Reset</button>
                        </div>
                    </form>
                </div>
            </body>
            </html>
            <?php
            exit();
        } else {

            $stmt->close();
            echo "<script>
                if (confirm('Customer does not exist. Do you want to re-enter data? Click Cancel to create a new customer.')) {
                    window.location.href = 'schedule_appointment.php';
                } else {
                    window.location.href = 'create_customer.php';
                }
            </script>";
            exit();
        }
    } else {
        $customerID = $_POST['customerID'];
        $dateOfService = trim($_POST['dateOfService']);
        $typeOfService = trim($_POST['typeOfService']);
        $costOfService = trim($_POST['costOfService']);

        $serviceID = rand(1000, 9999);

        $insertSQL = "INSERT INTO ServiceRecords (ServiceID, CustomerID, DateOfService, TypeOfService, Cost) VALUES (?, ?, ?, ?, ?)";
        $insertStmt = $con->prepare($insertSQL);
        $insertStmt->bind_param("iissd", $serviceID, $customerID, $dateOfService, $typeOfService, $costOfService);

        if ($insertStmt->execute()) {
            echo "<script>
                    alert('Service appointment scheduled successfully. Appointment ID: $serviceID');
                    if (confirm('Do you want to request supplies for this appointment?')) {
                        window.location.href = 'request_supplies.php?serviceID=$serviceID';
                    } else {
                        window.location.href = 'validate_login.php';
                    }
                  </script>";
        } else {
            echo "Error scheduling appointment: " . $insertStmt->error;
        }
        $insertStmt->close();
    }
} else {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Schedule Service Appointment</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="nav-bar">
            <a href="index.html">Logout</a>
        </div>
        <div class="form-container">
            <h2>Schedule Service Appointment</h2>
            <form method="POST" action="">
                <label for="customerFirstName">Customer First Name: <span style="color:red;">Required</span></label>
                <input type="text" id="customerFirstName" name="customerFirstName" required>

                <label for="customerLastName">Customer Last Name: <span style="color:red;">Required</span></label>
                <input type="text" id="customerLastName" name="customerLastName" required>

                <label for="customerID">Customer ID: <span style="color:red;">Required</span></label>
                <input type="text" id="customerID" name="customerID" required>

                <div class="buttons">
                    <button type="submit">Validate Customer</button>
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
