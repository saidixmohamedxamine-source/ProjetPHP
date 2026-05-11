<?php
/**
 * Settings Page
 */

require_once '../includes/config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$page_title = 'Settings';
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = 'Settings updated successfully!';
}

include '../includes/header.php';
?>

<div class="app-shell">
    <?php $sidebar_active = 'settings'; include '../includes/dashboard_sidebar.php'; ?>

    <div class="app-main">
        <?php $topbar_title = 'Settings'; include '../includes/dashboard_topbar.php'; ?>

        <main class="app-content">
            <div class="page-header">
                <div>
                    <span class="eyebrow">General</span>
                    <h1 class="page-title"><span class="accent">Account</span> settings</h1>
                    <p class="page-subtitle">Manage your profile, privacy, and notifications.</p>
                </div>
            </div>

            <?php if (!empty($message)): ?>
                <div class="alert alert-success"><i class="fas fa-circle-check"></i><span><?php echo htmlspecialchars($message); ?></span></div>
            <?php endif; ?>

            <form method="POST" class="form-card">
                <h3 style="margin-bottom:14px;">Profile information</h3>
                <div class="form-grid">
                    <div class="form-field">
                        <label for="first_name">First name</label>
                        <input type="text" id="first_name" name="first_name" value="">
                    </div>
                    <div class="form-field">
                        <label for="last_name">Last name</label>
                        <input type="text" id="last_name" name="last_name" value="">
                    </div>
                    <div class="form-field full-width">
                        <label for="bio">Bio</label>
                        <textarea id="bio" name="bio" rows="5" placeholder="Tell others a bit about yourself…"></textarea>
                    </div>
                </div>

                <h3 style="margin:18px 0 14px;">Privacy</h3>
                <div class="form-field">
                    <label class="checkbox-group">
                        <input type="checkbox" name="public_profile" checked> Make my profile public
                    </label>
                </div>
                <div class="form-field">
                    <label class="checkbox-group">
                        <input type="checkbox" name="notifications" checked> Enable email notifications
                    </label>
                </div>

                <div class="form-actions-row">
                    <button type="submit" class="button primary"><i class="fas fa-floppy-disk"></i> Save changes</button>
                    <a href="profile.php" class="button secondary">Cancel</a>
                </div>
            </form>
        </main>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
