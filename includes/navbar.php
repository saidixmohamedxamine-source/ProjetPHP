<?php
/**
 * Navigation Bar Template
 */
?>
<nav class="navbar">
    <div class="nav-wrapper">
        <div class="nav-left">
            <button class="menu-toggle" id="menuToggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <div class="nav-center">
            <h2><?php echo isset($page_title) ? $page_title : 'Dashboard'; ?></h2>
        </div>
        <div class="nav-right">
            <div class="user-menu">
                <span class="user-name"><?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest'; ?></span>
                <a href="<?php echo SITE_URL; ?>auth/logout.php" class="logout-btn">Logout</a>
            </div>
        </div>
    </div>
</nav>
