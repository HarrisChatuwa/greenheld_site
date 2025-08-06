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

$stmt = $pdo->prepare('SELECT * FROM team_members WHERE id = ?');
$stmt->execute([$id]);
$member = $stmt->fetch();

if ($member) {
    // Delete image file
    if (file_exists('../' . $member['photo_url'])) {
        unlink('../' . $member['photo_url']);
    }

    // Delete from database
    $sql = "DELETE FROM team_members WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
}

header('Location: team.php');
exit;
