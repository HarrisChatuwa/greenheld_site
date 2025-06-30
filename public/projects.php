<?php
require_once '../config/db.php'; // Go up one level to find config

// Fetch all projects
try {
    $stmt = $pdo->query("SELECT id, title, description, image_url, outcome FROM projects ORDER BY created_at DESC");
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching projects for projects page: " . $e->getMessage());
    $projects = [];
    $page_error = "Could not retrieve projects at this time. Please try again later.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Projects - Greemheld Social Research and Consulting</title>
    <link href="css/style.css" rel="stylesheet">
    <meta name="description" content="View past and ongoing projects by Greemheld Social Research and Consulting.">
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
            <h1 class="text-4xl md:text-5xl font-bold text-primary mb-10 text-center">Our Impactful Projects</h1>
            <p class="text-lg text-neutral-default max-w-3xl mx-auto text-center mb-16">
                We take pride in our diverse portfolio of projects, showcasing our commitment to delivering impactful research and consulting solutions. Below are some examples of our work that highlight the breadth of our expertise and our dedication to client success.
            </p>

            <?php if (isset($page_error)): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md text-center" role="alert">
                    <p><?php echo htmlspecialchars($page_error); ?></p>
                </div>
            <?php endif; ?>

            <?php if (empty($projects) && !isset($page_error)): ?>
                <p class="text-center text-neutral-default text-xl mt-12" id="no-projects-message">
                    Currently, there are no projects to display. Please check back later or contact us for more information on our work.
                </p>
            <?php elseif (!empty($projects)): ?>
                <div id="project-list" class="grid md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12">
                    <?php foreach ($projects as $project): ?>
                    <div id="project-<?php echo htmlspecialchars($project['id']); ?>" class="bg-white rounded-xl shadow-xl overflow-hidden flex flex-col transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-1">
                        <img src="<?php echo htmlspecialchars(empty($project['image_url']) ? 'https://via.placeholder.com/600x400/06b6d4/ffffff?text=Project+Image' : $project['image_url']); ?>"
                             alt="<?php echo htmlspecialchars($project['title']); ?>" class="w-full h-56 object-cover">
                        <div class="p-6 flex flex-col flex-grow">
                            <h2 class="text-2xl font-semibold text-primary-dark mb-3"><?php echo htmlspecialchars($project['title']); ?></h2>
                            <p class="text-neutral-default text-base leading-relaxed flex-grow mb-4">
                                <?php echo nl2br(htmlspecialchars($project['description'])); ?>
                            </p>
                            <?php if (!empty($project['outcome'])): ?>
                            <div class="mt-auto pt-4 border-t border-neutral-200">
                                <h4 class="text-md font-semibold text-primary mb-1">Key Outcome:</h4>
                                <p class="text-sm text-neutral-600 leading-relaxed"><?php echo nl2br(htmlspecialchars($project['outcome'])); ?></p>
                            </div>
                            <?php endif; ?>
                            <!-- <a href="project_single.php?id=<?php echo $project['id']; ?>" class="mt-5 bg-primary hover:bg-primary-dark text-white font-semibold py-2 px-4 rounded-md text-sm transition duration-300 self-start">Read More</a> -->
                        </div>
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
