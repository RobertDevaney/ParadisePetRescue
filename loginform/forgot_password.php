<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
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

    // Sanitize and retrieve input
    $email = $conn->real_escape_string($_POST['email']);

    // Check if user exists with the given email
    $stmt = $conn->prepare("SELECT * FROM Users WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        // User found
        $user = $result->fetch_assoc();
        $token = bin2hex(random_bytes(16)); // Generate a token
        
        $expires = new DateTime("NOW");
        $expires->add(new DateInterval("PT01H")); // Token is valid for 1 hour
        
        // Update the user with the reset token and its expiration
        $updateStmt = $conn->prepare("UPDATE Users SET PasswordResetToken=?, TokenExpiration=? WHERE Email=?");
        $updateStmt->bind_param("sss", $token, $expires->format('Y-m-d H:i:s'), $email);
        if ($updateStmt->execute()) {
            // Prepare the email message
            $resetLink = "https://paradisepetrescue.in/loginform/reset_password.php?token=" . $token;
            $message = "We received a password reset request. The link to reset your password is below. ";
            $message .= "If you did not make this request, you can ignore this email.\n\n";
            $message .= "Here is your password reset link: " . $resetLink;
            $headers = 'From: noreply@paradisepetrescue.in' . "\r\n";

            // Send the email
            if (mail($email, "Password Reset", $message, $headers)) {
                echo "A password reset link has been sent to your email address.";
            } else {
                echo "Unable to send email. Please contact support.";
            }
        } else {
            echo "There was an error processing your request.";
        }
        $updateStmt->close();
    } else {
        echo "No account found with that email address.";
    }
    // Redirect to home page after 3 seconds
    header("Refresh:3; url=../index.html");
    $stmt->close();
    $conn->close();
} else {
    // If not a POST request, perhaps handle differently or show an error.
    echo "This page requires a POST method.";
}
?>
