<?php
/**
 * Home Page
 */

require_once 'includes/config.php';
session_start();

// Redirect to dashboard if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard/index.php');
    exit;
}

$page_title = 'Home';

include 'includes/header.php';
include 'includes/navbar.php';
?>

<main class="home-page">
    <section class="hero-section">
        <span class="eyebrow"><?php echo SITE_SUBTITLE; ?></span>
        <h1><?php echo SITE_NAME; ?></h1>
        <p class="lead">Connect with students, share your skills, and learn from peers — all in one place.</p>
        <p class="hero-description">Built for students who want to grow faster through real, peer-to-peer learning.</p>

        <div class="action-buttons">
            <a href="auth/register.php" class="button primary">
                <i class="fas fa-rocket"></i> Get started — it's free
            </a>
            <a href="auth/login.php" class="button outline">
                <i class="fas fa-arrow-right-to-bracket"></i> Sign in
            </a>
        </div>
    </section>

    <section class="features-section">
        <h2>Why students choose <?php echo SITE_NAME; ?></h2>
        <div class="features-grid">
            <article class="feature-card">
                <div class="highlight-icon"><i class="fas fa-lightbulb"></i></div>
                <h3>Share Your Skills</h3>
                <p>Teach what you're passionate about, get recognized, and earn points and badges.</p>
            </article>
            <article class="feature-card">
                <div class="highlight-icon"><i class="fas fa-user-graduate"></i></div>
                <h3>Find Mentors</h3>
                <p>Match with students who already understand the topics you want to improve in.</p>
            </article>
            <article class="feature-card">
                <div class="highlight-icon"><i class="fas fa-people-group"></i></div>
                <h3>Build Community</h3>
                <p>Make meaningful learning connections and grow alongside your peers.</p>
            </article>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
