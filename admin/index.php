<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require_once '../config/db.php';
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

// Fetch counts
$projects_count = $pdo->query('SELECT count(*) FROM projects')->fetchColumn();
$team_members_count = $pdo->query('SELECT count(*) FROM team_members')->fetchColumn();
$testimonials_count = $pdo->query('SELECT count(*) FROM testimonials')->fetchColumn();
$users_count = $pdo->query('SELECT count(*) FROM users')->fetchColumn();

// Fetch recent projects
$stmt_projects = $pdo->query('SELECT * FROM projects ORDER BY created_at DESC LIMIT 5');
$recent_projects = $stmt_projects->fetchAll();

// Fetch recent testimonials
$stmt_testimonials = $pdo->query('SELECT * FROM testimonials ORDER BY created_at DESC LIMIT 5');
$recent_testimonials = $stmt_testimonials->fetchAll();
?>

<h1 class="text-3xl font-bold">Dashboard</h1>
<p class="mt-4">Welcome to the admin panel. Here you can manage your website's content.</p>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-bold text-gray-800"><?php echo $projects_count; ?></h2>
        <p class="text-gray-600">Projects</p>
    </div>
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-bold text-gray-800"><?php echo $team_members_count; ?></h2>
        <p class="text-gray-600">Team Members</p>
    </div>
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-bold text-gray-800"><?php echo $testimonials_count; ?></h2>
        <p class="text-gray-600">Testimonials</p>
    </div>
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-bold text-gray-800"><?php echo $users_count; ?></h2>
        <p class="text-gray-600">Users</p>
    </div>
</div>

<!-- <div class="mt-8">
    <h2 class="text-2xl font-bold mb-4">Quick Links</h2>
    <div class="flex space-x-4">
        <a href="add_project.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Project</a>
        <a href="add_member.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Team Member</a>
        <a href="add_testimonial.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Testimonial</a>
    </div>
</div> -->

<div class="mt-8">
    <h2 class="text-2xl font-bold mb-4">Recent Activity</h2>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-xl font-bold mb-4">Recent Projects</h3>
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Title</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_projects as $project): ?>
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?php echo htmlspecialchars($project['title']); ?></td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?php echo date('M d, Y', strtotime($project['created_at'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-xl font-bold mb-4">Recent Testimonials</h3>
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Client Name</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_testimonials as $testimonial): ?>
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?php echo htmlspecialchars($testimonial['client_name']); ?></td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?php echo date('M d, Y', strtotime($testimonial['created_at'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>