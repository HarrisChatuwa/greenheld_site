<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set content type to JSON
header('Content-Type: application/json');

// Include the database connection
// The path is relative to this file's location
require_once __DIR__ . '/../../config/db.php';

// Define a response array
$response = ['success' => false, 'message' => 'An unexpected error occurred.'];

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST data
    $raw_data = file_get_contents('php://input');
    $data = json_decode($raw_data, true);

    // --- Validation ---
    if (empty($data['name'])) {
        $response['message'] = 'Please enter your full name.';
    } elseif (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Please enter a valid email address.';
    } elseif (empty($data['subject'])) {
        $response['message'] = 'Please enter a subject.';
    } elseif (empty($data['message'])) {
        $response['message'] = 'Please enter your message.';
    } else {
        // --- Sanitize data ---
        $name = htmlspecialchars(strip_tags($data['name']));
        $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        $subject = htmlspecialchars(strip_tags($data['subject']));
        $message = htmlspecialchars(strip_tags($data['message']));

        // --- Database Insertion ---
        try {
            $sql = "INSERT INTO contact_submissions (name, email, subject, message) VALUES (:name, :email, :subject, :message)";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':subject', $subject);
            $stmt->bindParam(':message', $message);

            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Thank you! Your message has been sent successfully.';

                // --- Email Notification ---
                // **IMPORTANT**: Replace with your actual email address and credentials.
                // Using a library like PHPMailer is strongly recommended for reliability.
                $to = 'your-email@example.com'; // <-- REPLACE THIS
                $email_subject = "New Contact Form Submission: " . $subject;
                $email_body = "You have received a new message from your website contact form.\n\n";
                $email_body .= "Here are the details:\n";
                $email_body .= "Name: " . $name . "\n";
                $email_body .= "Email: " . $email . "\n";
                $email_body .= "Subject: " . $subject . "\n";
                $email_body .= "Message:\n" . $message . "\n";

                // **IMPORTANT**: Configure your server or use an SMTP service for mail to work.
                // The 'From' header should be a valid email address on your server.
                $headers = 'From: no-reply@yourdomain.com' . "\r\n" . // <-- REPLACE THIS
                           'Reply-To: ' . $email . "\r\n" .
                           'X-Mailer: PHP/' . phpversion();

                // The mail() function's success is not guaranteed.
                // It returns true if the email was accepted for delivery, not if it was delivered.
                if (!mail($to, $email_subject, $email_body, $headers)) {
                    // Log the error, but don't tell the user the email failed.
                    // The primary goal (saving to DB) was successful.
                    error_log("Contact form email failed to send. Submission ID: " . $pdo->lastInsertId());
                }

            } else {
                $response['message'] = 'Error: Could not save your message to the database.';
                error_log("Failed to insert contact submission into database.");
            }
        } catch (PDOException $e) {
            $response['message'] = 'Database error: Could not process your request.';
            // Log the detailed error for the admin, not for the user.
            error_log("Database Error: " . $e->getMessage());
        }
    }
} else {
    // Not a POST request
    $response['message'] = 'Invalid request method.';
}

// Return the JSON response
echo json_encode($response);
?>