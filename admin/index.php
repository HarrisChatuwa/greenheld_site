<?php
require_once 'auth_check.php'; // Session start is in auth_check.php
require_once '../config/db.php'; // For any potential DB interactions, though not strictly needed for this page

// Get admin username from session
$admin_username = isset($_SESSION['admin_username']) ? htmlspecialchars($_SESSION['admin_username']) : 'Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - greenheld</title>
    <link href="../public/css/style.css" rel="stylesheet"> <!-- Link to Tailwind output CSS -->
    <style>
        /* Basic admin layout styles */
        .admin-nav a {
            @apply block px-4 py-2 text-neutral-700 hover:bg-primary-light hover:text-primary-dark rounded-md transition-colors;
        }
        .admin-nav a.active { /* Example for active link styling if needed later */
            @apply bg-primary text-white;
        }
    </style>
</head>
<body class="bg-neutral-light font-sans">
    <div class="flex h-screen bg-neutral-100">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg p-6 space-y-6">
            <div class="text-center mb-8">
                <a href="index.php" class="text-2xl font-bold text-primary">greenheld Admin</a>
            </div>
            <nav class="admin-nav space-y-2">
                <a href="index.php" class="active">Dashboard</a>
                <a href="projects_admin.php">Manage Projects</a>
                <a href="testimonials_admin.php">Manage Testimonials</a>
                <!-- Add more links here as functionality grows, e.g., User Management -->
            </nav>
            <div class="mt-auto pt-6 border-t border-neutral-200">
                 <a href="logout.php" class="block w-full text-center px-4 py-2 text-red-600 hover:bg-red-100 hover:text-red-700 rounded-md transition-colors">
                    Logout
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8 md:p-10 overflow-y-auto">
            <header class="mb-10">
                <h1 class="text-3xl md:text-4xl font-bold text-primary-dark">Admin Dashboard</h1>
                <p class="text-neutral-default mt-1">Welcome back, <?php echo $admin_username; ?>!</p>
            </header>

            <section class="grid md:grid-cols-2 gap-8">
                <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                    <h2 class="text-2xl font-semibold text-primary mb-4">Manage Projects</h2>
                    <p class="text-neutral-default mb-6">
                        Add new projects, edit existing project details, upload images, and remove projects from the public website.
                    </p>
                    <a href="projects_admin.php" class="inline-block bg-primary hover:bg-primary-dark text-white font-semibold py-2 px-4 rounded-md transition duration-300">
                        Go to Projects
                    </a>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                    <h2 class="text-2xl font-semibold text-primary mb-4">Manage Testimonials</h2>
                    <p class="text-neutral-default mb-6">
                        Create new client testimonials, update existing quotes or client details, upload client photos, and delete testimonials.
                    </p>
                    <a href="testimonials_admin.php" class="inline-block bg-primary hover:bg-primary-dark text-white font-semibold py-2 px-4 rounded-md transition duration-300">
                        Go to Testimonials
                    </a>
                </div>
            </section>

            <!-- Future widgets or stats could go here -->
            <!--
            <section class="mt-12 bg-white p-6 rounded-xl shadow-lg">
                <h2 class="text-2xl font-semibold text-primary-dark mb-4">Site Statistics (Placeholder)</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div><p class="text-neutral-default"><strong class="text-primary">Total Projects:</strong> [PHP Count]</p></div>
                    <div><p class="text-neutral-default"><strong class="text-primary">Total Testimonials:</strong> [PHP Count]</p></div>
                </div>
            </section>
            -->

        </main>
    </div>
</body>
</html>
