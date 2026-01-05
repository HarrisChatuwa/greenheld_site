document.addEventListener('DOMContentLoaded', function () {
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    if (mobileMenuButton) {
        mobileMenuButton.addEventListener('click', function () {
            mobileMenu.classList.toggle('hidden');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const isClickInside = mobileMenuButton.contains(event.target) || mobileMenu.contains(event.target);
            if (!isClickInside && !mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.add('hidden');
            }
        });
    }

    // Active navigation link
    const currentPage = window.location.pathname.split('/').pop();
    let activeLink = null;
    let activeMobileLink = null;

    if (currentPage === 'index.php' || currentPage === '') {
        activeLink = document.getElementById('nav-home');
        activeMobileLink = document.getElementById('nav-home-mobile');
    } else if (currentPage === 'about.php') {
        activeLink = document.getElementById('nav-about');
        activeMobileLink = document.getElementById('nav-about-mobile');
    } else if (currentPage === 'services.html') {
        activeLink = document.getElementById('nav-services');
        activeMobileLink = document.getElementById('nav-services-mobile');
    } else if (currentPage === 'projects.php') {
        activeLink = document.getElementById('nav-projects');
        activeMobileLink = document.getElementById('nav-projects-mobile');
    } else if (currentPage === 'testimonials.php') {
        activeLink = document.getElementById('nav-testimonials');
        activeMobileLink = document.getElementById('nav-testimonials-mobile');
    } else if (currentPage === 'contact.html') {
        activeLink = document.getElementById('nav-contact');
        activeMobileLink = document.getElementById('nav-contact-mobile');
    }

    if (activeLink) {
        activeLink.classList.add('bg-neutral-light', 'text-primary');
    }
    if (activeMobileLink) {
        activeMobileLink.classList.add('bg-primary-light', 'text-primary-dark');
    }

    // Set current year in footer
    const currentYearElement = document.getElementById('currentYear');
    if (currentYearElement) {
        currentYearElement.textContent = new Date().getFullYear();
    }

    // Intersection Observer for fade-in animations on scroll
    const observerOptions = {
        root: null,
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const fadeInObserver = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in-up');
                fadeInObserver.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe sections and cards for fade-in effect
    const elementsToAnimate = document.querySelectorAll('section, .bg-white.p-8, .bg-white.p-6');
    elementsToAnimate.forEach(el => {
        if (!el.closest('header') && !el.closest('footer')) {
            fadeInObserver.observe(el);
        }
    });

    // Add lazy loading to images below the fold
    const images = document.querySelectorAll('img:not([loading])');
    images.forEach((img, index) => {
        // Skip images in the hero and first section
        if (index > 2) {
            img.setAttribute('loading', 'lazy');
        }
    });
});
