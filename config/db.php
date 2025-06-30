<?php
// Database Configuration
define('DB_HOST', 'localhost'); // Replace with your database host (e.g., '127.0.0.1')
define('DB_NAME', 'greemheld_db'); // Replace with your database name
define('DB_USER', 'root');       // Replace with your database username
define('DB_PASS', '');           // Replace with your database password
define('DB_CHARSET', 'utf8mb4');

// Data Source Name (DSN)
$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

// PDO Options
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Turn on errors in the form of exceptions
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Make the default fetch be an associative array
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Turn off emulation mode for real prepared statements
];

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    // In a production environment, you would log this error and show a generic message.
    // For development, it's okay to show the error, but be cautious.
    error_log("Database Connection Error: " . $e->getMessage()); // Log error to server error log
    // For a user-facing site, you might show a friendlier error or redirect.
    // For the admin panel during setup, dying might be acceptable to highlight the issue.
    die("Database connection failed. Please check your configuration and ensure the database server is running. Details: " . $e->getMessage());
}

// Security Note for Production:
// In a real production environment, it's highly recommended to:
// 1. Store database credentials outside of the web root (e.g., in environment variables or a config file in a non-accessible directory).
// 2. Restrict file permissions for this config file.
// 3. Implement more robust error handling that doesn't expose detailed error messages to the end-user.
?>
