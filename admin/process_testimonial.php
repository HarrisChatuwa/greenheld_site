<?php
require_once 'auth_check.php'; // Ensures admin is logged in, starts session
require_once '../config/db.php';

// (Using the sanitize_input function from process_project.php - ideally, this would be in a shared utility file)
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    return $data;
}

// Function to handle testimonial photo uploads (similar to project image upload)
function handle_testimonial_photo_upload($file_input_name, $current_photo_url = null) {
    $upload_dir = '../uploads/testimonials/';
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_file_size = 1 * 1024 * 1024; // 1MB for client photos

    if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES[$file_input_name];

        if (!in_array($file['type'], $allowed_types)) {
            $_SESSION['error_message'] = 'Invalid file type for photo. Only JPG, PNG, and GIF are allowed.';
            return false;
        }
        if ($file['size'] > $max_file_size) {
            $_SESSION['error_message'] = 'Photo file is too large. Maximum size is 1MB.';
            return false;
        }

        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $unique_filename = uniqid('testimonial_', true) . '.' . $file_extension;
        $upload_path = $upload_dir . $unique_filename;

        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
            if ($current_photo_url && file_exists('../' . $current_photo_url)) {
                unlink('../' . $current_photo_url);
            }
            return 'uploads/testimonials/' . $unique_filename;
        } else {
            $_SESSION['error_message'] = 'Failed to move uploaded photo.';
            return false;
        }
    } elseif (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] !== UPLOAD_ERR_NO_FILE) {
        $_SESSION['error_message'] = 'Photo upload error: ' . $_FILES[$file_input_name]['error'];
        return false;
    }

    // If editing and no new file, keep current. Or if no file uploaded for 'add' (it's optional).
    if (($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit') ||
        ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add' && (!isset($_FILES[$file_input_name]) || $_FILES[$file_input_name]['error'] === UPLOAD_ERR_NO_FILE)) ) {
        return $current_photo_url; // Keep old if editing, or null if adding and no photo
    }

    return null; // No new file was intended for upload or error occurred
}


$action = $_REQUEST['action'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($action === 'add' || $action === 'edit')) {
    $client_name = sanitize_input($_POST['client_name']);
    $client_title_company = sanitize_input($_POST['client_title_company'] ?? null);
    $quote = sanitize_input($_POST['quote']);
    $testimonial_id = isset($_POST['testimonial_id']) ? (int)$_POST['testimonial_id'] : null;

    if (empty($client_name) || empty($quote)) {
        $_SESSION['error_message'] = 'Client Name and Quote are required.';
        if ($action === 'edit' && $testimonial_id) {
            header("Location: edit_testimonial.php?id=$testimonial_id");
        } else {
            header('Location: testimonials_admin.php');
        }
        exit;
    }

    $current_photo_path = null;
    if ($action === 'edit' && $testimonial_id) {
        $stmt = $pdo->prepare("SELECT client_photo_url FROM testimonials WHERE id = :id");
        $stmt->bindParam(':id', $testimonial_id, PDO::PARAM_INT);
        $stmt->execute();
        $testimonial_data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($testimonial_data) {
            $current_photo_path = $testimonial_data['client_photo_url'];
        }
    }

    $photo_url_for_db = handle_testimonial_photo_upload('client_photo', $current_photo_path);

    if ($photo_url_for_db === false) { // Error message already set in session by handler
        if ($action === 'edit' && $testimonial_id) {
            header("Location: edit_testimonial.php?id=$testimonial_id");
        } else {
            header('Location: testimonials_admin.php');
        }
        exit;
    }

    // For 'add', if $photo_url_for_db is null, it means no photo was uploaded (which is fine as it's optional)
    // For 'edit', if $photo_url_for_db is null and $current_photo_path exists, it means keep the current one.
    // If $photo_url_for_db has a value, it's the new path.

    $final_photo_url = $photo_url_for_db; // This will be the new path, or null if no new one and none existing/adding
    if ($action === 'edit' && $photo_url_for_db === null && $current_photo_path !== null) {
        $final_photo_url = $current_photo_path; // Keep existing if no new one uploaded during edit
    }


    try {
        if ($action === 'add') {
            $sql = "INSERT INTO testimonials (client_name, client_title_company, quote, client_photo_url) VALUES (:client_name, :client_title_company, :quote, :client_photo_url)";
            $stmt = $pdo->prepare($sql);
        } elseif ($action === 'edit' && $testimonial_id) {
            $sql = "UPDATE testimonials SET client_name = :client_name, client_title_company = :client_title_company, quote = :quote, client_photo_url = :client_photo_url WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $testimonial_id, PDO::PARAM_INT);
        } else {
            throw new Exception("Invalid action or missing testimonial ID for edit.");
        }

        $stmt->bindParam(':client_name', $client_name);
        $stmt->bindParam(':client_title_company', $client_title_company);
        $stmt->bindParam(':quote', $quote);
        $stmt->bindParam(':client_photo_url', $final_photo_url); // Use $final_photo_url

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Testimonial " . ($action === 'add' ? "added" : "updated") . " successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to " . ($action === 'add' ? "add" : "update") . " testimonial.";
        }
    } catch (PDOException $e) {
        error_log("Database error in process_testimonial.php: " . $e->getMessage());
        $_SESSION['error_message'] = "Database error: Could not process testimonial. " . $e->getMessage();
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
    }

} elseif ($action === 'delete' && isset($_GET['id'])) {
    $testimonial_id = (int)$_GET['id'];
    try {
        $stmt_select = $pdo->prepare("SELECT client_photo_url FROM testimonials WHERE id = :id");
        $stmt_select->bindParam(':id', $testimonial_id, PDO::PARAM_INT);
        $stmt_select->execute();
        $testimonial = $stmt_select->fetch(PDO::FETCH_ASSOC);

        if ($testimonial) {
            $stmt_delete = $pdo->prepare("DELETE FROM testimonials WHERE id = :id");
            $stmt_delete->bindParam(':id', $testimonial_id, PDO::PARAM_INT);
            if ($stmt_delete->execute()) {
                if (!empty($testimonial['client_photo_url']) && file_exists('../' . $testimonial['client_photo_url'])) {
                    unlink('../' . $testimonial['client_photo_url']);
                }
                $_SESSION['success_message'] = "Testimonial deleted successfully.";
            } else {
                $_SESSION['error_message'] = "Failed to delete testimonial.";
            }
        } else {
            $_SESSION['error_message'] = "Testimonial not found.";
        }
    } catch (PDOException $e) {
        error_log("Database error deleting testimonial: " . $e->getMessage());
        $_SESSION['error_message'] = "Database error: Could not delete testimonial.";
    }
} else {
    $_SESSION['error_message'] = "Invalid action for testimonials.";
}

header('Location: testimonials_admin.php');
exit;
?>
