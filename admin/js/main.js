document.addEventListener('DOMContentLoaded', function () {
    // Sidebar toggle
    const sidebar = document.getElementById('sidebar');
    const openSidebarButton = document.getElementById('open-sidebar');
    const closeSidebarButton = document.getElementById('close-sidebar');
    const sidebarOverlay = document.getElementById('sidebar-overlay');

    function openSidebar() {
        if (sidebar && sidebarOverlay) {
            sidebar.classList.remove('-translate-x-full');
            sidebarOverlay.classList.remove('hidden');
        }
    }

    function closeSidebar() {
        if (sidebar && sidebarOverlay) {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        }
    }

    if (openSidebarButton) {
        openSidebarButton.addEventListener('click', openSidebar);
    }
    if (closeSidebarButton) {
        closeSidebarButton.addEventListener('click', closeSidebar);
    }
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', closeSidebar);
    }

    // Modal toggle
    const openModalButtons = document.querySelectorAll('[data-modal-target]');
    const closeModalButtons = document.querySelectorAll('[data-modal-close]');
    const overlay = document.getElementById('overlay');

    openModalButtons.forEach(button => {
        button.addEventListener('click', () => {
            const modal = document.querySelector(button.dataset.modalTarget);
            openModal(modal);
        });
    });

    if (overlay) {
        overlay.addEventListener('click', () => {
            const modals = document.querySelectorAll('.modal.flex');
            modals.forEach(modal => {
                closeModal(modal);
            });
        });
    }

    closeModalButtons.forEach(button => {
        button.addEventListener('click', () => {
            const modal = button.closest('.modal');
            closeModal(modal);
        });
    });

    function openModal(modal) {
        if (modal == null) return;
        modal.classList.remove('hidden');
        modal.classList.add('flex'); // Use flex to center content
        if (overlay) {
            overlay.classList.remove('hidden');
        }
    }

    function closeModal(modal) {
        if (modal == null) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        if (overlay) {
            overlay.classList.add('hidden');
        }
    }
});