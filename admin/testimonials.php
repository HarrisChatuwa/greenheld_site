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

<div class="bg-white shadow-md rounded my-6">
    <table class="min-w-full leading-normal">
        <thead>
            <tr>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Quote</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Client Name</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Client Title/Company</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Client Photo</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($testimonials as $testimonial): ?>
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?php echo htmlspecialchars($testimonial['quote']); ?></td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?php echo htmlspecialchars($testimonial['client_name']); ?></td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?php echo htmlspecialchars($testimonial['client_title_company']); ?></td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><img src="../<?php echo htmlspecialchars($testimonial['client_photo_url']); ?>" alt="<?php echo htmlspecialchars($testimonial['client_name']); ?>" width="50"></td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <button data-modal-target="#edit-testimonial-modal-<?php echo $testimonial['id']; ?>" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                        <a href="delete_testimonial.php?id=<?php echo $testimonial['id']; ?>" class="text-red-600 hover:text-red-900 ml-4" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Add Testimonial Modal -->
<div id="add-testimonial-modal" class="modal fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center">
    <div class="modal-content bg-white p-8 rounded-lg shadow-lg w-1/2">
        <h2 class="text-2xl font-bold mb-4">Add New Testimonial</h2>
        <form action="add_testimonial.php" method="post" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="quote" class="block text-gray-700">Quote</label>
                <textarea name="quote" id="quote" class="w-full border-gray-300 rounded" required></textarea>
            </div>
            <div class="mb-4">
                <label for="client_name" class="block text-gray-700">Client Name</label>
                <input type="text" name="client_name" id="client_name" class="w-full border-gray-300 rounded" required>
            </div>
            <div class="mb-4">
                <label for="client_title_company" class="block text-gray-700">Client Title/Company</label>
                <input type="text" name="client_title_company" id="client_title_company" class="w-full border-gray-300 rounded">
            </div>
            <div class="mb-4">
                <label for="client_photo" class="block text-gray-700">Client Photo</label>
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
<div id="edit-testimonial-modal-<?php echo $testimonial['id']; ?>" class="modal fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center">
    <div class="modal-content bg-white p-8 rounded-lg shadow-lg w-1/2">
        <h2 class="text-2xl font-bold mb-4">Edit Testimonial</h2>
        <form action="edit_testimonial.php?id=<?php echo $testimonial['id']; ?>" method="post" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="quote" class="block text-gray-700">Quote</label>
                <textarea name="quote" id="quote" class="w-full border-gray-300 rounded" required><?php echo htmlspecialchars($testimonial['quote']); ?></textarea>
            </div>
            <div class="mb-4">
                <label for="client_name" class="block text-gray-700">Client Name</label>
                <input type="text" name="client_name" id="client_name" class="w-full border-gray-300 rounded" value="<?php echo htmlspecialchars($testimonial['client_name']); ?>" required>
            </div>
            <div class="mb-4">
                <label for="client_title_company" class="block text-gray-700">Client Title/Company</label>
                <input type="text" name="client_title_company" id="client_title_company" class="w-full border-gray-300 rounded" value="<?php echo htmlspecialchars($testimonial['client_title_company']); ?>">
            </div>
            <div class="mb-4">
                <label for="client_photo" class="block text-gray-700">Client Photo</label>
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

<div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40"></div>

<?php
require_once 'includes/footer.php';
?>
