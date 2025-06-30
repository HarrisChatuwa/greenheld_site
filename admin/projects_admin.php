<?php
require_once 'auth_check.php';
require_once '../config/db.php';

$page_title = "Manage Projects";

// Fetch all projects
try {
    $stmt = $pdo->query("SELECT id, title, LEFT(description, 100) AS description_snippet, image_url, LEFT(outcome, 100) AS outcome_snippet, created_at FROM projects ORDER BY created_at DESC");
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching projects: " . $e->getMessage());
    // In a real app, you might set an error message to display to the user
    $projects = []; // Ensure $projects is an array even on error
    $fetch_error = "Could not retrieve projects from the database.";
}

// Handle success/error messages from process_project.php (via session flash messages)
$success_message = $_SESSION['success_message'] ?? null;
$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['success_message'], $_SESSION['error_message']);

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
        th, td { @apply px-4 py-2 border border-neutral-300 text-left; }
        table { @apply w-full border-collapse; }
        .thumbnail { @apply w-20 h-auto object-cover; }
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
            <header class="mb-10">
                <h1 class="text-3xl md:text-4xl font-bold text-primary-dark"><?php echo htmlspecialchars($page_title); ?></h1>
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


            <!-- Add New Project Form -->
            <section id="add-project-form" class="mb-12 bg-white p-6 md:p-8 rounded-xl shadow-lg">
                <h2 class="text-2xl font-semibold text-primary-dark mb-6">Add New Project</h2>
                <form action="process_project.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="add">

                    <div class="mb-4">
                        <label for="title" class="block text-neutral-700 font-medium mb-1">Title <span class="text-red-500">*</span></label>
                        <input type="text" id="title" name="title" required class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-neutral-700 font-medium mb-1">Description <span class="text-red-500">*</span></label>
                        <textarea id="description" name="description" rows="5" required class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="outcome" class="block text-neutral-700 font-medium mb-1">Key Outcome/Impact</label>
                        <textarea id="outcome" name="outcome" rows="3" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"></textarea>
                    </div>

                    <div class="mb-6">
                        <label for="image" class="block text-neutral-700 font-medium mb-1">Project Image <span class="text-red-500">*</span></label>
                        <input type="file" id="image" name="image" required accept="image/jpeg, image/png, image/gif" class="w-full text-sm text-neutral-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-lg file:border-0
                            file:text-sm file:font-semibold
                            file:bg-primary-light file:text-primary-dark
                            hover:file:bg-primary-dark hover:file:text-white
                        ">
                        <p class="text-xs text-neutral-500 mt-1">Accepted formats: JPG, PNG, GIF. Max size: 2MB (example).</p>
                    </div>

                    <div>
                        <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-6 rounded-lg transition duration-300">
                            Add Project
                        </button>
                    </div>
                </form>
            </section>

            <!-- Display Existing Projects -->
            <section id="display-projects" class="bg-white p-6 md:p-8 rounded-xl shadow-lg">
                <h2 class="text-2xl font-semibold text-primary-dark mb-6">Existing Projects</h2>
                <?php if (empty($projects) && !isset($fetch_error)): ?>
                    <p class="text-neutral-600">No projects found. Add one using the form above.</p>
                <?php elseif (!empty($projects)): ?>
                    <div class="overflow-x-auto">
                        <table>
                            <thead>
                                <tr class="bg-neutral-100">
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Description Snippet</th>
                                    <th>Outcome Snippet</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($projects as $project): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($project['id']); ?></td>
                                    <td>
                                        <?php if (!empty($project['image_url'])): ?>
                                            <img src="../<?php echo htmlspecialchars($project['image_url']); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>" class="thumbnail">
                                        <?php else: ?>
                                            N/A
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($project['title']); ?></td>
                                    <td><?php echo htmlspecialchars($project['description_snippet']); ?>...</td>
                                    <td><?php echo htmlspecialchars($project['outcome_snippet'] ?? ''); ?>...</td>
                                    <td><?php echo htmlspecialchars(date('M j, Y H:i', strtotime($project['created_at']))); ?></td>
                                    <td class="space-x-2 whitespace-nowrap">
                                        <a href="edit_project.php?id=<?php echo $project['id']; ?>" class="text-blue-600 hover:text-blue-800 hover:underline">Edit</a>
                                        <a href="process_project.php?action=delete&id=<?php echo $project['id']; ?>"
                                           class="text-red-600 hover:text-red-800 hover:underline"
                                           onclick="return confirm('Are you sure you want to delete this project? This cannot be undone.');">Delete</a>
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
