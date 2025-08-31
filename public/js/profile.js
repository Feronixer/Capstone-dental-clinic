document.addEventListener('DOMContentLoaded', function () {
    const mobileMenuButton = document.getElementById('mobile-menu-button-patient-account');
    const mobileMenu = document.getElementById('mobile-menu-patient-account');
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }
    const notificationBellBtn = document.getElementById('notificationBellBtn');
    const notificationPopout = document.getElementById('notificationPopout');
    if (notificationBellBtn && notificationPopout) {
        notificationBellBtn.addEventListener('click', function(event) {
            event.stopPropagation();
            notificationPopout.style.display = notificationPopout.style.display === 'block' ? 'none' : 'block';
        });
        document.addEventListener('click', function(event) {
            if (notificationPopout.style.display === 'block' && !notificationPopout.contains(event.target) && !notificationBellBtn.contains(event.target)) {
                notificationPopout.style.display = 'none';
            }
        });
    }
});
