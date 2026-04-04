<div class="nav-notification-wrapper" style="position: relative; display: inline-flex; margin: 0 5px;">
    <button type="button" class="nav-link-custom" id="notificationBell" title="Notifications"
            style="border: none; cursor: pointer; position: relative;">
        <i class="bi bi-bell-fill"></i>
        <span class="notification-badge d-none" id="notifBadge">0</span>
    </button>
    <div class="notification-dropdown d-none" id="notifDropdown">
        <div class="notif-header">
            <strong>Notifications</strong>
            <button type="button" class="btn btn-sm btn-link p-0" id="markAllRead">Mark all read</button>
        </div>
        <div class="notif-list" id="notifList">
            <p class="text-muted text-center py-3 mb-0">No new notifications</p>
        </div>
        <div class="notif-footer">
            <a href="{{ route('notifications.index') }}">View all notifications</a>
        </div>
    </div>
</div>
