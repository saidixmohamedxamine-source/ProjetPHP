<?php
/**
 * Navigation Bar Template
 */

$current_page = basename($_SERVER['PHP_SELF']);
$current_path = str_replace('\\', '/', $_SERVER['PHP_SELF']);
$is_dashboard_page = strpos($current_path, '/dashboard/') !== false;
$is_logged_in = isset($_SESSION['user_id']);
$display_name = trim($_SESSION['user_name'] ?? '');

if ($display_name === '') {
    $display_name = $_SESSION['username'] ?? 'Student';
}

$nav_links = [
    [
        'label' => 'Home',
        'href' => SITE_URL . 'index.php',
        'icon' => 'fas fa-home',
        'page' => 'index.php'
    ],
    [
        'label' => 'About',
        'href' => SITE_URL . 'about.php',
        'icon' => 'fas fa-circle-info',
        'page' => 'about.php'
    ],
    [
        'label' => 'Contact',
        'href' => SITE_URL . 'contact.php',
        'icon' => 'fas fa-envelope',
        'page' => 'contact.php'
    ]
];

if ($is_logged_in) {
    $nav_links[] = [
        'label' => 'Dashboard',
        'href' => SITE_URL . 'dashboard/index.php',
        'icon' => 'fas fa-table-columns',
        'page' => 'index.php',
        'dashboard' => true
    ];
}
?>
<nav class="navbar">
    <div class="nav-wrapper">
        <a href="<?php echo SITE_URL; ?>index.php" class="nav-brand" aria-label="<?php echo htmlspecialchars(SITE_NAME); ?> home">
            <span class="brand-mark">IS</span>
            <span>
                <span class="brand-name"><?php echo SITE_NAME; ?></span>
                <span class="brand-tagline"><?php echo SITE_SUBTITLE; ?></span>
            </span>
        </a>

        <button class="menu-toggle" id="menuToggle" type="button" aria-label="Toggle navigation" aria-controls="siteNavigation" aria-expanded="false">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <div class="nav-content" id="siteNavigation">
            <div class="site-nav" aria-label="Main navigation">
                <?php foreach ($nav_links as $link): ?>
                    <?php
                    $is_active = !$is_dashboard_page && $current_page === $link['page'];
                    if (!empty($link['dashboard'])) {
                        $is_active = $is_dashboard_page;
                    }
                    ?>
                    <a href="<?php echo $link['href']; ?>" class="site-nav-link<?php echo $is_active ? ' active' : ''; ?>">
                        <i class="<?php echo $link['icon']; ?>"></i>
                        <span><?php echo $link['label']; ?></span>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="nav-actions">
                <?php if ($is_logged_in): ?>
                    <a href="<?php echo SITE_URL; ?>dashboard/profile.php" class="user-chip">
                        <span class="user-avatar-small"><?php echo htmlspecialchars(strtoupper(substr($display_name, 0, 1))); ?></span>
                        <span class="user-name"><?php echo htmlspecialchars($display_name); ?></span>
                    </a>
                    <a href="<?php echo SITE_URL; ?>auth/logout.php" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                <?php else: ?>
                    <a href="<?php echo SITE_URL; ?>auth/login.php" class="nav-login">Login</a>
                    <a href="<?php echo SITE_URL; ?>auth/register.php" class="nav-signup">Sign up</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
