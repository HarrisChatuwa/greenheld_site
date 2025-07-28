<?php
session_start();
require_once '../config/db.php';

$error_message = '';

if (isset($_SESSION['admin_user_id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error_message = 'Please enter both username and password.';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, username, password FROM greenheld.users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['admin_user_id'] = $user['id'];
                $_SESSION['admin_username'] = $user['username'];
                session_regenerate_id(true);
                header('Location: index.php');
                exit;
            } else {
                $error_message = 'Invalid username or password.';
            }
        } catch (PDOException $e) {
            error_log("Login Error: " . $e->getMessage());
            $error_message = 'An error occurred. Please try again later.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - greenheld</title>
    <link href="../public/css/style.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-md">
        <h1 class="text-2xl font-bold text-center text-gray-900">greenheld Admin</h1>
        <?php if (!empty($error_message)): ?>
            <div class="p-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        <form class="space-y-6" action="login.php" method="POST">
            <div>
                <label for="username" class="text-sm font-medium text-gray-700">Username</label>
                <input type="text" id="username" name="username" required class="block w-full px-3 py-2 mt-1 text-gray-900 bg-gray-50 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
            </div>
            <div>
                <label for="password" class="text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" required class="block w-full px-3 py-2 mt-1 text-gray-900 bg-gray-50 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
            </div>
            <button type="submit" class="w-full px-4 py-2 font-medium text-white bg-primary rounded-md hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">Sign In</button>
        </form>
    </div>
</body>
</html>