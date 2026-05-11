<?php
/**
 * Dashboard Sidebar Partial
 *
 * Expects:
 *   - $sidebar_active (string|null): one of dashboard, profile, skills, help_requests,
 *     create_request, badges, search, statistics, about, contact, settings
 */

$active = $sidebar_active ?? '';
$is_authenticated = isset($_SESSION['user_id']);
$user_display = trim($_SESSION['user_name'] ?? '');
if ($user_display === '') {
    $user_display = $_SESSION['username'] ?? 'Student';
}
$user_initial = strtoupper(substr($user_display, 0, 1));
$user_role = $_SESSION['role'] ?? 'Student';

$app_links = [
    ['key' => 'dashboard',       'label' => 'Dashboard',      'icon' => 'fas fa-house',           'href' => SITE_URL . 'dashboard/index.php'],
    ['key' => 'profile',         'label' => 'Profile',        'icon' => 'fas fa-user',            'href' => SITE_URL . 'dashboard/profile.php'],
    ['key' => 'skills',          'label' => 'Skills',         'icon' => 'fas fa-bolt',            'href' => SITE_URL . 'dashboard/skills.php'],
    ['key' => 'help_requests',   'label' => 'Help Requests',  'icon' => 'fas fa-circle-question', 'href' => SITE_URL . 'dashboard/help_requests.php'],
    ['key' => 'create_request',  'label' => 'New Request',    'icon' => 'fas fa-circle-plus',     'href' => SITE_URL . 'dashboard/create_request.php'],
    ['key' => 'search',          'label' => 'Find Mentors',   'icon' => 'fas fa-magnifying-glass','href' => SITE_URL . 'dashboard/search.php'],
];

$progress_links = [
    ['key' => 'badges',     'label' => 'Badges & Levels', 'icon' => 'fas fa-award',     'href' => SITE_URL . 'dashboard/badges_levels.php'],
    ['key' => 'statistics', 'label' => 'Statistics',      'icon' => 'fas fa-chart-line','href' => SITE_URL . 'dashboard/statistics.php'],
];

$general_links = [
    ['key' => 'about',    'label' => 'About',    'icon' => 'fas fa-circle-info', 'href' => SITE_URL . 'about.php'],
    ['key' => 'contact',  'label' => 'Contact',  'icon' => 'fas fa-envelope',    'href' => SITE_URL . 'contact.php'],
    ['key' => 'settings', 'label' => 'Settings', 'icon' => 'fas fa-gear',        'href' => SITE_URL . 'dashboard/settings.php'],
];

$render_group = function (array $items, string $label = '') use ($active) {
    if ($label !== '') {
        echo '<div class="sidebar-label">' . htmlspecialchars($label) . '</div>';
    }
    echo '<nav class="sidebar-menu">';
    foreach ($items as $link) {
        $is_active = $link['key'] === $active ? ' class="active"' : '';
        echo '<a href="' . $link['href'] . '"' . $is_active . '>';
        echo '<i class="' . $link['icon'] . '"></i><span>' . htmlspecialchars($link['label']) . '</span>';
        echo '</a>';
    }
    echo '</nav>';
};
?>
<aside class="app-sidebar" id="appSidebar">
    <a href="<?php echo SITE_URL; ?>dashboard/index.php" class="sidebar-brand">
        <span class="brand-mark">IS</span>
        <span class="brand-text">
            <span class="brand-title"><?php echo htmlspecialchars(SITE_NAME); ?></span>
            <span class="brand-subtitle"><?php echo htmlspecialchars(SITE_SUBTITLE); ?></span>
        </span>
    </a>

    <div class="sidebar-section">
        <?php $render_group($app_links, 'Workspace'); ?>
    </div>

    <div class="sidebar-section">
        <?php $render_group($progress_links, 'Progress'); ?>
    </div>

    <div class="sidebar-section">
        <?php $render_group($general_links, 'General'); ?>
    </div>

    <div class="sidebar-footer">
        <?php if ($is_authenticated): ?>
            <div class="sidebar-user">
                <span class="user-avatar-small"><?php echo htmlspecialchars($user_initial); ?></span>
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name"><?php echo htmlspecialchars($user_display); ?></div>
                    <div class="sidebar-user-role"><?php echo htmlspecialchars($user_role); ?></div>
                </div>
            </div>
            <a href="<?php echo SITE_URL; ?>auth/logout.php" class="sidebar-logout">
                <i class="fas fa-arrow-right-from-bracket"></i><span>Log out</span>
            </a>
        <?php else: ?>
            <div class="sidebar-guest">
                <a href="<?php echo SITE_URL; ?>auth/login.php" class="button secondary block">
                    <i class="fas fa-arrow-right-to-bracket"></i> Sign in
                </a>
                <a href="<?php echo SITE_URL; ?>auth/register.php" class="button primary block">
                    <i class="fas fa-user-plus"></i> Sign up
                </a>
            </div>
        <?php endif; ?>
    </div>
</aside>
