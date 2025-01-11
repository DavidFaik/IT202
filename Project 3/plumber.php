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

$sql = "SELECT * FROM Plumbers";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<h1>Plumber Records</h1>";
    echo "<table border='1'>
            <tr>
                <th>Plumber ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Phone Number</th>
                <th>Email</th>
            </tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>" . $row["PlumberID"] . "</td>
                <td>" . $row["FirstName"] . "</td>
                <td>" . $row["LastName"] . "</td>
                <td>" . $row["PhoneNumber"] . "</td>
                <td>" . $row["Email"] . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No plumber records found.</p>";
}

echo "<a href='index.php' class='home-button'>Home</a>";
echo "</div>";  

mysqli_close($conn);  

echo "</body>";
echo "</html>";
?>
