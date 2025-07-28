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
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white flex flex-col">
            <div class="p-6 text-center border-b border-gray-700">
                <a href="index.php" class="text-2xl font-bold text-white">greenheld Admin</a>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="index.php" class="block px-4 py-2 rounded-md text-gray-300 bg-gray-900 font-semibold">
                    Dashboard
                </a>
                <a href="projects_admin.php" class="block px-4 py-2 rounded-md text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200">
                    Manage Projects
                </a>
                <a href="testimonials_admin.php" class="block px-4 py-2 rounded-md text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200">
                    Manage Testimonials
                </a>
                <!-- Add more links here as functionality grows, e.g., User Management -->
            </nav>
            <div class="p-4 border-t border-gray-700">
                 <a href="logout.php" class="block w-full text-center px-4 py-2 text-red-300 hover:bg-red-700 hover:text-white rounded-md transition-colors duration-200">
                    Logout
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8 overflow-y-auto bg-gray-100">
            <header class="mb-8">
                <h1 class="text-4xl font-bold text-gray-800">Admin Dashboard</h1>
                <p class="text-gray-600 mt-2">Welcome back, <?php echo $admin_username; ?>!</p>
            </header>

            <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-3">Manage Projects</h2>
                    <p class="text-gray-600 mb-4">
                        Add new projects, edit existing project details, upload images, and remove projects from the public website.
                    </p>
                    <a href="projects_admin.php" class="inline-block bg-primary hover:bg-primary-dark text-white font-semibold py-2 px-4 rounded-md transition duration-300">
                        Go to Projects
                    </a>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-3">Manage Testimonials</h2>
                    <p class="text-gray-600 mb-4">
                        Create new client testimonials, update existing quotes or client details, upload client photos, and delete testimonials.
                    </p>
                    <a href="testimonials_admin.php" class="inline-block bg-primary hover:bg-primary-dark text-white font-semibold py-2 px-4 rounded-md transition duration-300">
                        Go to Testimonials
                    </a>
                </div>

                <!-- Future widgets or stats could go here -->
                <!--
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-3">Site Statistics (Placeholder)</h2>
                    <p class="text-gray-600 mb-4">
                        Overview of key metrics like total projects, testimonials, and user activity.
                    </p>
                    <a href="#" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-md transition duration-300">
                        View Statistics
                    </a>
                </div>
                -->
            </section>
        </main>
    </div>
</body>
</html>