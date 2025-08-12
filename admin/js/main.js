document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const openSidebarButton = document.getElementById('open-sidebar');
    const closeSidebarButton = document.getElementById('close-sidebar');

    if (openSidebarButton) {
        openSidebarButton.addEventListener('click', function () {
            sidebar.classList.remove('-translate-x-full');
        });
    }

    if (closeSidebarButton) {
        closeSidebarButton.addEventListener('click', function () {
            sidebar.classList.add('-translate-x-full');
        });
    }
});