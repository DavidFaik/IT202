<?php
session_start(); 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['plumberID'])) {
    die("Unauthorized access. Please log in first.");
}

$plumberID = $_SESSION['plumberID'];

include 'db_connect.php';

$sql = "
    SELECT 
        Plumbers.FirstName AS PlumberFirstName, 
        Plumbers.LastName AS PlumberLastName,
        Customers.FirstName AS CustomerFirstName, 
        Customers.LastName AS CustomerLastName,
        CustomerPersonalInfo.StreetAddress, 
        CustomerPersonalInfo.City, 
        CustomerPersonalInfo.State,
        CustomerPersonalInfo.ZipCode, 
        ServiceRecords.DateOfService, 
        ServiceRecords.TypeOfService, 
        ServiceRecords.Cost,
        SuppliesRecords.SuppliesNeeded, 
        SuppliesRecords.SuppliesReceivedDate
    FROM Plumbers
    JOIN Customers ON Plumbers.PlumberID = Customers.PlumberID
    JOIN CustomerPersonalInfo ON Customers.CustomerID = CustomerPersonalInfo.CustomerID
    JOIN ServiceRecords ON Customers.CustomerID = ServiceRecords.CustomerID
    LEFT JOIN SuppliesRecords ON Customers.CustomerID = SuppliesRecords.CustomerID
    WHERE Plumbers.PlumberID = ?
";

$stmt = $con->prepare($sql);

if (!$stmt) {
    die("SQL prepare failed: " . $con->error);
}

$stmt->bind_param("i", $plumberID);

if (!$stmt->execute()) {
    die("SQL execute failed: " . $stmt->error);
}

$stmt->store_result();

if ($stmt->num_rows == 0) {
    echo "<h1>No records found for Plumber ID: $plumberID</h1>";
} else {
    $stmt->bind_result(
        $plumberFirstName, $plumberLastName, $customerFirstName, $customerLastName,
        $streetAddress, $city, $state, $zipCode, $dateOfService, $typeOfService, $costOfService,
        $suppliesNeeded, $suppliesReceivedDate
    );

    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Plumber and Customer Records</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="nav-bar">
            <a href="index">Logout</a>
        </div>
        <div class="content-container">
            <h1>Plumber and Customer Records</h1>
            <table>
                <tr>
                    <th>Plumber Name</th>
                    <th>Customer Name</th>
                    <th>Address</th>
                    <th>Service Date</th>
                    <th>Service Type</th>
                    <th>Cost</th>
                    <th>Supplies Needed</th>
                    <th>Supplies Received Date</th>
                </tr>
                <?php
                while ($stmt->fetch()) {
                    echo "<tr>
                            <td>{$plumberFirstName} {$plumberLastName}</td>
                            <td>{$customerFirstName} {$customerLastName}</td>
                            <td>{$streetAddress}, {$city}, {$state}, {$zipCode}</td>
                            <td>{$dateOfService}</td>
                            <td>{$typeOfService}</td>
                            <td>{$costOfService}</td>
                            <td>{$suppliesNeeded}</td>
                            <td>{$suppliesReceivedDate}</td>
                          </tr>";
                }
                ?>
            </table>
        </div>
    </body>
    </html>

    <?php
}

$stmt->close();
$con->close();
?>
