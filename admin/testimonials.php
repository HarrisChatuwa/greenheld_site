<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require_once '../config/db.php';
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

// Fetch all testimonials
$stmt = $pdo->query('SELECT * FROM testimonials ORDER BY created_at DESC');
$testimonials = $stmt->fetchAll();
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Manage Testimonials</h1>
    <button data-modal-target="#add-testimonial-modal" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Add New Testimonial
    </button>
</div>

<div class="bg-white shadow-md rounded my-6 overflow-x-auto">
    <table class="min-w-full leading-normal">
        <thead>
            <tr>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Quote</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Client Name</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Client Title/Company</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Client Photo</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($testimonials)): ?>
                <tr>
                    <td colspan="5" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">No testimonials yet.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($testimonials as $testimonial): ?>
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm truncate max-w-xs"><?php echo htmlspecialchars($testimonial['quote']); ?></td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?php echo htmlspecialchars($testimonial['client_name']); ?></td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?php echo htmlspecialchars($testimonial['client_title_company']); ?></td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><img src="../<?php echo htmlspecialchars($testimonial['client_photo_url']); ?>" alt="<?php echo htmlspecialchars($testimonial['client_name']); ?>" width="50"></td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-4">
                                <button data-modal-target="#edit-testimonial-modal-<?php echo $testimonial['id']; ?>" class="w-6 h-6 text-gray-500 hover:text-indigo-600 focus:outline-none" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </button>
                                <a href="delete_testimonial.php?id=<?php echo $testimonial['id']; ?>" class="w-6 h-6 text-gray-500 hover:text-red-600 focus:outline-none" title="Delete" onclick="return confirm('Are you sure?')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Add Testimonial Modal -->
<div id="add-testimonial-modal" class="modal hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
    <div class="modal-content bg-white p-8 rounded-lg shadow-lg w-11/12 md:w-3/4 lg:w-1/2 relative">
        <button data-modal-close class="absolute top-0 right-0 mt-4 mr-4 text-gray-600 hover:text-gray-900">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <h2 class="text-2xl font-bold mb-4">Add New Testimonial</h2>
        <form action="add_testimonial.php" method="post" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="quote" class="block text-gray-700 text-sm font-bold mb-2">Quote</label>
                <textarea name="quote" id="quote" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
            </div>
            <div class="mb-4">
                <label for="client_name" class="block text-gray-700 text-sm font-bold mb-2">Client Name</label>
                <input type="text" name="client_name" id="client_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-4">
                <label for="client_title_company" class="block text-gray-700 text-sm font-bold mb-2">Client Title/Company</label>
                <input type="text" name="client_title_company" id="client_title_company" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="client_photo" class="block text-gray-700 text-sm font-bold mb-2">Client Photo</label>
                <input type="file" name="client_photo" id="client_photo" class="w-full">
            </div>
            <div class="flex justify-end">
                <button type="button" data-modal-close class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded mr-2">Cancel</button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Testimonial</button>
            </div>
        </form>
    </div>
</div>

<?php foreach ($testimonials as $testimonial): ?>
<!-- Edit Testimonial Modal -->
<div id="edit-testimonial-modal-<?php echo $testimonial['id']; ?>" class="modal hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
    <div class="modal-content bg-white p-8 rounded-lg shadow-lg w-11/12 md:w-3/4 lg:w-1/2 relative">
        <button data-modal-close class="absolute top-0 right-0 mt-4 mr-4 text-gray-600 hover:text-gray-900">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <h2 class="text-2xl font-bold mb-4">Edit Testimonial</h2>
        <form action="edit_testimonial.php?id=<?php echo $testimonial['id']; ?>" method="post" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="quote" class="block text-gray-700 text-sm font-bold mb-2">Quote</label>
                <textarea name="quote" id="quote" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required><?php echo htmlspecialchars($testimonial['quote']); ?></textarea>
            </div>
            <div class="mb-4">
                <label for="client_name" class="block text-gray-700 text-sm font-bold mb-2">Client Name</label>
                <input type="text" name="client_name" id="client_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo htmlspecialchars($testimonial['client_name']); ?>" required>
            </div>
            <div class="mb-4">
                <label for="client_title_company" class="block text-gray-700 text-sm font-bold mb-2">Client Title/Company</label>
                <input type="text" name="client_title_company" id="client_title_company" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo htmlspecialchars($testimonial['client_title_company']); ?>">
            </div>
            <div class="mb-4">
                <label for="client_photo" class="block text-gray-700 text-sm font-bold mb-2">Client Photo</label>
                <input type="file" name="client_photo" id="client_photo" class="w-full">
                <p class="mt-2">Current photo: <img src="../<?php echo htmlspecialchars($testimonial['client_photo_url']); ?>" alt="<?php echo htmlspecialchars($testimonial['client_name']); ?>" width="50"></p>
            </div>
            <div class="flex justify-end">
                <button type="button" data-modal-close class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded mr-2">Cancel</button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Testimonial</button>
            </div>
        </form>
    </div>
</div>
<?php endforeach; ?>

<div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

<?php
require_once 'includes/footer.php';
?>