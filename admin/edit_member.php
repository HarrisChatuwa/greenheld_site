<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require_once '../config/db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: team.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $role = trim($_POST['role']);
    $bio = trim($_POST['bio']);

    $stmt = $pdo->prepare('SELECT photo_url FROM team_members WHERE id = ?');
    $stmt->execute([$id]);
    $member = $stmt->fetch();
    $photoUrl = $member['photo_url'];

    // Image upload handling
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $targetDir = "../public/uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        $fileName = basename($_FILES["photo"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
        if (in_array($fileType, $allowTypes)) {
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath)) {
                $photoUrl = "public/uploads/" . $fileName;
            }
        }
    }

    $sql = "UPDATE team_members SET name = :name, role = :role, bio = :bio, photo_url = :photo_url WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'name' => $name,
        'role' => $role,
        'bio' => $bio,
        'photo_url' => $photoUrl,
        'id' => $id
    ]);
    header('Location: team.php');
    exit;
}
