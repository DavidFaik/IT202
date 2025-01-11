<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "sql1.njit.edu";
$username = "djf47";
$password = "Davijoe_2005";
$dbname = "djf47";

$con = new mysqli($servername, $username, $password, $dbname);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
?>
