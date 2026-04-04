<script>
(function() {
    const bell = document.getElementById('notificationBell');
    const badge = document.getElementById('notifBadge');
    const dropdown = document.getElementById('notifDropdown');
    const notifList = document.getElementById('notifList');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // Fetch unread count on page load
    fetch('/notifications/unread-count')
        .then(r => r.json())
        .then(data => {
            if (data.count > 0) {
                badge.textContent = data.count > 9 ? '9+' : data.count;
                badge.classList.remove('d-none');
            }
        })
        .catch(() => {});

    // Toggle dropdown
    bell.addEventListener('click', function(e) {
        e.stopPropagation();
        dropdown.classList.toggle('d-none');
        if (!dropdown.classList.contains('d-none')) {
            loadRecentNotifications();
        }
    });

    function loadRecentNotifications() {
        fetch('/notifications/recent')
            .then(r => r.json())
            .then(notifications => {
                if (notifications.length === 0) {
                    notifList.innerHTML = '<p class="text-muted text-center py-3 mb-0">No new notifications</p>';
                    return;
                }
                notifList.innerHTML = notifications.map(n => {
                    const date = new Date(n.created_at);
                    const timeStr = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit' });
                    return `
                        <div class="notif-item unread" data-id="${n.id}" onclick="markNotifRead('${n.id}')">
                            <div style="font-size:0.9rem">${n.data.message}</div>
                            <small class="text-muted">${timeStr}</small>
                        </div>
                    `;
                }).join('');
            })
            .catch(() => {});
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (dropdown && !dropdown.contains(e.target) && e.target !== bell) {
            dropdown.classList.add('d-none');
        }
    });

    // Mark single as read
    window.markNotifRead = function(id) {
        fetch(`/notifications/${id}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        }).then(() => location.reload());
    };

    // Mark all as read
    document.getElementById('markAllRead').addEventListener('click', function() {
        fetch('/notifications/read-all', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        }).then(() => location.reload());
    });
})();
</script>
