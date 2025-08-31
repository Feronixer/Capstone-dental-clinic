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
console.log("Announcement page loaded.");
