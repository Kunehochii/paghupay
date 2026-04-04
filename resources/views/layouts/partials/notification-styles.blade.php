<style>
    .notification-badge {
        position: absolute;
        top: -2px;
        right: -2px;
        background: #dc3545;
        color: white;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        font-size: 0.7rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .notification-dropdown {
        position: absolute;
        top: 45px;
        right: -60px;
        width: 320px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        z-index: 1000;
        overflow: hidden;
    }

    .notif-header {
        padding: 10px 15px;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .notif-footer {
        padding: 10px 15px;
        border-top: 1px solid #eee;
        text-align: center;
    }

    .notif-footer a {
        color: #3d9f9b;
        text-decoration: none;
        font-size: 0.9rem;
    }

    .notif-list {
        max-height: 300px;
        overflow-y: auto;
    }

    .notif-item {
        padding: 10px 15px;
        border-bottom: 1px solid #f0f0f0;
        cursor: pointer;
        transition: background 0.2s;
    }

    .notif-item:hover {
        background: #f8f9fa;
    }

    .notif-item.unread {
        background: #e7f3ff;
    }
</style>
