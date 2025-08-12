document.addEventListener('DOMContentLoaded', function () {
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    if (mobileMenuButton) {
        mobileMenuButton.addEventListener('click', function () {
            mobileMenu.classList.toggle('hidden');
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
});
