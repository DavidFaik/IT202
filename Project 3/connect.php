<?php
$servername = "sql1.njit.edu";
$username = "djf47";
$password = "Davijoe_2005";
$dbname = "djf47";


$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
