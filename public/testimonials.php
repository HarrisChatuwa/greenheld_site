<?php
require_once '../config/db.php'; // Go up one level to find config

// Fetch all testimonials
try {
    $stmt = $pdo->query("SELECT id, quote, client_name, client_title_company, client_photo_url FROM greenheld.testimonials ORDER BY created_at DESC");
    $testimonials = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching testimonials for testimonials page: " . $e->getMessage());
    $testimonials = [];
    $page_error = "Could not retrieve testimonials at this time. Please try again later.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Testimonials - Greemheld Social Research and Consulting</title>
    <link href="css/style.css" rel="stylesheet">
    <meta name="description" content="Read what our clients say about Greemheld Social Research and Consulting.">
</head>
<body class="bg-neutral-light font-sans text-neutral-dark">
<header class="bg-white shadow-md sticky top-0 z-50">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <a href="index.php" class="text-2xl font-bold text-primary">Greemheld</a>
        <nav class="hidden md:flex space-x-1 items-center">
            <a href="index.php" class="py-2 px-3 text-neutral-default hover:text-primary hover:bg-neutral-light rounded-md transition-all duration-300">Home</a>
            <a href="about.html" class="py-2 px-3 text-neutral-default hover:text-primary hover:bg-neutral-light rounded-md transition-all duration-300">About</a>
            <a href="services.html" class="py-2 px-3 text-neutral-default hover:text-primary hover:bg-neutral-light rounded-md transition-all duration-300">Services</a>
            <a href="projects.php" class="py-2 px-3 text-neutral-default hover:text-primary hover:bg-neutral-light rounded-md transition-all duration-300">Projects</a>
            <a href="testimonials.php" class="py-2 px-3 text-neutral-default hover:text-primary hover:bg-neutral-light rounded-md transition-all duration-300">Testimonials</a>
            <a href="contact.html" class="bg-primary hover:bg-primary-dark text-white font-semibold py-2 px-4 rounded-md transition-all duration-300">Contact Us</a>
        </nav>
        <div class="md:hidden">
            <button id="mobile-menu-button" class="text-neutral-dark focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
        </div>
    </div>
    <!-- Mobile Menu -->
    <div id="mobile-menu" class="md:hidden hidden bg-white shadow-lg py-2">
        <a href="index.php" class="block px-4 py-2 text-neutral-default hover:bg-primary-light hover:text-primary-dark transition duration-300">Home</a>
        <a href="about.html" class="block px-4 py-2 text-neutral-default hover:bg-primary-light hover:text-primary-dark transition duration-300">About</a>
        <a href="services.html" class="block px-4 py-2 text-neutral-default hover:bg-primary-light hover:text-primary-dark transition duration-300">Services</a>
        <a href="projects.php" class="block px-4 py-2 text-neutral-default hover:bg-primary-light hover:text-primary-dark transition duration-300">Projects</a>
        <a href="testimonials.php" class="block px-4 py-2 text-neutral-default hover:bg-primary-light hover:text-primary-dark transition duration-300">Testimonials</a>
        <a href="contact.html" class="block px-4 py-2 text-neutral-default hover:bg-primary-light hover:text-primary-dark transition duration-300">Contact Us</a>
    </div>
</header>

    <main class="py-16 md:py-24 bg-white">
        <div class="container mx-auto px-6 md:px-12">
            <h1 class="text-4xl md:text-5xl font-bold text-primary mb-10 text-center">What Our Valued Clients Say</h1>
            <p class="text-lg text-neutral-default max-w-3xl mx-auto text-center mb-16">
                We cherish the partnerships we build and are proud of the positive feedback received from those we've worked with. These testimonials reflect our commitment to quality, insight, and collaborative success.
            </p>

            <?php if (isset($page_error)): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md text-center" role="alert">
                    <p><?php echo htmlspecialchars($page_error); ?></p>
                </div>
            <?php endif; ?>

            <?php if (empty($testimonials) && !isset($page_error)): ?>
                <p class="text-center text-neutral-default text-xl mt-12" id="no-testimonials-message">
                     We are currently gathering testimonials from our valued clients. Please check back soon!
                </p>
            <?php elseif (!empty($testimonials)): ?>
                <div id="testimonial-list" class="grid md:grid-cols-2 gap-x-8 gap-y-10 max-w-5xl mx-auto">
                    <?php foreach ($testimonials as $testimonial): ?>
                    <div class="bg-white p-8 rounded-xl shadow-xl border border-neutral-200 flex flex-col items-center text-center transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
                        <?php if (!empty($testimonial['client_photo_url'])): ?>
                            <img src="<?php echo htmlspecialchars($testimonial['client_photo_url']); ?>"
                                 alt="Photo of <?php echo htmlspecialchars($testimonial['client_name']); ?>"
                                 class="w-24 h-24 rounded-full object-cover mb-5 shadow-md border-2 border-primary">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/120/808080/ffffff?text=Client"
                                 alt="Client Placeholder"
                                 class="w-24 h-24 rounded-full object-cover mb-5 shadow-md border-2 border-neutral-300">
                        <?php endif; ?>
                        <p class="text-neutral-default italic text-lg leading-relaxed mb-6 flex-grow">"<?php echo nl2br(htmlspecialchars($testimonial['quote'])); ?>"</p>
                        <p class="font-bold text-primary-dark text-xl">- <?php echo htmlspecialchars($testimonial['client_name']); ?></p>
                        <?php if (!empty($testimonial['client_title_company'])): ?>
                        <p class="text-sm text-accent font-medium"><?php echo htmlspecialchars($testimonial['client_title_company']); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

<footer class="bg-neutral-dark text-neutral-light py-12 px-4">
    <div class="container mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
        <div>
            <h3 class="text-xl font-semibold mb-4 text-white">Greemheld</h3>
            <p class="text-sm">
                Placeholder Street Name, City, Postal Code<br>
                Country
            </p>
            <p class="text-sm mt-2">
                Email: <a href="mailto:info@greemheld.com" class="hover:text-primary transition duration-300">info@greemheld.com</a>
            </p>
            <p class="text-sm">
                Phone: <a href="tel:+1234567890" class="hover:text-primary transition duration-300">+1 (234) 567-890</a>
            </p>
        </div>
        <div>
            <h3 class="text-xl font-semibold mb-4 text-white">Quick Links</h3>
            <ul class="space-y-2 text-sm">
                <li><a href="index.php" class="hover:text-primary transition duration-300">Home</a></li>
                <li><a href="about.html" class="hover:text-primary transition duration-300">About Us</a></li>
                <li><a href="services.html" class="hover:text-primary transition duration-300">Services</a></li>
                <li><a href="projects.php" class="hover:text-primary transition duration-300">Projects</a></li>
                <li><a href="testimonials.php" class="hover:text-primary transition duration-300">Testimonials</a></li>
                <li><a href="contact.html" class="hover:text-primary transition duration-300">Contact</a></li>
                <li><a href="#" class="hover:text-primary transition duration-300">Privacy Policy</a></li> <!-- Placeholder -->
                <li><a href="#" class="hover:text-primary transition duration-300">Terms of Service</a></li> <!-- Placeholder -->
            </ul>
        </div>
        <div>
            <h3 class="text-xl font-semibold mb-4 text-white">Connect With Us</h3>
            <!-- Placeholder for social media icons -->
            <div class="flex justify-center md:justify-start space-x-4 mt-2">
                <a href="#" class="hover:text-primary transition duration-300">FB</a>
                <a href="#" class="hover:text-primary transition duration-300">TW</a>
                <a href="#" class="hover:text-primary transition duration-300">LI</a>
            </div>
        </div>
    </div>
    <div class="text-center text-sm mt-10 border-t border-neutral-700 pt-8">
        <p>&copy; <span id="currentYear"></span> Greemheld Social Research and Consulting. All Rights Reserved.</p>
    </div>
</footer>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="js/main.js"></script>
</body>
</html>
