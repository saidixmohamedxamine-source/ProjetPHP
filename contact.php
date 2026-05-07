<?php
/**
 * Contact Page
 */

require_once 'includes/config.php';
session_start();

$page_title = 'Contact';
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
    $message_text = isset($_POST['message']) ? trim($_POST['message']) : '';

    if (empty($name) || empty($email) || empty($subject) || empty($message_text)) {
        $error = 'Please fill in all fields.';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format.';
    } else {
        // TODO: Send email or store message in database
        $message = 'Thank you for your message! We will get back to you soon.';
    }
}

include 'includes/header.php';
include 'includes/navbar.php';
?>

<main class="main-content">
    <div class="contact-container">
        <h1>Contact Us</h1>
        <p>Have a question or feedback? We'd love to hear from you!</p>

        <form method="POST" class="contact-form">
            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if (!empty($message)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>

            <div class="form-group">
                <label for="name">Your Name</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="email">Your Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" required>
            </div>

            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" rows="5" required></textarea>
            </div>

            <button type="submit" class="button primary">Send Message</button>
        </form>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
