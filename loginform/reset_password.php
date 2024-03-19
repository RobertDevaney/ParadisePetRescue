<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Error reporting for development (turn off for production)
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

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
    
    // Sanitize POST data
    $token = $conn->real_escape_string($_POST['token']);
    $newPassword = $conn->real_escape_string($_POST['newPassword']);
    $confirmNewPassword = $conn->real_escape_string($_POST['confirmNewPassword']);

    // Check if passwords match
    if ($newPassword !== $confirmNewPassword) {
        echo "Passwords do not match.";
        exit;
    }

    // Check token validity (exists and not expired)
    $stmt = $conn->prepare("SELECT Email FROM Users WHERE PasswordResetToken = ? AND TokenExpiration > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $email = $user['Email']; // Correctly retrieve email from user record
        
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update the user's password and clear the reset token and expiration
        $updateStmt = $conn->prepare("UPDATE Users SET Password = ?, PasswordResetToken = NULL, TokenExpiration = NULL WHERE Email = ?");
        $updateStmt->bind_param("ss", $hashedPassword, $email);
        if ($updateStmt->execute()) {
            echo "<p>Your password has been updated successfully. Redirecting to the login page...</p>";
        } else {
            echo "Error updating password.";
        }
        $updateStmt->close();
    } else {
        echo "Invalid or expired token.";
    }
    // Redirect with JavaScript to ensure that the message can be seen before redirecting
        echo "<script>setTimeout(function(){ window.location.href = 'login.html'; }, 3000);</script>";
    $stmt->close();
    $conn->close();
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
    <link rel="stylesheet" href="style.css">
    <script>
    function checkPasswordsMatch() {
        var newPassword = document.getElementById('newPassword').value;
        var confirmNewPassword = document.getElementById('confirmNewPassword').value;
        if (newPassword !== confirmNewPassword) {
            alert('Passwords do not match. Please try again.');
            return false;
        }
        return true;
    }
    </script>
</head>
<body>
    <div class="login-screen">
        <form action="reset_password.php" method="post" class="login-form" onsubmit="return checkPasswordsMatch()">
            <h1 class="login-title">Reset Your Password</h1>
            <div class="input-box">
                <i class='bx bxs-lock-alt'></i>
                <input type="password" id="newPassword" name="newPassword" placeholder="New Password" required>
            </div>
            <div class="input-box">
                <i class='bx bxs-lock-alt'></i>
                <input type="password" id="confirmNewPassword" name="confirmNewPassword" placeholder="Confirm New Password" required>
            </div>
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
            <button type="submit" class="login-btn">Reset Password</button>
        </form>
    </div>
</body>
</html>
<?php
}
?>
