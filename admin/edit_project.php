<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require_once '../config/db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: projects.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $outcome = trim($_POST['outcome']);

    $stmt = $pdo->prepare('SELECT image_url FROM projects WHERE id = ?');
    $stmt->execute([$id]);
    $project = $stmt->fetch();
    $imageUrl = $project['image_url'];

    // Image upload handling
    if (isset($_FILES['image']) && $_FILES['image']['error'] != UPLOAD_ERR_NO_FILE) {
        if ($_FILES['image']['error'] === UPLOAD_ERR_INI_SIZE) {
            $_SESSION['error_message'] = 'Error: The uploaded file is too large. Please choose a smaller file.';
            header('Location: projects.php');
            exit;
        }

        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error_message'] = 'An unexpected error occurred during file upload. Please try again.';
            header('Location: projects.php');
            exit;
        }

        $targetDir = "../uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        $fileName = basename($_FILES["image"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
        if (in_array($fileType, $allowTypes)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
                $imageUrl = "uploads/" . $fileName;
            } else {
                $_SESSION['error_message'] = 'Error: Could not save the uploaded file. Please check server permissions.';
                header('Location: projects.php');
                exit;
            }
        } else {
            $_SESSION['error_message'] = 'Error: Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.';
            header('Location: projects.php');
            exit;
        }
    }

    $sql = "UPDATE projects SET title = :title, description = :description, image_url = :image_url, outcome = :outcome WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'title' => $title,
        'description' => $description,
        'image_url' => $imageUrl,
        'outcome' => $outcome,
        'id' => $id
    ]);

    $_SESSION['success_message'] = 'Project updated successfully!';
    header('Location: projects.php');
    exit;
}
