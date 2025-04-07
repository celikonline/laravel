import './bootstrap';
import 'bootstrap';

// Bildirim sayısını güncelleme fonksiyonu
function updateNotificationCount() {
    /*fetch('/notifications/unread-count')
        .then(response => response.json())
        .then(data => {
            const badge = document.querySelector('#notificationsDropdown .badge');
            if (badge) {
                if (data.count > 0) {
                    badge.textContent = data.count;
                    badge.style.display = 'inline-block';
                } else {
                    badge.style.display = 'none';
                }
            }
        });*/
}

// Her 30 saniyede bir bildirim sayısını güncelle
//setInterval(updateNotificationCount, 30000);

// Sayfa yüklendiğinde bildirim sayısını güncelle
document.addEventListener('DOMContentLoaded', updateNotificationCount);

// Bildirim işlemleri sonrası sayıyı güncelle
document.addEventListener('click', function(e) {
    if (e.target.matches('.notifications-dropdown form button[type="submit"]')) {
        setTimeout(updateNotificationCount, 1000);
    }
});
