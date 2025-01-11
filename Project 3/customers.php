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

$sql = "SELECT * FROM Customers";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<h1>Customer Records</h1>";
    echo "<table border='1'>
            <tr>
                <th>Customer ID</th>
                <th>First Name</th>
                <th>Last Name</th>
            </tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>" . $row["CustomerID"] . "</td>
                <td>" . $row["FirstName"] . "</td>
                <td>" . $row["LastName"] . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No customer records found.";
}

echo "<a href='index.php' class='home-button'>Home</a>";
echo "</div>";  // End of container

mysqli_close($conn);  

echo "</body>";
echo "</html>";
?>
