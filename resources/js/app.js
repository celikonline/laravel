import './bootstrap';
import 'bootstrap';

// Sayfa yüklendiğinde çalışacak fonksiyonlar
window.onPageLoad = function() {
    // Bildirim sayısını güncelle
    updateNotificationCount();
    
    // Bootstrap tooltip'leri aktifleştir
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Bootstrap popover'ları aktifleştir
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
};

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
document.addEventListener('DOMContentLoaded', function() {
    window.onPageLoad();
});

// Bildirim işlemleri sonrası sayıyı güncelle
document.addEventListener('click', function(e) {
    if (e.target.matches('.notifications-dropdown form button[type="submit"]')) {
        setTimeout(updateNotificationCount, 1000);
    }
});
