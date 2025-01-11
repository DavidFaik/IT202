<?php
include 'connect.php';  

echo "<!DOCTYPE html>";
echo "<html lang='en'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Plumber Records</title>";
echo "<link rel='stylesheet' href='project3.css'>";  
echo "</head>";
echo "<body>";

echo "<div class='container'>";


$sql = "SELECT * FROM SuppliesRecords";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<h1>Supplies Records</h1>";
    echo "<table border='1'>
            <tr>
                <th>Customer ID</th>
                <th>Supplies Needed</th>
                <th>Supplies Status</th>
                <th>Supplies Received Date</th>
            </tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>" . $row["CustomerID"] . "</td>
                <td>" . $row["SuppliesNeeded"] . "</td>
                <td>" . $row["SuppliesStatus"] . "</td>
                <td>" . $row["SuppliesReceivedDate"] . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No supplies records found.";
}


echo "<a href='index.php' class='home-button'>Home</a>";
echo "</div>";  

mysqli_close($conn);  

echo "</body>";
echo "</html>";
?>
