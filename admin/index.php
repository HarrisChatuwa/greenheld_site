<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require_once '../config/db.php';
require_once 'includes/header.php';
require_once 'includes/sidebar.php';
?>

<h1 class="text-3xl font-bold">Dashboard</h1>
<p class="mt-4">Welcome to the admin panel. Here you can manage your website's content.</p>

<?php
require_once 'includes/footer.php';
?>
