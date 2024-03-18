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
$username = $conn->real_escape_string($_POST['username']);
$password = $conn->real_escape_string($_POST['password']);
$userIP = $_SERVER['REMOTE_ADDR']; // Get the user's IP address

// Prepare and execute the query to authenticate the user
$stmt = $conn->prepare("SELECT Password FROM Users WHERE Username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    // Use password_verify to check the entered password against the stored hash
    if (password_verify($password, $user['Password'])) {
        // Authentication successful

        // Insert login record with IP address into the database
        $insertStmt = $conn->prepare("INSERT INTO LoginRecords (Username, LoginDate, LoginTime, IPAddress) VALUES (?, CURDATE(), CURTIME(), ?)");
        $insertStmt->bind_param("ss", $username, $userIP);
        $insertStmt->execute();
        $insertStmt->close();

        echo "Login successful. Redirecting to home page...";
        header("Refresh:3; url=../index.html");
    } else {
        // Authentication failed
        echo "Invalid username or password.";
    }
} else {
    // User not found
    echo "Invalid username or password.";
}

$stmt->close();
$conn->close();
?>
