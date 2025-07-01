<?php
session_start();
require_once '../config/db.php'; // Adjust path as necessary

$error_message = '';

// If user is already logged in, redirect to admin dashboard
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
                // Password is correct, set session variables
                $_SESSION['admin_user_id'] = $user['id'];
                $_SESSION['admin_username'] = $user['username'];

                // Regenerate session ID for security
                session_regenerate_id(true);

                // Redirect to admin dashboard or intended page
                // $redirect_url = isset($_SESSION['redirect_url']) ? $_SESSION['redirect_url'] : 'index.php';
                // unset($_SESSION['redirect_url']);
                // header('Location: ' . $redirect_url);
                header('Location: index.php');
                exit;
            } else {
                $error_message = 'Invalid username or password.';
            }
        } catch (PDOException $e) {
            error_log("Login Error: " . $e->getMessage());
            $error_message = "Login Error: " . $e->getMessage();
            // $error_message = 'An error occurred. Please try again later.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Greemheld</title>
    <link href="../public/css/style.css" rel="stylesheet"> <!-- Link to Tailwind output CSS -->
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f3f4f6; /* bg-neutral-light equivalent */
        }
        .login-container {
            width: 100%;
            max-width: 400px;
        }
    </style>
</head>
<body class="font-sans">
    <div class="login-container bg-white p-8 md:p-10 rounded-xl shadow-2xl">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-primary">Greemheld Admin</h1>
            <p class="text-neutral-default">Please sign in to continue</p>
        </div>

        <?php if (!empty($error_message)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                <span class="block sm:inline"><?php echo htmlspecialchars($error_message); ?></span>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST" novalidate>
            <div class="mb-6">
                <label for="username" class="block text-neutral-default text-base font-medium mb-2">Username</label>
                <input type="text" id="username" name="username" required
                       class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-shadow duration-200"
                       placeholder="Enter your username"
                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>
            <div class="mb-8">
                <label for="password" class="block text-neutral-default text-base font-medium mb-2">Password</label>
                <input type="password" id="password" name="password" required
                       class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-shadow duration-200"
                       placeholder="Enter your password">
            </div>
            <div>
                <button type="submit"
                        class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-3 px-6 rounded-lg transition duration-300 text-lg transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-primary-light focus:ring-opacity-50">
                    Sign In
                </button>
            </div>
        </form>
        <p class="text-center text-sm text-neutral-500 mt-8">
            &copy; <?php echo date('Y'); ?> Greemheld Social Research and Consulting
        </p>
    </div>
</body>
</html>
