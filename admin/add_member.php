<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $role = trim($_POST['role']);
    $bio = trim($_POST['bio']);

    // Image upload handling
    $photoUrl = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $targetDir = "../uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        $fileName = basename($_FILES["photo"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
        if (in_array($fileType, $allowTypes)) {
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath)) {
                $photoUrl = "uploads/" . $fileName;
            } else {
                error_log("Failed to move uploaded file for team member. Check permissions for 'uploads' directory.");
            }
        }
    } elseif (isset($_FILES['photo']) && $_FILES['photo']['error'] != UPLOAD_ERR_NO_FILE) {
        error_log("File upload error for team member photo: " . $_FILES['photo']['error']);
    }

    $sql = "INSERT INTO team_members (name, role, bio, photo_url) VALUES (:name, :role, :bio, :photo_url)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'name' => $name,
        'role' => $role,
        'bio' => $bio,
        'photo_url' => $photoUrl
    ]);
    header('Location: team.php');
    exit;
}