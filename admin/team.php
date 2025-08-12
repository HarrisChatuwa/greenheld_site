<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require_once '../config/db.php';
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

// Fetch all team members
$stmt = $pdo->query('SELECT * FROM team_members ORDER BY created_at DESC');
$team_members = $stmt->fetchAll();
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Manage Team</h1>
    <button data-modal-target="#add-member-modal" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Add New Member
    </button>
</div>

<div class="bg-white shadow-md rounded my-6 overflow-x-auto">
    <table class="min-w-full leading-normal">
        <thead>
            <tr>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Role</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Bio</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Photo</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($team_members)): ?>
                <tr>
                    <td colspan="5" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">No team members yet.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($team_members as $member): ?>
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?php echo htmlspecialchars($member['name']); ?></td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?php echo htmlspecialchars($member['role']); ?></td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm truncate max-w-xs"><?php echo htmlspecialchars($member['bio']); ?></td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><img src="../<?php echo htmlspecialchars($member['photo_url']); ?>" alt="<?php echo htmlspecialchars($member['name']); ?>" width="50"></td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm whitespace-nowrap">
                            <div class="flex items-center space-x-4">
                                <button data-modal-target="#edit-member-modal-<?php echo $member['id']; ?>" class="text-indigo-600 hover:text-indigo-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                        <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <a href="delete_member.php?id=<?php echo $member['id']; ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Add Member Modal -->
<div id="add-member-modal" class="modal hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
    <div class="modal-content bg-white p-8 rounded-lg shadow-lg w-11/12 md:w-3/4 lg:w-1/2 relative">
        <button data-modal-close class="absolute top-0 right-0 mt-4 mr-4 text-gray-600 hover:text-gray-900">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <h2 class="text-2xl font-bold mb-4">Add New Team Member</h2>
        <form action="add_member.php" method="post" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-4">
                <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Role</label>
                <input type="text" name="role" id="role" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-4">
                <label for="bio" class="block text-gray-700 text-sm font-bold mb-2">Bio</label>
                <textarea name="bio" id="bio" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
            </div>
            <div class="mb-4">
                <label for="photo" class="block text-gray-700 text-sm font-bold mb-2">Photo</label>
                <input type="file" name="photo" id="photo" class="w-full">
            </div>
            <div class="flex justify-end">
                <button type="button" data-modal-close class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded mr-2">Cancel</button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Member</button>
            </div>
        </form>
    </div>
</div>

<?php foreach ($team_members as $member): ?>
<!-- Edit Member Modal -->
<div id="edit-member-modal-<?php echo $member['id']; ?>" class="modal hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
    <div class="modal-content bg-white p-8 rounded-lg shadow-lg w-11/12 md:w-3/4 lg:w-1/2 relative">
        <button data-modal-close class="absolute top-0 right-0 mt-4 mr-4 text-gray-600 hover:text-gray-900">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <h2 class="text-2xl font-bold mb-4">Edit Team Member</h2>
        <form action="edit_member.php?id=<?php echo $member['id']; ?>" method="post" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo htmlspecialchars($member['name']); ?>" required>
            </div>
            <div class="mb-4">
                <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Role</label>
                <input type="text" name="role" id="role" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo htmlspecialchars($member['role']); ?>" required>
            </div>
            <div class="mb-4">
                <label for="bio" class="block text-gray-700 text-sm font-bold mb-2">Bio</label>
                <textarea name="bio" id="bio" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?php echo htmlspecialchars($member['bio']); ?></textarea>
            </div>
            <div class="mb-4">
                <label for="photo" class="block text-gray-700 text-sm font-bold mb-2">Photo</label>
                <input type="file" name="photo" id="photo" class="w-full">
                <p class="mt-2">Current photo: <img src="../<?php echo htmlspecialchars($member['photo_url']); ?>" alt="<?php echo htmlspecialchars($member['name']); ?>" width="50"></p>
            </div>
            <div class="flex justify-end">
                <button type="button" data-modal-close class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded mr-2">Cancel</button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Member</button>
            </div>
        </form>
    </div>
</div>
<?php endforeach; ?>

<div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

<?php
require_once 'includes/footer.php';
?>
