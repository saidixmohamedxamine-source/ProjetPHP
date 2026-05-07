<?php
/**
 * Settings Page
 */

require_once '../includes/config.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$page_title = 'Settings';
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // TODO: Implement settings update logic
    $message = 'Settings updated successfully!';
}

include '../includes/header.php';
include '../includes/navbar.php';
?>

<main class="main-content">
    <div class="settings-container">
        <h1>Account Settings</h1>

        <?php if (!empty($message)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form method="POST" class="settings-form">
            <div class="form-section">
                <h3>Profile Information</h3>
                
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" value="">
                </div>

                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" value="">
                </div>

                <div class="form-group">
                    <label for="bio">Bio</label>
                    <textarea id="bio" name="bio"></textarea>
                </div>
            </div>

            <div class="form-section">
                <h3>Privacy Settings</h3>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="public_profile" checked>
                        Make my profile public
                    </label>
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" name="notifications" checked>
                        Enable email notifications
                    </label>
                </div>
            </div>

            <button type="submit" class="button primary">Save Changes</button>
        </form>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
