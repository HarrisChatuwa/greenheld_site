<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the admin_user_id session variable is set
if (!isset($_SESSION['admin_user_id'])) {
    // If not set, the user is not logged in.
    // Store the intended destination to redirect after login (optional)
    // $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];

    // Redirect to the login page
    header('Location: login.php');
    exit; // Important to prevent further script execution
}

// Optional: You might want to add activity checks or role checks here in a more complex system.
// For example, update a "last activity" timestamp or verify user roles/permissions.

// The user is authenticated if the script reaches this point without exiting.
?>
