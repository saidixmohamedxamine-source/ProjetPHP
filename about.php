<?php
/**
 * About Page
 */

require_once 'includes/config.php';
session_start();

$page_title = 'About';

include 'includes/header.php';
include 'includes/navbar.php';
?>

<main class="main-content">
    <div class="about-container">
        <h1>About <?php echo SITE_NAME; ?></h1>
        
        <div class="about-section">
            <h2>Our Mission</h2>
            <p>Our mission is to create a vibrant community where students can share their knowledge, learn from one another, and grow together. We believe that peer-to-peer learning is one of the most effective ways to develop new skills and build meaningful connections.</p>
        </div>

        <div class="about-section">
            <h2>What We Offer</h2>
            <ul>
                <li>A platform to showcase and share your skills with other students</li>
                <li>Access to mentors and experts in various fields</li>
                <li>Tools to request help and find learning partners</li>
                <li>A supportive community dedicated to mutual growth</li>
            </ul>
        </div>

        <div class="about-section">
            <h2>Get Started</h2>
            <p>
                Ready to start your learning journey? 
                <a href="auth/register.php" class="button secondary">Create an account</a>
            </p>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
