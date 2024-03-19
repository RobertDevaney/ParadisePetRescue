<?php
// Database configuration
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

// Sanitize input data
$firstName = $conn->real_escape_string($_POST['firstname']);
$lastName = $conn->real_escape_string($_POST['lastname']);
$email = $conn->real_escape_string($_POST['email']);
$phoneNumber = $conn->real_escape_string($_POST['phonenumber']);
$username = $conn->real_escape_string($_POST['username']);
$password = $conn->real_escape_string($_POST['password']); 
$hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password for security

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO Users (FirstName, LastName, Email, PhoneNumber, Username, Password) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $firstName, $lastName, $email, $phoneNumber, $username, $hashedPassword);

// Execute the statement
if ($stmt->execute()) {
    echo "New record created successfully. Redirecting to home page...";
    // Redirect to home page after 3 seconds
    header("Refresh:3; url=../index.html");
} else {
    echo "Error: " . $stmt->error;
     // Redirect to home page after 3 seconds
    header("Refresh:3; url=../index.html");
}

$stmt->close();
$conn->close();
?>
