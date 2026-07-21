<?php
/**
 * SkillBridge - Reusable Header Component
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';

$pageTitle = $pageTitle ?? APP_NAME . ' - Skill Gap Analysis & LMS';
$currentUser = get_logged_in_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    
    <!-- Google Fonts: Inter + Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6.5 & Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    
    <!-- Early FOUC Prevention Theme Engine -->
    <script>
    (function() {
        var savedTheme = localStorage.getItem('skillbridge_theme') || 'system';
        var resolvedTheme = savedTheme;
        if (savedTheme === 'system') {
            resolvedTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }
        document.documentElement.setAttribute('data-theme', resolvedTheme);
    })();
    </script>

    <!-- Custom CSS -->
    <link href="<?= BASE_URL ?>assets/css/style.css" rel="stylesheet">
</head>
<body class="<?= is_logged_in() ? 'app-body' : 'auth-body' ?>">

<?php if (is_logged_in()): ?>
<div class="app-layout" id="appLayout">
    <!-- Sidebar Included via layout -->
    <?php include __DIR__ . '/sidebar.php'; ?>
    
    <!-- Main Content Wrapper -->
    <div class="main-wrapper">
        <!-- Top Navbar Included via layout -->
        <?php include __DIR__ . '/navbar.php'; ?>
        
        <!-- Main Content Area -->
        <main class="content-area p-3 p-md-4">
            <!-- Flash Message Toast/Alert -->
            <?php $flash = get_flash_message(); if ($flash): ?>
                <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                        <div><?= htmlspecialchars($flash['message']) ?></div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
<?php endif; ?>
