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
?>

<main class="home-page">
    <div class="hero-section">
        <h1><?php echo SITE_NAME; ?></h1>
        <p><?php echo SITE_SUBTITLE; ?></p>
        <p class="hero-description">Connect with students, share your skills, and learn from others in our community.</p>
        
        <div class="action-buttons">
            <a href="auth/login.php" class="button primary">Login</a>
            <a href="auth/register.php" class="button secondary">Sign Up</a>
        </div>
    </div>

    <div class="features-section">
        <h2>Why Join Us?</h2>
        <div class="features-grid">
            <div class="feature-card">
                <h3>Share Your Skills</h3>
                <p>Teach others what you're passionate about and help them grow.</p>
            </div>
            <div class="feature-card">
                <h3>Learn New Skills</h3>
                <p>Find mentors and experts willing to share their knowledge with you.</p>
            </div>
            <div class="feature-card">
                <h3>Build Community</h3>
                <p>Connect with like-minded students and create lasting relationships.</p>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
