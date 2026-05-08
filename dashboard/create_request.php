<?php
/**
 * Create Request Page
 */

require_once '../includes/config.php';
require_once '../database/connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$page_title = 'Create Request';
include '../includes/header.php';
?>

<div class="dashboard-page">
    <aside class="dashboard-sidebar">
        <div class="sidebar-brand">
            <div class="brand-title">ISMO-SkillSwap</div>
            <div class="brand-subtitle">Student Skill Sharing</div>
        </div>

        <nav class="dashboard-menu">
            <a href="index.php"><i class="fas fa-home"></i> Dashboard</a>
            <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
            <a href="skills.php"><i class="fas fa-book"></i> Skills</a>
            <a href="help_requests.php"><i class="fas fa-question-circle"></i> Help Requests</a>
            <a href="create_request.php" class="active"><i class="fas fa-plus-circle"></i> Create Request</a>
            <a href="#"><i class="fas fa-award"></i> Badges &amp; Levels</a>
            <a href="#"><i class="fas fa-search"></i> Search</a>
            <a href="#"><i class="fas fa-chart-bar"></i> Statistics</a>
        </nav>

        <a href="../auth/logout.php" class="sidebar-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </aside>

    <main class="dashboard-main">
        <section class="create-request-page">
            <div class="help-header-row">
                <div>
                    <h1>Create Request</h1>
                    <p>Post a new help request so other students can connect with you.</p>
                </div>
            </div>

            <div class="form-card">
                <div class="form-field full-width">
                    <label for="request-title">Title</label>
                    <input id="request-title" class="form-input" type="text" placeholder="Enter a clear request title" />
                </div>

                <div class="form-field full-width">
                    <label for="request-description">Description</label>
                    <textarea id="request-description" class="form-textarea" placeholder="Provide detailed information about what you need help with..."></textarea>
                </div>

                <div class="form-grid">
                    <div class="form-field">
                        <label for="request-skill">Skill / Topic</label>
                        <input id="request-skill" class="form-input" type="text" placeholder="e.g., React, Python, SQL" />
                    </div>
                    <div class="form-field">
                        <label for="request-level">Your Level</label>
                        <select id="request-level" class="form-select">
                            <option value="">Select level</option>
                            <option value="Beginner">Beginner</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Advanced">Advanced</option>
                        </select>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-field">
                        <label for="request-urgency">Urgency</label>
                        <select id="request-urgency" class="form-select">
                            <option value="Normal">Normal - Few days</option>
                            <option value="High">High - 24 hours</option>
                            <option value="Low">Low - Flexible</option>
                        </select>
                    </div>
                    <div class="form-field">
                        <label for="request-tags">Tags</label>
                        <div class="tag-input-row">
                            <input id="request-tags" class="form-input" type="text" placeholder="Add tags (press Enter)" />
                            <button type="button" class="tag-button">Add Tag</button>
                        </div>
                        <div id="tag-list" class="tag-list"></div>
                    </div>
                </div>

                <div class="form-actions-row">
                    <button type="button" class="button primary submit-request-button"><i class="fas fa-question-circle"></i> Submit Request</button>
                    <button type="button" class="button secondary cancel-request-button">Cancel</button>
                </div>
            </div>
        </section>
    </main>
</div>

<?php include '../includes/footer.php'; ?>