<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../config/db.php';

// Handle AJAX requests for marking as read
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'toggle_read_ajax') {
    $submission_id = $_POST['submission_id'] ?? null;
    if ($submission_id) {
        $stmt = $pdo->prepare("UPDATE contact_submissions SET is_read = 1 WHERE id = ?");
        $stmt->execute([$submission_id]);
        // Respond with success
        echo json_encode(['success' => true]);
        exit;
    }
    // Respond with error
    echo json_encode(['success' => false]);
    exit;
}

// Handle form submissions for delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $submission_id = $_POST['submission_id'] ?? null;
    if ($submission_id) {
        $stmt = $pdo->prepare("DELETE FROM contact_submissions WHERE id = ?");
        $stmt->execute([$submission_id]);
    }
    header('Location: submissions.php');
    exit;
}

require_once 'includes/header.php';
require_once 'includes/sidebar.php';

// Fetch all submissions, newest first
$stmt = $pdo->prepare("SELECT * FROM contact_submissions ORDER BY created_at DESC");
$stmt->execute();
$submissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Contact Form Submissions</h1>

    <div class="bg-white shadow-md rounded my-6">
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Received</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Contact</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Subject</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-sm">
                    <?php if (empty($submissions)): ?>
                        <tr>
                            <td colspan="6" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">No submissions yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($submissions as $submission): ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-100 accordion-toggle <?= $submission['is_read'] ? '' : 'font-semibold bg-yellow-50'; ?>" data-id="<?= $submission['id'] ?>">
                                <td class="px-5 py-5 text-center status-cell">
                                    <span class="relative inline-block p-2 leading-tight">
                                        <?php if ($submission['is_read']): ?>
                                            <span class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                                            <span class="relative text-green-900">Read</span>
                                        <?php else: ?>
                                            <span class="absolute inset-0 bg-yellow-200 opacity-50 rounded-full"></span>
                                            <span class="relative text-yellow-900">New</span>
                                        <?php endif; ?>
                                    </span>
                                </td>
                                <td class="px-5 py-5 text-sm"><?= htmlspecialchars(date('d M Y, H:i', strtotime($submission['created_at']))) ?></td>
                                <td class="px-5 py-5 text-sm"><?= htmlspecialchars($submission['name']) ?></td>
                                <td class="px-5 py-5 text-sm"><a href="mailto:<?= htmlspecialchars($submission['email']) ?>" class="text-blue-600 hover:underline"><?= htmlspecialchars($submission['email']) ?></a></td>
                                <td class="px-5 py-5 text-sm"><?= htmlspecialchars($submission['subject']) ?></td>
                                <td class="px-5 py-5 text-center whitespace-nowrap">
                                    <div class="flex item-center justify-center space-x-4">
                                        <button class="w-6 h-6 text-gray-500 hover:text-blue-600 focus:outline-none view-btn" title="View Message">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </button>
                                        <a href="mailto:<?= htmlspecialchars($submission['email']) ?>?subject=Re: <?= htmlspecialchars($submission['subject']) ?>" class="w-6 h-6 text-gray-500 hover:text-green-600 focus:outline-none" title="Reply via Email">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                                        </a>
                                        <form action="submissions.php" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this submission?');">
                                            <input type="hidden" name="submission_id" value="<?= $submission['id'] ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <button type="submit" class="w-6 h-6 text-gray-500 hover:text-red-600 focus:outline-none" title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <!-- Accordion Content -->
                            <tr class="accordion-content hidden">
                                <td colspan="6" class="p-0">
                                    <div class="p-6 bg-gray-50 border-t border-b border-gray-200">
                                        <h4 class="text-md font-semibold text-gray-800 mb-2">Message:</h4>
                                        <p class="text-gray-700 whitespace-pre-wrap"><?= htmlspecialchars($submission['message']) ?></p>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const viewButtons = document.querySelectorAll('.view-btn');
    viewButtons.forEach(button => {
        button.addEventListener('click', function () {
            const row = this.closest('tr');
            const contentRow = row.nextElementSibling;
            const submissionId = row.dataset.id;
            const isUnread = row.classList.contains('bg-yellow-50');

            // Toggle visibility of the content row
            contentRow.classList.toggle('hidden');
            // Toggle icon direction
            this.querySelector('svg').classList.toggle('rotate-180');

            // If the message was unread, mark it as read via AJAX
            if (isUnread) {
                const formData = new FormData();
                formData.append('submission_id', submissionId);
                formData.append('action', 'toggle_read_ajax');

                fetch('submissions.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update UI to reflect "read" status
                        row.classList.remove('font-semibold', 'bg-yellow-50');
                        const statusCell = row.querySelector('.status-cell');
                        statusCell.innerHTML = `
                            <span class="relative inline-block p-2 leading-tight">
                                <span class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                                <span class="relative text-green-900">Read</span>
                            </span>
                        `;
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>
