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

$stmt = $pdo->prepare('SELECT * FROM projects WHERE id = ?');
$stmt->execute([$id]);
$project = $stmt->fetch();

if ($project) {
    // Delete image file
    if (file_exists('../' . $project['image_url'])) {
        unlink('../' . $project['image_url']);
    }

    // Delete from database
    $sql = "DELETE FROM projects WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
}

header('Location: projects.php');
exit;
