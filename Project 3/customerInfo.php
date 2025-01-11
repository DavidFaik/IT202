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


$sql = "SELECT * FROM CustomerPersonalInfo";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<h1>Customer Personal Information</h1>";
    echo "<table border='1'>
            <tr>
                <th>Customer ID</th>
                <th>Street Address</th>
                <th>City</th>
                <th>State</th>
                <th>Zip Code</th>
                <th>Phone Number</th>
            </tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>" . $row["CustomerID"] . "</td>
                <td>" . $row["StreetAddress"] . "</td>
                <td>" . $row["City"] . "</td>
                <td>" . $row["State"] . "</td>
                <td>" . $row["ZipCode"] . "</td>
                <td>" . $row["PhoneNumber"] . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No personal information records found.";
}

echo "<a href='index.php' class='home-button'>Home</a>";
echo "</div>";  // End of container

mysqli_close($conn);  

echo "</body>";
echo "</html>";
?>

