<?php
session_start(); 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connect.php';

if (!isset($_SESSION['plumberID'])) {
    die("Unauthorized access. Please log in first.");
}

$serviceID = isset($_GET['serviceID']) ? $_GET['serviceID'] : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $serviceID = trim($_POST['serviceID']);
    $suppliesNeeded = trim($_POST['suppliesNeeded']);
    $suppliesOrdered = trim($_POST['suppliesOrdered']);
    $suppliesReceivedDate = trim($_POST['suppliesReceivedDate']);

    $sql = "SELECT ServiceID FROM ServiceRecords WHERE ServiceID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $serviceID);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>
                if (confirm('You are about to REQUEST Supplies for your customer. Are you sure you want to do so?')) {
                    window.location.href = 'request_supplies.php?confirm=yes&serviceID=$serviceID&suppliesNeeded=" . urlencode($suppliesNeeded) . "&suppliesOrdered=" . urlencode($suppliesOrdered) . "&suppliesReceivedDate=$suppliesReceivedDate';
                } else {
                    window.location.href = 'request_supplies.php';
                }
              </script>";
    } else {
        echo "<script>alert('CUSTOMER DATA INFORMATION CANNOT BE FOUND. RECHECK DATA ENTERED OTHERWISE YOU NEED TO MAKE SURE THE CUSTOMER HAS A SERVICE APPOINTMENT'); window.history.back();</script>";
    }
    $stmt->close();
} elseif (isset($_GET['confirm']) && $_GET['confirm'] == 'yes') {
    $serviceID = $_GET['serviceID'];
    $suppliesNeeded = $_GET['suppliesNeeded'];
    $suppliesOrdered = $_GET['suppliesOrdered'];
    $suppliesReceivedDate = $_GET['suppliesReceivedDate'];

    $insertSQL = "INSERT INTO SuppliesRecords (ServiceID, SuppliesNeeded, SuppliesOrdered, SuppliesReceivedDate)
                  VALUES (?, ?, ?, ?)
                  ON DUPLICATE KEY UPDATE SuppliesNeeded = ?, SuppliesOrdered = ?, SuppliesReceivedDate = ?";
    $insertStmt = $con->prepare($insertSQL);
    $insertStmt->bind_param("issssss", $serviceID, $suppliesNeeded, $suppliesOrdered, $suppliesReceivedDate, $suppliesNeeded, $suppliesOrdered, $suppliesReceivedDate);
    if ($insertStmt->execute()) {
        echo "<script>alert('Supplies Added'); window.location.href = 'validate_login.php';</script>";
    } else {
        echo "Error updating supplies: " . $insertStmt->error;
    }
    $insertStmt->close();
} else {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Request Supplies</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="nav-bar">
            <a href="index">Logout</a>
        </div>
        <div class="form-container">
            <h2>Request Supplies</h2>
            <form method="POST" action="">
                <label for="serviceID">Service Appointment ID: <span style="color:red;">Required</span></label>
                <input type="text" id="serviceID" name="serviceID" value="<?php echo htmlspecialchars($serviceID); ?>" required>

                <label for="suppliesNeeded">Supplies Needed: <span style="color:red;">Required</span></label>
                <input type="text" id="suppliesNeeded" name="suppliesNeeded" required>

                <label for="suppliesOrdered">Supplies Ordered: <span style="color:red;">Required</span></label>
                <input type="text" id="suppliesOrdered" name="suppliesOrdered" required>

                <label for="suppliesReceivedDate">Date Supplies Received:</label>
                <input type="date" id="suppliesReceivedDate" name="suppliesReceivedDate">

                <div class="buttons">
                    <button type="submit">Request Supplies</button>
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
