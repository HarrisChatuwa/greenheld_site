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
$submissions_total_count = $pdo->query('SELECT count(*) FROM contact_submissions')->fetchColumn();
$submissions_unread_count = $pdo->query('SELECT count(*) FROM contact_submissions WHERE is_read = 0')->fetchColumn();

// Fetch recent submissions
$stmt_submissions = $pdo->query('SELECT * FROM contact_submissions ORDER BY created_at DESC LIMIT 5');
$recent_submissions = $stmt_submissions->fetchAll();

// Fetch recent projects
$stmt_projects = $pdo->query('SELECT * FROM projects ORDER BY created_at DESC LIMIT 5');
$recent_projects = $stmt_projects->fetchAll();

// Fetch recent testimonials
$stmt_testimonials = $pdo->query('SELECT * FROM testimonials ORDER BY created_at DESC LIMIT 5');
$recent_testimonials = $stmt_testimonials->fetchAll();

// Fetch recent team members
$stmt_team = $pdo->query('SELECT * FROM team_members ORDER BY created_at DESC LIMIT 5');
$recent_team_members = $stmt_team->fetchAll();
?>

<h1 class="text-3xl font-bold">Dashboard</h1>
<p class="mt-4">Welcome to the admin panel. Here you can manage your website's content.</p>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mt-6">
    <div class="bg-white shadow-md rounded-lg p-6 flex items-center">
        <div class="mr-4">
            <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
        </div>
        <div>
            <h2 class="text-2xl font-bold text-gray-800"><?php echo $submissions_total_count; ?></h2>
            <p class="text-gray-600">Total Submissions</p>
        </div>
    </div>
    <div class="bg-white shadow-md rounded-lg p-6 flex items-center">
        <div class="mr-4">
            <svg class="w-10 h-10 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0l-8-4-8 4"></path></svg>
        </div>
        <div>
            <h2 class="text-2xl font-bold text-gray-800"><?php echo $submissions_unread_count; ?></h2>
            <p class="text-gray-600">Unread Submissions</p>
        </div>
    </div>
    <div class="bg-white shadow-md rounded-lg p-6 flex items-center">
        <div class="mr-4">
            <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M5 21v-5a2 2 0 012-2h10a2 2 0 012 2v5m-4-4h.01"></path></svg>
        </div>
        <div>
            <h2 class="text-2xl font-bold text-gray-800"><?php echo $projects_count; ?></h2>
            <p class="text-gray-600">Projects</p>
        </div>
    </div>
    <div class="bg-white shadow-md rounded-lg p-6 flex items-center">
        <div class="mr-4">
            <svg class="w-10 h-10 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.28-1.25-.743-1.672M17 20v-2a3 3 0 00-3-3H7a3 3 0 00-3 3v2m10 0v-2a3 3 0 00-3-3H7a3 3 0 00-3 3v2m10 0h-3.172a3 3 0 00-5.656 0H7m10 0H7m10 0v-2a3 3 0 00-3-3H7a3 3 0 00-3 3v2"></path></svg>
        </div>
        <div>
            <h2 class="text-2xl font-bold text-gray-800"><?php echo $team_members_count; ?></h2>
            <p class="text-gray-600">Team Members</p>
        </div>
    </div>
    <div class="bg-white shadow-md rounded-lg p-6 flex items-center">
        <div class="mr-4">
            <svg class="w-10 h-10 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
        </div>
        <div>
            <h2 class="text-2xl font-bold text-gray-800"><?php echo $testimonials_count; ?></h2>
            <p class="text-gray-600">Testimonials</p>
        </div>
    </div>
    <div class="bg-white shadow-md rounded-lg p-6 flex items-center">
        <div class="mr-4">
            <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21v-1a6 6 0 00-5.176-5.97M15 21v-1a6 6 0 00-9-5.197"></path></svg>
        </div>
        <div>
            <h2 class="text-2xl font-bold text-gray-800"><?php echo $users_count; ?></h2>
            <p class="text-gray-600">Users</p>
        </div>
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
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-xl font-bold mb-4">Recent Submissions</h3>
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Received At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_submissions as $submission): ?>
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?php echo htmlspecialchars($submission['name']); ?></td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?php echo date('M d, Y', strtotime($submission['created_at'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
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
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-xl font-bold mb-4">Recent Team Members</h3>
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Added At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_team_members as $member): ?>
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?php echo htmlspecialchars($member['name']); ?></td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?php echo date('M d, Y', strtotime($member['created_at'])); ?></td>
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