<?php
/**
 * Dashboard Topbar Partial
 *
 * Expects:
 *   - $topbar_title (string|null): page title shown on the left
 */
$title = $topbar_title ?? ($page_title ?? '');
?>
<header class="app-topbar">
    <div class="app-topbar-left">
        <button type="button" class="topbar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
            <i class="fas fa-bars"></i>
        </button>
        <div class="topbar-search">
            <i class="fas fa-magnifying-glass"></i>
            <input type="search" placeholder="Search skills, mentors, requests…" aria-label="Search">
        </div>
    </div>

    <div class="app-topbar-right">
        <a href="<?php echo SITE_URL; ?>dashboard/help_requests.php" class="icon-pill" title="Notifications" aria-label="Notifications">
            <i class="fas fa-bell"></i>
        </a>
        <a href="<?php echo SITE_URL; ?>dashboard/profile.php" class="icon-pill" title="Profile" aria-label="Profile">
            <i class="fas fa-user"></i>
        </a>
    </div>
</header>
