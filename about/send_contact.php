<?php
// Only process POST reqeusts.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form fields and remove whitespace.
    $firstname = strip_tags(trim($_POST["firstname"]));
    $firstname = str_replace(array("\r","\n"),array(" "," "),$firstname);
    $lastname = strip_tags(trim($_POST["lastname"]));
    $lastname = str_replace(array("\r","\n"),array(" "," "),$lastname);
    $email = filter_var(trim($_POST["emailaddress"]), FILTER_SANITIZE_EMAIL);
    $subject = trim($_POST["subject"]);

    // Check that data was sent to the mailer.
    if (empty($firstname) OR empty($lastname) OR !filter_var($email, FILTER_VALIDATE_EMAIL) OR empty($subject)) {
        // Set a 400 (bad request) response code and exit.
        echo "Oops! There was a problem with your submission. Please complete the form and try again.";
        // Redirect back to the About Us page
        header("refresh:5;url=AboutUs.html");
        exit;
    }

    // Set the recipient email address.
    $recipient = "information@paradisepetrescue.in";

    // Set the email subject.
    $email_subject = "New contact from $firstname $lastname";

    // Build the email content.
    $email_content = "First Name: $firstname\n";
    $email_content .= "Last Name: $lastname\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Subject:\n$subject\n";

    // Build the email headers.
    $email_headers = "From: $firstname $lastname <$email>";

    // Send the email.
    if (mail($recipient, $email_subject, $email_content, $email_headers)) {
        // Set a 200 (okay) response code.
        echo "Thank You! Your message has been sent.";
        // Redirect back to the About Us page
        header("refresh:5;url=AboutUs.html");
    } else {
        // Set a 500 (internal server error) response code.
        echo "Oops! Something went wrong and we couldn't send your message.";
        // Redirect back to the About Us page
        header("refresh:5;url=AboutUs.html");
    }

} else {
    // Not a POST request, set a 403 (forbidden) response code.
    echo "There was a problem with your submission, please try again.";
    // Redirect back to the About Us page
    header("refresh:5;url=AboutUs.html");
}
?>
