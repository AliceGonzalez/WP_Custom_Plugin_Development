<!--CODE BELOW SENTS FORM INFORMATION DIRECTLY TO OUR EMAIL AND GIVES THE USER A SUCCES MESSAGE -->
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
    // Sanitize form fields
    $first_name = sanitize_text_field($_POST['cfe-first-name']);
    $last_name = sanitize_text_field($_POST['cfe-last-name']);
    $email = sanitize_email($_POST['cfe-email']);
    $phone = sanitize_text_field($_POST['cfe-phone']);
    $message = sanitize_textarea_field($_POST['cfe-message']);

    // Validate the email
    if (!is_email($email)) {
        echo '<p class="cfe-error-message">Please enter a valid email address.</p>';
        return;
    }

    // Send the email if required fields are filled
    if (!empty($first_name) && !empty($last_name) && !empty($email) && !empty($message)) {
        $to = 'tekcodes@gmail.com';
        $subject = 'New Contact Form Submission';
        $body = "You have received a new message from $first_name $last_name.<br><br>";
        $body .= "<strong>Email:</strong> $email<br>";
        $body .= "<strong>Phone:</strong> $phone<br>";
        $body .= "<strong>Message:</strong><br>$message<br>";
        $headers = array(
            'Content-Type: text/html; charset=UTF-8'
        );

        // Send the email
        if (wp_mail($to, $subject, $body, $headers)) {
            echo '<p class="cfe-success-message">Thank you for reaching out. We will get back to you soon!</p>';
        } else {
            echo '<p class="cfe-error-message">There was an issue sending your message. Please try again later.</p>';
        }
    } else {
        echo '<p class="cfe-error-message">All required fields must be filled out. Please try again.</p>';
    }
}
?>

<!-- BUILD FORM WITH HTML -->

<form id="cfe-contact-form" method="post" action="">
    <!-- Ask for first name -->
    <div class="cfe-form-group">
        <label for="cfe-first-name">First Name: </label>
        <input type="text" id="cfe-first-name" name="cfe-first-name" required>
    </div>

    <!-- Ask for last name -->
    <div class="cfe-form-group">
        <label for="cfe-last-name">Last Name: </label>
        <input type="text" id="cfe-last-name" name="cfe-last-name" required>
    </div>

    <!-- Ask for email -->
    <div class="cfe-form-group">
        <label for="cfe-email">Email: </label>
        <input type="email" id="cfe-email" name="cfe-email" required>
    </div>

    <!-- Ask for phone -->
    <div class="cfe-form-group">
        <label for="cfe-phone">Phone Number: </label>
        <input type="tel" id="cfe-phone" name="cfe-phone">
    </div>

    <!-- Ask for message -->
    <div class="cfe-message">
        <label for="cfe-message">Message: </label>
        <textarea id="cfe-message" name="cfe-message" rows="5" required></textarea>
    </div>

    <!-- Submit message form -->
    <button type="submit" class="cfe-submit-button">Send Message</button>
</form>