<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require_once '../config/db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: testimonials.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM testimonials WHERE id = ?');
$stmt->execute([$id]);
$testimonial = $stmt->fetch();

if ($testimonial) {
    // Delete image file
    if ($testimonial && !empty($testimonial['client_photo_url']) && file_exists('../' . $testimonial['client_photo_url'])) {
        unlink('../' . $testimonial['client_photo_url']);
    }

    // Delete from database
    $sql = "DELETE FROM testimonials WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
}

header('Location: testimonials.php');
exit;
