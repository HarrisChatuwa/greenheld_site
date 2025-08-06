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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quote = trim($_POST['quote']);
    $client_name = trim($_POST['client_name']);
    $client_title_company = trim($_POST['client_title_company']);

    $stmt = $pdo->prepare('SELECT client_photo_url FROM testimonials WHERE id = ?');
    $stmt->execute([$id]);
    $testimonial = $stmt->fetch();
    $photoUrl = $testimonial['client_photo_url'];

    // Image upload handling
    if (isset($_FILES['client_photo']) && $_FILES['client_photo']['error'] == 0) {
        $targetDir = "../uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        $fileName = basename($_FILES["client_photo"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
        if (in_array($fileType, $allowTypes)) {
            if (move_uploaded_file($_FILES["client_photo"]["tmp_name"], $targetFilePath)) {
                $photoUrl = "uploads/" . $fileName;
            }
        }
    }

    $sql = "UPDATE testimonials SET quote = :quote, client_name = :client_name, client_title_company = :client_title_company, client_photo_url = :client_photo_url WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'quote' => $quote,
        'client_name' => $client_name,
        'client_title_company' => $client_title_company,
        'client_photo_url' => $photoUrl,
        'id' => $id
    ]);
    header('Location: testimonials.php');
    exit;
}