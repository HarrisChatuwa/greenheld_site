<?php
require_once 'auth_check.php';
require_once '../config/db.php';

$page_title = "Edit Testimonial";
$testimonial_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($testimonial_id <= 0) {
    $_SESSION['error_message'] = "Invalid testimonial ID.";
    header('Location: testimonials_admin.php');
    exit;
}

// Fetch testimonial details
try {
    $stmt = $pdo->prepare("SELECT * FROM greenheld.testimonials WHERE id = :id");
    $stmt->bindParam(':id', $testimonial_id, PDO::PARAM_INT);
    $stmt->execute();
    $testimonial = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$testimonial) {
        $_SESSION['error_message'] = "Testimonial not found.";
        header('Location: testimonials_admin.php');
        exit;
    }
} catch (PDOException $e) {
    error_log("Error fetching testimonial for edit: " . $e->getMessage());
    $_SESSION['error_message'] = "Database error: Could not retrieve testimonial details.";
    header('Location: testimonials_admin.php');
    exit;
}

$form_error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['error_message']);
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
            <header class="mb-6 flex justify-between items-center">
                <h1 class="text-4xl font-bold text-gray-800"><?php echo htmlspecialchars($page_title); ?></h1>
                <a href="testimonials_admin.php" class="text-primary hover:underline text-sm">
                    <span aria-hidden="true">&laquo;</span> Back to Testimonials
                </a>
            </header>

            <?php if ($form_error_message): ?>
                 <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
                    <p><?php echo htmlspecialchars($form_error_message); ?></p>
                </div>
            <?php endif; ?>

            <section class="bg-white p-6 rounded-lg shadow-md">
                <form action="process_testimonial.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="testimonial_id" value="<?php echo htmlspecialchars($testimonial['id']); ?>">

                    <div>
                        <label for="client_name" class="block text-sm font-medium text-gray-700">Client Name <span class="text-red-500">*</span></label>
                        <input type="text" id="client_name" name="client_name" required
                               value="<?php echo htmlspecialchars($testimonial['client_name']); ?>"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm form-input">
                    </div>

                    <div>
                        <label for="client_title_company" class="block text-sm font-medium text-gray-700">Client Title/Company</label>
                        <input type="text" id="client_title_company" name="client_title_company"
                               value="<?php echo htmlspecialchars($testimonial['client_title_company'] ?? ''); ?>"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm form-input">
                    </div>

                    <div>
                        <label for="quote" class="block text-sm font-medium text-gray-700">Quote <span class="text-red-500">*</span></label>
                        <textarea id="quote" name="quote" rows="6" required
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm form-input"><?php echo htmlspecialchars($testimonial['quote']); ?></textarea>
                    </div>

                    <div>
                        <label for="client_photo" class="block text-sm font-medium text-gray-700">Client Photo/Logo</label>
                        <?php if (!empty($testimonial['client_photo_url'])): ?>
                            <div class="my-2">
                                <p class="text-sm text-gray-600 mb-1">Current Photo:</p>
                                <img src="../<?php echo htmlspecialchars($testimonial['client_photo_url']); ?>" alt="Current client photo" class="max-w-[150px] h-auto rounded-full shadow-md mb-4">
                            </div>
                        <?php endif; ?>
                        <input type="file" id="client_photo" name="client_photo" accept="image/jpeg, image/png, image/gif" class="mt-1 block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-md file:border-0
                            file:text-sm file:font-semibold
                            file:bg-primary file:text-white
                            hover:file:bg-primary-dark
                        ">
                        <p class="mt-1 text-xs text-gray-500">Leave empty to keep the current photo. Accepted formats: JPG, PNG, GIF. Max size: 1MB (example).</p>
                    </div>

                    <div class="flex items-center space-x-4">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary btn-primary">
                            Update Testimonial
                        </button>
                        <a href="testimonials_admin.php" class="text-gray-600 hover:text-gray-900 text-sm">Cancel</a>
                    </div>
                </form>
            </section>
        </main>
    </div>
</body>
</html>
