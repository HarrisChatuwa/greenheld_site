<?php
require_once '../config/db.php'; // Go up one level to find config

// Fetch latest 3 projects
try {
    $stmt_projects = $pdo->query("SELECT id, title, LEFT(description, 120) AS description_snippet, image_url FROM greenheld.projects ORDER BY created_at DESC LIMIT 3");
    $featured_projects = $stmt_projects->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching featured projects: " . $e->getMessage());
    $featured_projects = []; // Default to empty array on error
}

// Fetch latest 2 testimonials
try {
    $stmt_testimonials = $pdo->query("SELECT quote, client_name, client_title_company FROM greenheld.testimonials ORDER BY created_at DESC LIMIT 2");
    $featured_testimonials = $stmt_testimonials->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching featured testimonials: " . $e->getMessage());
    $featured_testimonials = []; // Default to empty array on error
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>greenheld Social Research and Consulting - Home</title>
    <link href="css/style.css" rel="stylesheet">
    <meta name="description" content="greenheld Social Research and Consulting offers expert social research services.">
</head>
<body class="bg-neutral-light font-sans text-neutral-dark">
<header class="bg-white shadow-md sticky top-0 z-50">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <a href="index.php" class="text-2xl font-bold text-primary">Greenheld</a>
        <nav class="hidden md:flex space-x-1 items-center">
            <a href="index.php" class="py-2 px-3 text-neutral-default hover:text-primary hover:bg-neutral-light rounded-md transition-all duration-300">Home</a>
            <a href="about.php" class="py-2 px-3 text-neutral-default hover:text-primary hover:bg-neutral-light rounded-md transition-all duration-300">About</a>
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
        <a href="about.php" class="block px-4 py-2 text-neutral-default hover:bg-primary-light hover:text-primary-dark transition duration-300">About</a>
        <a href="services.html" class="block px-4 py-2 text-neutral-default hover:bg-primary-light hover:text-primary-dark transition duration-300">Services</a>
        <a href="projects.php" class="block px-4 py-2 text-neutral-default hover:bg-primary-light hover:text-primary-dark transition duration-300">Projects</a>
        <a href="testimonials.php" class="block px-4 py-2 text-neutral-default hover:bg-primary-light hover:text-primary-dark transition duration-300">Testimonials</a>
        <a href="contact.html" class="block px-4 py-2 text-neutral-default hover:bg-primary-light hover:text-primary-dark transition duration-300">Contact Us</a>
    </div>
</header>

    <main>
        <!-- Hero Section -->
        <section id="hero" class="bg-primary text-white text-center py-20 md:py-32 px-4 relative overflow-hidden">
            <div class="absolute inset-0 bg-black opacity-30"></div> <!-- Optional: Dark overlay for better text contrast if using a background image -->
            <div class="relative z-10 container mx-auto">
                <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold mb-6 leading-tight">Empowering Decisions Through Insightful Social Research.</h1>
                <p class="text-lg sm:text-xl md:text-2xl mb-10 max-w-3xl mx-auto">Transforming data into actionable strategies for sustainable development and social impact.</p>
                <a href="#services-overview" class="bg-accent hover:bg-pink-600 text-white font-bold py-3 px-8 rounded-full text-lg transition duration-300 transform hover:scale-105">Discover Our Expertise</a>
            </div>
        </section>

        <!-- Brief Introduction/About Section -->
        <section id="intro" class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-6 md:px-12 text-center">
                <h2 class="text-3xl md:text-4xl font-semibold text-primary-dark mb-6">Welcome to Greenheld</h2>
                <p class="text-neutral-default text-lg md:text-xl leading-relaxed max-w-3xl mx-auto mb-8">
                    greenheld Social Research and Consulting is a leading firm dedicated to providing comprehensive research, monitoring, and evaluation services. We empower NGOs, government agencies, and private sector organizations with data-driven insights to achieve their social impact goals.
                </p>
                <a href="about.php" class="text-accent hover:underline font-semibold text-lg">Learn More About Us</a>
            </div>
        </section>

        <!-- Key Services Overview -->
        <section id="services-overview" class="py-16 md:py-24 bg-neutral-light">
            <div class="container mx-auto px-6 md:px-12">
                <h2 class="text-3xl md:text-4xl font-semibold text-primary-dark mb-12 text-center">Our Core Expertise</h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Service 1 -->
                    <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-2xl transition-shadow duration-300 flex flex-col">
                        <h3 class="text-2xl font-semibold text-primary mb-4">Baseline & Endline Surveys</h3>
                        <p class="text-neutral-default mb-6 flex-grow">Robust data collection and analysis to establish project benchmarks and measure impact.</p>
                        <a href="services.html#baseline-surveys" class="text-accent hover:underline font-medium self-start">Learn More &raquo;</a>
                    </div>
                    <!-- Service 2 -->
                    <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-2xl transition-shadow duration-300 flex flex-col">
                        <h3 class="text-2xl font-semibold text-primary mb-4">Feasibility Studies</h3>
                        <p class="text-neutral-default mb-6 flex-grow">Assessing the viability of new initiatives and interventions for sustainable success.</p>
                        <a href="services.html#feasibility-studies" class="text-accent hover:underline font-medium self-start">Learn More &raquo;</a>
                    </div>
                    <!-- Service 3 -->
                    <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-2xl transition-shadow duration-300 flex flex-col">
                        <h3 class="text-2xl font-semibold text-primary mb-4">Monitoring & Evaluation</h3>
                        <p class="text-neutral-default mb-6 flex-grow">Continuous tracking and assessment to ensure project effectiveness and accountability.</p>
                        <a href="services.html#monitoring-evaluation" class="text-accent hover:underline font-medium self-start">Learn More &raquo;</a>
                    </div>
                    <!-- Service 4 -->
                    <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-2xl transition-shadow duration-300 flex flex-col">
                        <h3 class="text-2xl font-semibold text-primary mb-4">Capacity Building</h3>
                        <p class="text-neutral-default mb-6 flex-grow">Strengthening organizational and individual capabilities in research methodologies.</p>
                        <a href="services.html#capacity-building" class="text-accent hover:underline font-medium self-start">Learn More &raquo;</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Projects (Placeholder) -->
        <section id="featured-projects" class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-6 md:px-12 text-center">
                <h2 class="text-3xl md:text-4xl font-semibold text-primary-dark mb-6">Our Impactful Projects</h2>
                <p class="text-neutral-default text-lg mb-12 max-w-2xl mx-auto">We're proud to have partnered with diverse organizations to deliver meaningful results. Here’s a snapshot of our work.</p>
                <?php if (!empty($featured_projects)): ?>
                <div class="grid md:grid-cols-3 gap-8 mb-10">
                    <?php foreach ($featured_projects as $project): ?>
                    <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 text-left flex flex-col">
                        <img src="../<?php echo htmlspecialchars(empty($project['image_url']) ? 'https://via.placeholder.com/400x250/06b6d4/ffffff?text=Project+Image' : $project['image_url']); ?>"
                             alt="<?php echo htmlspecialchars($project['title']); ?>" class="w-full h-48 object-cover rounded-md mb-4">
                        <h3 class="text-xl font-semibold text-primary mb-2"><?php echo htmlspecialchars($project['title']); ?></h3>
                        <p class="text-sm text-neutral-default flex-grow mb-4"><?php echo htmlspecialchars($project['description_snippet']); ?>...</p>
                        <a href="projects.php#project-<?php echo $project['id']; ?>" class="bg-primary hover:bg-primary-dark text-white font-semibold py-2 px-4 rounded-md text-sm transition duration-300 self-start">Explore Project</a>
                    </div>
                    <?php endforeach; ?>
                </div>
                <a href="projects.php" class="bg-primary hover:bg-primary-dark text-white font-semibold py-3 px-6 rounded-md text-sm transition duration-300">View All Projects</a>
                <?php else: ?>
                    <p class="text-neutral-default text-center">No projects to display at the moment. Please check back soon!</p>
                <?php endif; ?>
            </div>
        </section>

        <!-- Testimonials Snippets (Placeholder) -->
        <section id="testimonials-snippets" class="py-16 md:py-24 bg-neutral-light">
            <div class="container mx-auto px-6 md:px-12">
                <h2 class="text-3xl md:text-4xl font-semibold text-primary-dark mb-12 text-center">What Our Clients Say</h2>
                <?php if (!empty($featured_testimonials)): ?>
                <div class="grid md:grid-cols-<?php echo count($featured_testimonials) === 1 ? '1' : '2'; ?> gap-8 max-w-4xl mx-auto">
                    <?php foreach ($featured_testimonials as $testimonial): ?>
                    <div class="bg-white p-8 rounded-xl shadow-lg">
                        <p class="text-neutral-default italic mb-4 text-lg">"<?php echo nl2br(htmlspecialchars($testimonial['quote'])); ?>"</p>
                        <p class="font-semibold text-primary">- <?php echo htmlspecialchars($testimonial['client_name']); ?></p>
                        <?php if (!empty($testimonial['client_title_company'])): ?>
                        <p class="text-sm text-neutral-500"><?php echo htmlspecialchars($testimonial['client_title_company']); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="text-center mt-12">
                    <a href="testimonials.php" class="text-accent hover:underline font-semibold text-lg">Read More Testimonials</a>
                </div>
                <?php else: ?>
                    <p class="text-neutral-default text-center">We are currently gathering feedback from our valued clients.</p>
                <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Secondary CTA Section -->
        <section id="secondary-cta" class="py-16 md:py-24 bg-primary text-white">
            <div class="container mx-auto px-6 md:px-12 text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-6">Ready to Drive Change?</h2>
                <p class="text-lg md:text-xl mb-10 max-w-2xl mx-auto">Let's discuss how greenheld can assist you in achieving your research and consulting objectives. Contact us today for a consultation.</p>
                <a href="contact.html" class="bg-accent hover:bg-pink-600 text-white font-bold py-3 px-8 rounded-full text-lg transition duration-300 transform hover:scale-105">Contact Us Today</a>
            </div>
        </section>
    </main>

<footer class="bg-neutral-dark text-neutral-light py-12 px-4">
    <div class="container mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
        <div>
            <h3 class="text-xl font-semibold mb-4 text-white">greenheld</h3>
            <p class="text-sm">
                Placeholder Street Name, City, Postal Code<br>
                Country
            </p>
            <p class="text-sm mt-2">
                Email: <a href="mailto:info@greenheld.com" class="hover:text-primary transition duration-300">info@greenheld.com</a>
            </p>
            <p class="text-sm">
                Phone: <a href="tel:+1234567890" class="hover:text-primary transition duration-300">+1 (234) 567-890</a>
            </p>
        </div>
        <div>
            <h3 class="text-xl font-semibold mb-4 text-white">Quick Links</h3>
            <ul class="space-y-2 text-sm">
                <li><a href="index.php" class="hover:text-primary transition duration-300">Home</a></li>
                <li><a href="about.php" class="hover:text-primary transition duration-300">About Us</a></li>
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
        <p>&copy; <span id="currentYear"></span> greenheld Social Research and Consulting. All Rights Reserved.</p>
    </div>
</footer>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="js/main.js"></script>
</body>
</html>
