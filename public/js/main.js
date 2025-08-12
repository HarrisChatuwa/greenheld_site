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
    if (currentPage === 'index.php' || currentPage === '') {
        document.getElementById('nav-home').classList.add('bg-neutral-light', 'text-primary');
    } else if (currentPage === 'about.php') {
        document.getElementById('nav-about').classList.add('bg-neutral-light', 'text-primary');
    } else if (currentPage === 'services.html') {
        document.getElementById('nav-services').classList.add('bg-neutral-light', 'text-primary');
    } else if (currentPage === 'projects.php') {
        document.getElementById('nav-projects').classList.add('bg-neutral-light', 'text-primary');
    } else if (currentPage === 'testimonials.php') {
        document.getElementById('nav-testimonials').classList.add('bg-neutral-light', 'text-primary');
    } else if (currentPage === 'contact.html') {
        // The contact link is a button, so we don't highlight it in the same way
    }
});