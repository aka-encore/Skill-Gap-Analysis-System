/**
 * SkillBridge - Global Application JavaScript UI Engine
 */

function toggleSidebar() {
    const appLayout = document.getElementById('appLayout') || document.querySelector('.dashboard-layout');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    if (window.innerWidth <= 768) {
        if (sidebar) sidebar.classList.toggle('mobile-open');
        if (overlay) overlay.classList.toggle('active');
        if (appLayout) appLayout.classList.toggle('sidebar-open');
    } else {
        if (sidebar) sidebar.classList.toggle('collapsed');
        if (appLayout) appLayout.classList.toggle('sidebar-collapsed');

        const isCollapsed = sidebar ? sidebar.classList.contains('collapsed') : (appLayout && appLayout.classList.contains('sidebar-collapsed'));
        localStorage.setItem('sb_sidebar_collapsed', isCollapsed ? 'true' : 'false');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // 1. Restore Sidebar State Preference on Load (Desktop)
    if (window.innerWidth > 768) {
        const isCollapsed = localStorage.getItem('sb_sidebar_collapsed') === 'true';
        const appLayout = document.getElementById('appLayout') || document.querySelector('.dashboard-layout');
        const sidebar = document.getElementById('sidebar');
        if (isCollapsed) {
            if (sidebar) sidebar.classList.add('collapsed');
            if (appLayout) appLayout.classList.add('sidebar-collapsed');
        }
    }

    // 2. Global Hamburger Menu Listeners
    const sidebarToggleBtn = document.getElementById('sidebarToggle');
    const menuToggleBtn = document.getElementById('menuToggle');
    const sidebarCloseToggleBtn = document.getElementById('sidebarCloseToggle');

    [sidebarToggleBtn, menuToggleBtn, sidebarCloseToggleBtn].forEach(btn => {
        if (btn) {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleSidebar();
            });
        }
    });

    // 2. Table Live Search Helper
    const searchInputs = document.querySelectorAll('[data-search-table]');
    searchInputs.forEach(input => {
        const tableId = input.getAttribute('data-search-table');
        const table = document.getElementById(tableId);
        if (table) {
            input.addEventListener('keyup', function() {
                const term = this.value.toLowerCase();
                const rows = table.querySelectorAll('tbody tr');
                rows.forEach(row => {
                    const text = row.innerText.toLowerCase();
                    row.style.display = text.includes(term) ? '' : 'none';
                });
            });
        }
    });

    // 3. Global Confirmation Dialogs
    const confirmButtons = document.querySelectorAll('[data-confirm]');
    confirmButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            const msg = this.getAttribute('data-confirm') || 'Are you sure you want to proceed?';
            if (!confirm(msg)) {
                e.preventDefault();
            }
        });
    });

    // 4. Auto-Scroll & Highlight Target Section on Hash Navigation
    function handleHashScroll() {
        const hash = window.location.hash;
        if (hash && hash.length > 1) {
            try {
                const targetEl = document.querySelector(hash);
                if (targetEl) {
                    setTimeout(() => {
                        targetEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        targetEl.classList.add('section-target-highlight');
                        setTimeout(() => targetEl.classList.remove('section-target-highlight'), 2500);
                    }, 200);
                }
            } catch(e) {}
        }
    }
    handleHashScroll();
    window.addEventListener('hashchange', handleHashScroll);

    // 5. Dynamic Sidebar Active State Resolution Engine
    updateSidebarActiveState();
    window.addEventListener('popstate', updateSidebarActiveState);
});

/**
 * Dynamic Sidebar Active State Resolution Engine
 * Ensures exactly 1 matching sidebar item is highlighted as active.
 * Excludes logout link. Supports browser back/forward and direct navigation.
 */
function updateSidebarActiveState() {
    const sidebar = document.getElementById('sidebar');
    if (!sidebar) return;

    const navItems = sidebar.querySelectorAll('.sidebar-nav-item');
    if (!navItems.length) return;

    const currentPath = window.location.pathname.toLowerCase().replace(/\\/g, '/');
    const currentFilename = currentPath.split('/').pop() || 'index.php';

    let matchedItem = null;

    navItems.forEach(item => {
        const href = item.getAttribute('href');
        if (!href || href.includes('logout.php')) return;

        try {
            const url = new URL(href, window.location.origin);
            const itemPath = url.pathname.toLowerCase().replace(/\\/g, '/');
            const itemFilename = itemPath.split('/').pop();

            if (itemPath === currentPath) {
                matchedItem = item;
            } else if (itemFilename && itemFilename === currentFilename) {
                matchedItem = item;
            } else if (currentFilename === 'recommendations.php' && itemFilename === 'courses.php') {
                matchedItem = item;
            } else if (['take-assessment.php', 'assessment-result.php'].includes(currentFilename) && itemFilename === 'assessments.php') {
                matchedItem = item;
            } else if (['create-assessment.php', 'edit-assessment.php', 'evaluate.php'].includes(currentFilename) && itemFilename === 'assessments.php') {
                matchedItem = item;
            } else if (currentFilename === 'recommend-courses.php' && itemFilename === 'skill-gap.php') {
                matchedItem = item;
            }
        } catch(e) {}
    });

    if (matchedItem) {
        navItems.forEach(item => item.classList.remove('active'));
        matchedItem.classList.add('active');
    }
}

/**
 * Mark all notifications as read via AJAX
 */
function markAllNotificationsRead() {
    const endpoint = (typeof window.BASE_URL !== 'undefined') ? window.BASE_URL + 'api/notifications.php' : '/api/notifications.php';
    fetch(endpoint, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'action=mark_all_read'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const badge = document.getElementById('notifBadge');
            if (badge) badge.remove();
            document.querySelectorAll('.notification-item.unread').forEach(item => {
                item.classList.remove('unread', 'bg-primary-subtle', 'bg-opacity-10');
                item.classList.add('read');
            });
            const markBtn = document.getElementById('markAllReadBtn');
            if (markBtn) markBtn.remove();
        }
    })
    .catch(err => console.error('Notification error:', err));
}
