<?php
// database.php

$host = "localhost";
$dbUsername = "u104394458_paradisepet";
$dbPassword = "Petparadise2";
$dbName = "u104394458_paradisepet";

// Create connection
$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
