<?php
require_once 'auth_check.php';
require_once '../config/db.php';

$page_title = "Manage Testimonials";

// Fetch all testimonials
try {
    $stmt = $pdo->query("SELECT id, LEFT(quote, 150) AS quote_snippet, client_name, client_title_company, client_photo_url, created_at FROM greenheld.testimonials ORDER BY created_at DESC");
    $testimonials = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching testimonials: " . $e->getMessage());
    $testimonials = [];
    $fetch_error = "Could not retrieve testimonials from the database.";
}

// Handle success/error messages from process_testimonial.php
$success_message = $_SESSION['success_message'] ?? null;
$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['success_message'], $_SESSION['error_message']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?> - greenheld Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="../public/css/style.css" rel="stylesheet">
    <link href="assets/css/dashboard.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 sidebar flex flex-col">
            <div class="p-6 text-center border-b border-gray-700">
                <a href="index.php" class="text-2xl font-bold text-white">greenheld Admin</a>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="index.php" class="block px-4 py-2 rounded-md">
                    Dashboard
                </a>
                <a href="projects_admin.php" class="block px-4 py-2 rounded-md">
                    Manage Projects
                </a>
                <a href="testimonials_admin.php" class="block px-4 py-2 rounded-md active">
                    Manage Testimonials
                </a>
            </nav>
            <div class="p-4 border-t border-gray-700">
                 <a href="logout.php" class="block w-full text-center px-4 py-2 text-red-300 hover:bg-red-700 hover:text-white rounded-md transition-colors duration-200">
                    Logout
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8 overflow-y-auto main-content">
            <header class="mb-8">
                <h1 class="text-4xl font-bold text-gray-800"><?php echo htmlspecialchars($page_title); ?></h1>
            </header>

            <?php if ($success_message): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
                    <p><?php echo htmlspecialchars($success_message); ?></p>
                </div>
            <?php endif; ?>
            <?php if ($error_message): ?>
                 <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
                    <p><?php echo htmlspecialchars($error_message); ?></p>
                </div>
            <?php endif; ?>
             <?php if (isset($fetch_error)): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
                    <p><?php echo htmlspecialchars($fetch_error); ?></p>
                </div>
            <?php endif; ?>

            <!-- Add New Testimonial Form -->
            <section id="add-testimonial-form" class="mb-8 bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Add New Testimonial</h2>
                <form action="process_testimonial.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                    <input type="hidden" name="action" value="add">

                    <div>
                        <label for="client_name" class="block text-sm font-medium text-gray-700">Client Name <span class="text-red-500">*</span></label>
                        <input type="text" id="client_name" name="client_name" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm form-input">
                    </div>

                    <div>
                        <label for="client_title_company" class="block text-sm font-medium text-gray-700">Client Title/Company</label>
                        <input type="text" id="client_title_company" name="client_title_company" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm form-input">
                    </div>

                    <div>
                        <label for="quote" class="block text-sm font-medium text-gray-700">Quote <span class="text-red-500">*</span></label>
                        <textarea id="quote" name="quote" rows="4" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm form-input"></textarea>
                    </div>

                    <div>
                        <label for="client_photo" class="block text-sm font-medium text-gray-700">Client Photo/Logo (Optional)</label>
                        <input type="file" id="client_photo" name="client_photo" accept="image/jpeg, image/png, image/gif" class="mt-1 block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-md file:border-0
                            file:text-sm file:font-semibold
                            file:bg-primary file:text-white
                            hover:file:bg-primary-dark
                        ">
                        <p class="mt-1 text-xs text-gray-500">Accepted formats: JPG, PNG, GIF. Max size: 1MB (example).</p>
                    </div>

                    <div>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary btn-primary">
                            Add Testimonial
                        </button>
                    </div>
                </form>
            </section>

            <!-- Display Existing Testimonials -->
            <section id="display-testimonials" class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Existing Testimonials</h2>
                <?php if (empty($testimonials) && !isset($fetch_error)): ?>
                    <p class="text-gray-600">No testimonials found. Add one using the form above.</p>
                <?php elseif (!empty($testimonials)): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Photo</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title/Company</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quote Snippet</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($testimonials as $testimonial): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($testimonial['id']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if (!empty($testimonial['client_photo_url'])): ?>
                                            <img src="../<?php echo htmlspecialchars($testimonial['client_photo_url']); ?>" alt="<?php echo htmlspecialchars($testimonial['client_name']); ?>" class="h-16 w-16 object-cover rounded-full">
                                        <?php else: ?>
                                            N/A
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($testimonial['client_name']); ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-500"><?php echo htmlspecialchars($testimonial['client_title_company'] ?? ''); ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-500"><?php echo htmlspecialchars($testimonial['quote_snippet']); ?>...</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars(date('M j, Y H:i', strtotime($testimonial['created_at']))); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="edit_testimonial.php?id=<?php echo $testimonial['id']; ?>" class="text-primary hover:text-primary-dark mr-3">Edit</a>
                                        <a href="process_testimonial.php?action=delete&id=<?php echo $testimonial['id']; ?>"
                                           class="text-red-600 hover:text-red-900"
                                           onclick="return confirm('Are you sure you want to delete this testimonial?');">Delete</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </section>
        </main>
    </div>
</body>
</html>