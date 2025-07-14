 <?php

$serverName = "localhost";
$databaseUsername = "root";
$databasePassword = ""; // empty, if no password
$databaseName = "rosellofarms"; // put your actual DB name here

// Create connection
$conn = new mysqli($serverName, $databaseUsername, $databasePassword, $databaseName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
