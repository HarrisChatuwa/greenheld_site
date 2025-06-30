<?php
require_once 'auth_check.php';
require_once '../config/db.php';

$page_title = "Edit Project";
$project_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($project_id <= 0) {
    $_SESSION['error_message'] = "Invalid project ID.";
    header('Location: projects_admin.php');
    exit;
}

// Fetch project details
try {
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = :id");
    $stmt->bindParam(':id', $project_id, PDO::PARAM_INT);
    $stmt->execute();
    $project = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$project) {
        $_SESSION['error_message'] = "Project not found.";
        header('Location: projects_admin.php');
        exit;
    }
} catch (PDOException $e) {
    error_log("Error fetching project for edit: " . $e->getMessage());
    $_SESSION['error_message'] = "Database error: Could not retrieve project details.";
    header('Location: projects_admin.php');
    exit;
}

// Handle success/error messages from process_project.php (if redirected back here on error)
$form_error_message = $_SESSION['error_message'] ?? null; // Use a different variable to avoid conflict
unset($_SESSION['error_message']); // Clear it after displaying

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?> - Greemheld Admin</title>
    <link href="../public/css/style.css" rel="stylesheet">
    <style>
        .admin-nav a { @apply block px-4 py-2 text-neutral-700 hover:bg-primary-light hover:text-primary-dark rounded-md transition-colors; }
        .admin-nav a.active { @apply bg-primary text-white; }
        .current-image-preview { @apply max-w-xs h-auto rounded-md shadow-md mb-4; }
    </style>
</head>
<body class="bg-neutral-light font-sans">
    <div class="flex h-screen bg-neutral-100">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg p-6 space-y-6 flex flex-col">
            <div class="text-center mb-8">
                <a href="index.php" class="text-2xl font-bold text-primary">Greemheld Admin</a>
            </div>
            <nav class="admin-nav space-y-2">
                <a href="index.php">Dashboard</a>
                <a href="projects_admin.php" class="active">Manage Projects</a>
                <a href="testimonials_admin.php">Manage Testimonials</a>
            </nav>
            <div class="mt-auto pt-6 border-t border-neutral-200">
                 <a href="logout.php" class="block w-full text-center px-4 py-2 text-red-600 hover:bg-red-100 hover:text-red-700 rounded-md transition-colors">
                    Logout
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8 md:p-10 overflow-y-auto">
            <header class="mb-6 flex justify-between items-center">
                <h1 class="text-3xl md:text-4xl font-bold text-primary-dark"><?php echo htmlspecialchars($page_title); ?></h1>
                <a href="projects_admin.php" class="text-primary hover:underline">&laquo; Back to Projects</a>
            </header>

            <?php if ($form_error_message): ?>
                 <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
                    <p><?php echo htmlspecialchars($form_error_message); ?></p>
                </div>
            <?php endif; ?>

            <section class="bg-white p-6 md:p-8 rounded-xl shadow-lg">
                <form action="process_project.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="project_id" value="<?php echo htmlspecialchars($project['id']); ?>">

                    <div class="mb-4">
                        <label for="title" class="block text-neutral-700 font-medium mb-1">Title <span class="text-red-500">*</span></label>
                        <input type="text" id="title" name="title" required
                               value="<?php echo htmlspecialchars($project['title']); ?>"
                               class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-neutral-700 font-medium mb-1">Description <span class="text-red-500">*</span></label>
                        <textarea id="description" name="description" rows="8" required
                                  class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"><?php echo htmlspecialchars($project['description']); ?></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="outcome" class="block text-neutral-700 font-medium mb-1">Key Outcome/Impact</label>
                        <textarea id="outcome" name="outcome" rows="4"
                                  class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"><?php echo htmlspecialchars($project['outcome'] ?? ''); ?></textarea>
                    </div>

                    <div class="mb-6">
                        <label for="image" class="block text-neutral-700 font-medium mb-1">Project Image</label>
                        <?php if (!empty($project['image_url'])): ?>
                            <div class="my-2">
                                <p class="text-sm text-neutral-600 mb-1">Current Image:</p>
                                <img src="../<?php echo htmlspecialchars($project['image_url']); ?>" alt="Current project image" class="current-image-preview">
                            </div>
                        <?php endif; ?>
                        <input type="file" id="image" name="image" accept="image/jpeg, image/png, image/gif" class="w-full text-sm text-neutral-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-lg file:border-0
                            file:text-sm file:font-semibold
                            file:bg-primary-light file:text-primary-dark
                            hover:file:bg-primary-dark hover:file:text-white
                        ">
                        <p class="text-xs text-neutral-500 mt-1">Leave empty to keep the current image. Accepted formats: JPG, PNG, GIF. Max size: 2MB (example).</p>
                    </div>

                    <div>
                        <button type="submit" class="bg-accent hover:bg-pink-600 text-white font-bold py-2 px-6 rounded-lg transition duration-300">
                            Update Project
                        </button>
                        <a href="projects_admin.php" class="text-neutral-600 hover:text-neutral-800 ml-4">Cancel</a>
                    </div>
                </form>
            </section>
        </main>
    </div>
</body>
</html>
