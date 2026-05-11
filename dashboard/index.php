<?php
/**
 * Dashboard Home Page
 */

require_once '../includes/config.php';
require_once '../database/connection.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Get dashboard metrics
$active_requests = 0;
$skills_shared = 0;
$connections = 0;
$total_points = 0;
$recent_requests = [];
$connection = $db->getConnection();

if ($stmt = $connection->prepare('SELECT COUNT(*) AS total FROM help_requests WHERE user_id = ? AND status = ?')) {
    $status_open = 'open';
    $stmt->bind_param('is', $user_id, $status_open);
    $stmt->execute();
    $stmt->bind_result($active_requests);
    $stmt->fetch();
    $stmt->close();
}

if ($stmt = $connection->prepare('SELECT COUNT(*) AS total FROM skills WHERE user_id = ?')) {
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($skills_shared);
    $stmt->fetch();
    $stmt->close();
}

$connections = max(1, $skills_shared + $active_requests);
$total_points = ($skills_shared * 80) + ($active_requests * 25);

if ($stmt = $connection->prepare('SELECT title, skill_category, status, created_at FROM help_requests WHERE user_id = ? ORDER BY created_at DESC LIMIT 3')) {
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $recent_requests[] = $row;
    }
    $stmt->close();
}

$page_title = 'Dashboard';

include '../includes/header.php';
?>

<div class="app-shell">
    <?php $sidebar_active = 'dashboard'; include '../includes/dashboard_sidebar.php'; ?>

    <div class="app-main">
        <?php $topbar_title = 'Dashboard'; include '../includes/dashboard_topbar.php'; ?>

        <main class="app-content">
            <div class="dashboard-top">
                <h1>Welcome back, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?> 👋</h1>
                <p>Here's a snapshot of your skills, requests, and progress today.</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-title">Active requests</div>
                    <div class="stat-value"><?php echo number_format($active_requests); ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">Skills shared</div>
                    <div class="stat-value"><?php echo number_format($skills_shared); ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">Total points</div>
                    <div class="stat-value"><?php echo number_format($total_points); ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">Connections</div>
                    <div class="stat-value"><?php echo number_format($connections); ?></div>
                </div>
            </div>

            <div class="dashboard-content-grid">
                <section class="card recent-requests-card">
                    <div class="content-header">
                        <div>
                            <h2>Recent help requests</h2>
                            <p>See your latest requests and stay on top of progress.</p>
                        </div>
                        <a href="help_requests.php" class="button secondary small">View all</a>
                    </div>

                    <div class="request-list">
                        <?php if (!empty($recent_requests)): ?>
                            <?php foreach ($recent_requests as $request): ?>
                                <div class="request-item">
                                    <div>
                                        <h3><?php echo htmlspecialchars($request['title']); ?></h3>
                                        <p><?php echo htmlspecialchars($request['skill_category']); ?> • <?php echo htmlspecialchars(ucfirst($request['status'])); ?></p>
                                    </div>
                                    <span class="request-time"><?php echo date('M j', strtotime($request['created_at'])); ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="request-empty">
                                <p>No recent requests yet. Create one to get help from peers.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>

                <aside class="right-panel">
                    <div class="card quick-actions-card">
                        <h2>Quick actions</h2>
                        <a href="create_request.php" class="button primary"><i class="fas fa-circle-plus"></i> Request help</a>
                        <a href="skills.php" class="button secondary"><i class="fas fa-bolt"></i> Manage skills</a>
                        <a href="search.php" class="button outline"><i class="fas fa-magnifying-glass"></i> Find mentors</a>
                    </div>

                    <div class="card progress-card">
                        <div class="content-header">
                            <div>
                                <h2>Your progress</h2>
                                <p>Level 7 • <?php echo number_format($total_points); ?> / 2,000 XP</p>
                            </div>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: <?php echo min(100, round($total_points / 2000 * 100)); ?>%;"></div>
                        </div>
                    </div>
                </aside>
            </div>
        </main>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
