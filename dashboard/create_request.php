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

<div class="app-shell">
    <?php $sidebar_active = 'create_request'; include '../includes/dashboard_sidebar.php'; ?>

    <div class="app-main">
        <?php $topbar_title = 'New Request'; include '../includes/dashboard_topbar.php'; ?>

        <main class="app-content">
            <div class="page-header">
                <div>
                    <span class="eyebrow">New request</span>
                    <h1 class="page-title">Create a <span class="accent">help request</span></h1>
                    <p class="page-subtitle">Post a clear request so other students can connect and help you.</p>
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
                    <button type="button" class="button primary submit-request-button"><i class="fas fa-paper-plane"></i> Submit request</button>
                    <button type="button" class="button secondary cancel-request-button">Cancel</button>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include '../includes/footer.php'; ?>