<?php
require_once 'auth_check.php'; // Ensures admin is logged in, starts session
require_once '../config/db.php';

// Function to sanitize input data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    // htmlspecialchars is typically for output, but can be part of a defense-in-depth strategy for input
    // However, for database insertion, prepared statements are the primary defense against SQLi.
    // For general string data, trim and stripslashes are good starting points.
    return $data;
}

// Function to handle file uploads
function handle_project_image_upload($file_input_name, $current_image_url = null) {
    global $pdo; // Not ideal, better to pass $pdo if this were a class method

    $upload_dir = '../uploads/projects/';
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_file_size = 2 * 1024 * 1024; // 2MB

    if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES[$file_input_name];

        // Validate file type
        if (!in_array($file['type'], $allowed_types)) {
            $_SESSION['error_message'] = 'Invalid file type. Only JPG, PNG, and GIF are allowed.';
            return false; // Indicate failure but don't redirect yet, let the calling code handle it
        }

        // Validate file size
        if ($file['size'] > $max_file_size) {
            $_SESSION['error_message'] = 'File is too large. Maximum size is 2MB.';
            return false;
        }

        // Generate unique filename
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $unique_filename = uniqid('project_', true) . '.' . $file_extension;
        $upload_path = $upload_dir . $unique_filename;

        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
            // If a new image is uploaded and there was an old one (during edit), delete the old one
            if ($current_image_url && file_exists('../' . $current_image_url)) {
                unlink('../' . $current_image_url);
            }
            return 'uploads/projects/' . $unique_filename; // Return relative path for DB
        } else {
            $_SESSION['error_message'] = 'Failed to move uploaded file.';
            return false;
        }
    } elseif (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] !== UPLOAD_ERR_NO_FILE) {
        // An error occurred with the upload, other than no file being submitted
        $_SESSION['error_message'] = 'File upload error: ' . $_FILES[$file_input_name]['error'];
        return false;
    }

    // If no new file uploaded during an edit, keep the current image URL
    if ($current_image_url && $_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit') {
        return $current_image_url;
    }

    return null; // No file uploaded or error (error message already set if it was an actual error)
}


// Determine action: add, edit, delete
$action = $_REQUEST['action'] ?? null; // Use $_REQUEST to catch GET for delete, POST for add/edit

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($action === 'add' || $action === 'edit')) {
    // Common fields
    $title = sanitize_input($_POST['title']);
    $description = sanitize_input($_POST['description']);
    $outcome = sanitize_input($_POST['outcome'] ?? null); // Outcome is nullable
    $project_id = isset($_POST['project_id']) ? (int)$_POST['project_id'] : null;

    // Validate required fields
    if (empty($title) || empty($description)) {
        $_SESSION['error_message'] = 'Title and Description are required.';
        if ($action === 'edit' && $project_id) {
            header("Location: edit_project.php?id=$project_id");
        } else {
            header('Location: projects_admin.php');
        }
        exit;
    }

    $current_image_path = null;
    if ($action === 'edit' && $project_id) {
        // Fetch current image path to handle deletion if new one is uploaded or for keeping if no new one.
        $stmt = $pdo->prepare("SELECT image_url FROM projects WHERE id = :id");
        $stmt->bindParam(':id', $project_id, PDO::PARAM_INT);
        $stmt->execute();
        $project_data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($project_data) {
            $current_image_path = $project_data['image_url'];
        }
    }

    $image_url_for_db = handle_project_image_upload('image', $current_image_path);

    if ($image_url_for_db === false) { // Error occurred during upload, message already in session
        if ($action === 'edit' && $project_id) {
            header("Location: edit_project.php?id=$project_id");
        } else {
            header('Location: projects_admin.php');
        }
        exit;
    }

    // If adding and no image uploaded, it's an error (assuming image is required for new projects)
    if ($action === 'add' && $image_url_for_db === null) {
        $_SESSION['error_message'] = 'Project image is required for new projects.';
        header('Location: projects_admin.php');
        exit;
    }


    try {
        if ($action === 'add') {
            $sql = "INSERT INTO projects (title, description, outcome, image_url) VALUES (:title, :description, :outcome, :image_url)";
            $stmt = $pdo->prepare($sql);
        } elseif ($action === 'edit' && $project_id) {
            // If $image_url_for_db is null here, it means no new image was uploaded, so we should keep $current_image_path
            $final_image_url = $image_url_for_db ?? $current_image_path;

            $sql = "UPDATE projects SET title = :title, description = :description, outcome = :outcome, image_url = :image_url WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $project_id, PDO::PARAM_INT);
        } else {
            throw new Exception("Invalid action or missing project ID for edit.");
        }

        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':outcome', $outcome);
        $stmt->bindParam(':image_url', $final_image_url); // Use $final_image_url for edit, $image_url_for_db for add

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Project " . ($action === 'add' ? "added" : "updated") . " successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to " . ($action === 'add' ? "add" : "update") . " project.";
        }
    } catch (PDOException $e) {
        error_log("Database error in process_project.php: " . $e->getMessage());
        $_SESSION['error_message'] = "Database error: Could not process project. " . $e->getMessage();
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
    }

} elseif ($action === 'delete' && isset($_GET['id'])) {
    $project_id = (int)$_GET['id'];
    try {
        // First, get the image URL to delete the file
        $stmt_select = $pdo->prepare("SELECT image_url FROM projects WHERE id = :id");
        $stmt_select->bindParam(':id', $project_id, PDO::PARAM_INT);
        $stmt_select->execute();
        $project = $stmt_select->fetch(PDO::FETCH_ASSOC);

        if ($project) {
            $stmt_delete = $pdo->prepare("DELETE FROM projects WHERE id = :id");
            $stmt_delete->bindParam(':id', $project_id, PDO::PARAM_INT);
            if ($stmt_delete->execute()) {
                // Delete the image file
                if (!empty($project['image_url']) && file_exists('../' . $project['image_url'])) {
                    unlink('../' . $project['image_url']);
                }
                $_SESSION['success_message'] = "Project deleted successfully.";
            } else {
                $_SESSION['error_message'] = "Failed to delete project.";
            }
        } else {
            $_SESSION['error_message'] = "Project not found.";
        }
    } catch (PDOException $e) {
        error_log("Database error deleting project: " . $e->getMessage());
        $_SESSION['error_message'] = "Database error: Could not delete project.";
    }
} else {
    $_SESSION['error_message'] = "Invalid action.";
}

header('Location: projects_admin.php');
exit;
?>
