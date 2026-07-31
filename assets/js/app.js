/**
 * SkillBridge - Global Application JavaScript UI Engine
 */

window.debounce = function(func, wait) {
    let timeout;
    return function(...args) {
        const context = this;
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(context, args), wait);
    };
};

function toggleSidebar() {
    const appLayout = document.getElementById('appLayout') || document.querySelector('.dashboard-layout');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    if (window.innerWidth < 992) {
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

function closeMobileSidebar() {
    if (window.innerWidth < 992) {
        const appLayout = document.getElementById('appLayout') || document.querySelector('.dashboard-layout');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        if (sidebar) sidebar.classList.remove('mobile-open');
        if (overlay) overlay.classList.remove('active');
        if (appLayout) appLayout.classList.remove('sidebar-open');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Restore Sidebar State Preference on Load (Desktop)
    if (window.innerWidth >= 992) {
        const isCollapsed = localStorage.getItem('sb_sidebar_collapsed') === 'true';
        const appLayout = document.getElementById('appLayout') || document.querySelector('.dashboard-layout');
        const sidebar = document.getElementById('sidebar');
        if (isCollapsed) {
            if (sidebar) sidebar.classList.add('collapsed');
            if (appLayout) appLayout.classList.add('sidebar-collapsed');
        }
    }

    // Close mobile drawer on Escape key or navigation clicks
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeMobileSidebar();
        }
    });

    document.querySelectorAll('.sidebar-nav-item, .sidebar-brand a').forEach(link => {
        link.addEventListener('click', function() {
            closeMobileSidebar();
        });
    });

    // 1.1 Restore Sidebar Scroll Position (Student, Faculty, Admin)
    const sidebar = document.getElementById('sidebar');
    const sidebarNav = document.querySelector('.sidebar-nav');
    let isRestoring = true;

    function saveSidebarScroll() {
        if (isRestoring) return; // Do not save during initial restoration phase
        if (sidebar) {
            sessionStorage.setItem('sb_sidebar_scroll', sidebar.scrollTop);
            console.log(`Saving sidebar scroll: ${sidebar.scrollTop}`);
        }
        if (sidebarNav) {
            sessionStorage.setItem('sb_sidebar_nav_scroll', sidebarNav.scrollTop);
            console.log(`Saving sidebar-nav scroll: ${sidebarNav.scrollTop}`);
        }
    }

    function restoreSidebarScroll() {
        const savedScroll = sessionStorage.getItem('sb_sidebar_scroll');
        const savedNavScroll = sessionStorage.getItem('sb_sidebar_nav_scroll');

        if (savedScroll !== null && sidebar) {
            sidebar.scrollTop = parseInt(savedScroll, 10);
            console.log(`Restoring sidebar scroll: ${savedScroll}`);
            console.log(`Current scrollTop after restore: ${sidebar.scrollTop}`);
        }
        if (savedNavScroll !== null && sidebarNav) {
            sidebarNav.scrollTop = parseInt(savedNavScroll, 10);
            console.log(`Restoring sidebar scroll (nav): ${savedNavScroll}`);
            console.log(`Current scrollTop after restore (nav): ${sidebarNav.scrollTop}`);
        }
    }

    if (sidebar || sidebarNav) {
        // Restore scroll position repeatedly across animation frames and layout updates
        requestAnimationFrame(() => {
            restoreSidebarScroll();
            
            requestAnimationFrame(() => {
                restoreSidebarScroll();
                
                setTimeout(() => {
                    restoreSidebarScroll();
                    isRestoring = false; // Restoration phase complete, safe to track user scrolling
                }, 150);

                // Final debug log after 500ms
                setTimeout(() => {
                    if (sidebar) console.log(`Final scrollTop after 500ms: ${sidebar.scrollTop}`);
                    if (sidebarNav) console.log(`Final scrollTop after 500ms (nav): ${sidebarNav.scrollTop}`);
                }, 500);
            });
        });

        // Save scroll positions on scroll events
        if (sidebar) {
            sidebar.addEventListener('scroll', saveSidebarScroll);
        }
        if (sidebarNav) {
            sidebarNav.addEventListener('scroll', saveSidebarScroll);
        }

        // Backup: Save scroll position on sidebar link clicks
        document.querySelectorAll('#sidebar a, #sidebar button').forEach(el => {
            el.addEventListener('click', function() {
                const wasRestoring = isRestoring;
                isRestoring = false;
                saveSidebarScroll();
                isRestoring = wasRestoring;
            });
        });
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
            input.addEventListener('keyup', window.debounce(function() {
                const term = this.value.toLowerCase();
                const rows = table.querySelectorAll('tbody tr');
                rows.forEach(row => {
                    const text = row.innerText.toLowerCase();
                    row.style.display = text.includes(term) ? '' : 'none';
                });
            }, 250));
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

    // 5b. Run page-specific chart/widget initializers on direct page load.
    // For PJAX navigation, loadPjaxPage() calls runPageSpecificInitializer() after
    // executePageScripts(). For direct loads/refreshes, we call it here so the
    // single source of truth covers both paths.
    runPageSpecificInitializer();

    // 6. Secure PJAX Navigation Engine
    if (!document.getElementById('pjax-loader-style')) {
        const loaderStyle = document.createElement('style');
        loaderStyle.id = 'pjax-loader-style';
        loaderStyle.textContent = `
            #pjax-loader-bar {
                position: fixed;
                top: 0;
                left: 0;
                height: 3px;
                background: var(--primary, #26658C);
                z-index: 9999;
                width: 0;
                transition: width 0.3s ease-out, opacity 0.3s ease;
                opacity: 0;
                pointer-events: none;
            }
            .content-area-pjax {
                transition: opacity 0.12s ease-in-out;
            }
            .content-area-pjax.loading {
                opacity: 0.15;
            }
        `;
        document.head.appendChild(loaderStyle);
    }

    const contentArea = document.querySelector('main.content-area');
    if (contentArea) {
        contentArea.classList.add('content-area-pjax');
    }

    function showPjaxLoader() {
        let loader = document.getElementById('pjax-loader-bar');
        if (!loader) {
            loader = document.createElement('div');
            loader.id = 'pjax-loader-bar';
            document.body.appendChild(loader);
        }
        loader.style.width = '0%';
        loader.style.opacity = '1';
        setTimeout(() => {
            loader.style.width = '70%';
        }, 10);
        
        if (contentArea) {
            contentArea.classList.add('loading');
        }
    }

    function hidePjaxLoader() {
        const loader = document.getElementById('pjax-loader-bar');
        if (loader) {
            loader.style.width = '100%';
            setTimeout(() => {
                loader.style.opacity = '0';
                setTimeout(() => {
                    loader.style.width = '0%';
                }, 300);
            }, 100);
        }
        if (contentArea) {
            contentArea.classList.remove('loading');
        }
    }

    function purgeActiveTrackers() {
        if (window.ytInterval) clearInterval(window.ytInterval);
        if (window.readingTimer) clearInterval(window.readingTimer);
        
        if (typeof ytInterval !== 'undefined') clearInterval(ytInterval);
        if (typeof readingTimer !== 'undefined') clearInterval(readingTimer);
    }

    function runPageSpecificInitializer() {
        const path = window.location.pathname.toLowerCase().replace(/\\/g, '/');
        const fn = path.split('/').pop() || 'index.php';
        const isStudent = path.includes('/student/');
        const isFaculty = path.includes('/faculty/');
        const isAdmin   = path.includes('/admin/');

        // Helper — call named window function only if it exists, safely caught
        const call = (name) => {
            try {
                if (typeof window[name] === 'function') {
                    window[name]();
                }
            } catch (err) {
                console.error(`Error initializing page component [${name}]:`, err);
            }
        };

        // Dispatch table covers every Faculty, Admin, and Student page.
        // Pages that don't define a window.initXxx are no-ops (call() does nothing).
        switch (fn) {
            // ── Dashboards ─────────────────────────────────────────────────
            case 'dashboard.php':
                if (isStudent)     call('initDashboard');
                else if (isFaculty) call('initFacultyDashboard');
                else if (isAdmin)   call('initAdminDashboard');
                break;

            // ── Skill Gap Analytics ────────────────────────────────────────
            case 'skill-gap.php':
                if (isStudent)     call('initSkillGap');
                else if (isFaculty) call('initFacultySkillGap');
                break;

            // ── Admin Analytics / Charts ───────────────────────────────────
            case 'analytics.php':
                if (isAdmin) call('initAdminAnalytics');
                break;

            // ── Shared Pages (context determined internally if needed) ──────
            case 'courses.php':           call('initCourses');          break;
            case 'roadmap.php':           call('initRoadmap');          break;
            case 'progress.php':          call('initProgress');         break;
            case 'profile.php':           call('initProfile');          break;
            case 'help.php':              call('initHelp');              break;
            case 'notifications.php':     call('initNotifications');    break;
            case 'assessments.php':
                if (isFaculty) call('initFacultyAssessments');
                else if (isAdmin) call('initAdminAssessments');
                break;
            case 'question-bank.php':     call('initQuestionBank');     break;
            case 'evaluate.php':          call('initEvaluate');         break;
            case 'students.php':
                if (isFaculty) call('initFacultyStudents');
                else if (isAdmin) call('initAdminStudents');
                break;
            case 'feedback.php':
                if (isStudent)     call('initFeedback');
                else if (isFaculty) call('initFacultyFeedback');
                break;
            case 'announce.php':
            case 'announcements.php':     call('initAnnouncements');   break;
            case 'proctoring-report.php': call('initProctoringReport'); break;
            case 'reports.php':           call('initReports');          break;
            case 'faculty.php':           call('initAdminFaculty');     break;
            case 'activity-logs.php':     call('initActivityLogs');     break;
            case 'skills.php':            call('initSkills');           break;

            default: break;
        }
    }

    function loadPjaxPage(url, pushToHistory = true) {
        showPjaxLoader();
        purgeActiveTrackers();

        fetch(url)
        .then(response => {
            if (response.redirected) {
                window.location.href = response.url;
                return;
            }
            if (!response.ok) {
                throw new Error(`Response status error: ${response.status}`);
            }
            return response.text();
        })
        .then(html => {
            if (!html) {
                throw new Error('Received empty HTML response');
            }

            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContent = doc.querySelector('main.content-area');

            if (!newContent) {
                throw new Error('Target content area (main.content-area) not found in response');
            }

            if (pushToHistory) {
                history.pushState({ pjax: true, url: url }, '', url);
            }

            document.title = doc.title;

            if (contentArea) {
                contentArea.innerHTML = newContent.innerHTML;
                bindGlobalListeners();
                executePageScripts(contentArea);
                runPageSpecificInitializer();
            }

            updateSidebarActiveState();

            // Check for open_announcement_id in PJAX page loads
            try {
                const urlParams = new URL(url).searchParams;
                const openAnnId = urlParams.get('open_announcement_id');
                if (openAnnId) {
                    window.openAnnouncementModal(openAnnId);
                }
            } catch(e) {}
        })
        .catch(err => {
            console.error('PJAX load error:', err);
            window.location.href = url;
        })
        .finally(() => {
            hidePjaxLoader();
        });
    }

    function executePageScripts(container) {
        const scripts = container.querySelectorAll('script');
        scripts.forEach(oldScript => {
            // Skip external src scripts — they are loaded globally via footer.php
            // Re-injecting them via PJAX causes async race conditions (inline init
            // runs before the externally fetched script finishes loading).
            if (oldScript.src && oldScript.src.length > 0) {
                oldScript.remove();
                return;
            }
            try {
                const newScript = document.createElement('script');
                Array.from(oldScript.attributes).forEach(attr => {
                    newScript.setAttribute(attr.name, attr.value);
                });
                newScript.textContent = oldScript.textContent;
                oldScript.parentNode.replaceChild(newScript, oldScript);
            } catch (err) {
                console.error('Error executing page script:', err, oldScript.textContent);
            }
        });
    }

    function bindGlobalListeners() {
        const searchInputs = document.querySelectorAll('[data-search-table]');
        searchInputs.forEach(input => {
            const tableId = input.getAttribute('data-search-table');
            const table = document.getElementById(tableId);
            if (table) {
                const newEl = input.cloneNode(true);
                input.parentNode.replaceChild(newEl, input);
                
                newEl.addEventListener('keyup', window.debounce(function() {
                    const term = this.value.toLowerCase();
                    const rows = table.querySelectorAll('tbody tr');
                    rows.forEach(row => {
                        const text = row.innerText.toLowerCase();
                        row.style.display = text.includes(term) ? '' : 'none';
                    });
                }, 250));
            }
        });

        document.querySelectorAll('[data-confirm]').forEach(btn => {
            const newBtn = btn.cloneNode(true);
            btn.parentNode.replaceChild(newBtn, btn);
            newBtn.addEventListener('click', function(e) {
                const msg = this.getAttribute('data-confirm') || 'Are you sure you want to proceed?';
                if (!confirm(msg)) {
                    e.preventDefault();
                }
            });
        });
        
        handleHashScroll();
    }

    document.addEventListener('click', function(e) {
        // Intercept any local <a> link click
        const link = e.target.closest('a');
        if (!link) return;

        const href = link.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('javascript:') || link.getAttribute('target') === '_blank') return;
        if (link.hasAttribute('download') || link.classList.contains('no-pjax') || link.closest('[data-no-pjax]')) return;

        // Exclude authentication, quiz, or custom pages with complex state
        const excludeList = [
            'logout.php', 'login.php', 'register.php', 'forgot-password.php', 'reset-password.php',
            'take-assessment.php', 'assessment-result.php'
        ];
        
        const isExcluded = excludeList.some(ex => href.toLowerCase().includes(ex));
        if (isExcluded) return;

        try {
            const url = new URL(link.href);
            if (url.origin !== window.location.origin) return;

            e.preventDefault();
            
            const sidebar = document.getElementById('sidebar');
            if (sidebar && sidebar.classList.contains('mobile-open')) {
                toggleSidebar();
            }

            loadPjaxPage(link.href, true);
        } catch (err) {
            console.error('Error parsing link URL:', err);
        }
    });

    window.addEventListener('popstate', function(e) {
        if (e.state && e.state.url) {
            loadPjaxPage(e.state.url, false);
        } else {
            loadPjaxPage(window.location.href, false);
        }
    });

    if (!history.state) {
        history.replaceState({ pjax: true, url: window.location.href }, '', window.location.href);
    }

    // Check for open_announcement_id on initial direct page load
    try {
        const urlParams = new URLSearchParams(window.location.search);
        const openAnnId = urlParams.get('open_announcement_id');
        if (openAnnId) {
            setTimeout(() => {
                window.openAnnouncementModal(openAnnId);
            }, 100);
        }
    } catch(e) {}
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

/**
 * Global announcement details modal opener and reader
 */
function openAnnouncementModal(announcementId) {
    if (!announcementId) return;
    const endpoint = (typeof window.BASE_URL !== 'undefined') ? window.BASE_URL : '/';
    fetch(endpoint + 'api/announcements_action.php?action=get&id=' + announcementId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const ann = data.announcement;
                
                // Populate modal fields
                document.getElementById('announcementModalTitle').textContent = ann.title;
                document.getElementById('announcementModalContent').innerHTML = ann.message.replace(/\n/g, '<br>');
                document.getElementById('announcementModalAuthor').textContent = ann.created_by_name + ' (' + ann.created_by_role.toUpperCase() + ')';
                document.getElementById('announcementModalDate').textContent = ann.formatted_date;
                
                // Priority Badge
                const priorityBadge = document.getElementById('announcementModalPriority');
                if (ann.priority && ann.priority !== 'normal') {
                    priorityBadge.style.display = 'inline-block';
                    priorityBadge.textContent = ann.priority.toUpperCase();
                    if (ann.priority === 'urgent') {
                        priorityBadge.className = 'badge bg-danger rounded-pill px-3 py-1.5 fw-semibold small';
                    } else {
                        priorityBadge.className = 'badge bg-warning text-dark rounded-pill px-3 py-1.5 fw-semibold small';
                    }
                } else {
                    priorityBadge.style.display = 'none';
                }
                
                // Department
                const deptSpan = document.getElementById('announcementModalDeptSpan');
                if (ann.department) {
                    deptSpan.style.display = 'inline';
                    document.getElementById('announcementModalDept').textContent = ann.department;
                } else {
                    deptSpan.style.display = 'none';
                }



                // Launch modal
                const modalEl = document.getElementById('globalAnnouncementModal');
                if (modalEl) {
                    let modal = bootstrap.Modal.getInstance(modalEl);
                    if (!modal) {
                        modal = new bootstrap.Modal(modalEl);
                    }
                    modal.show();
                }

                // Mark as read in UI list
                const elements = document.querySelectorAll(`[data-announcement-id="${announcementId}"]`);
                elements.forEach(el => {
                    el.classList.remove('unread');
                    el.style.fontWeight = 'normal';
                    const badge = el.querySelector('.unread-badge');
                    if (badge) badge.remove();
                });
            } else {
                alert(data.message || 'Failed to load announcement details.');
            }
        })
        .catch(err => {
            console.error('Error fetching announcement:', err);
        });
}
window.openAnnouncementModal = openAnnouncementModal;
