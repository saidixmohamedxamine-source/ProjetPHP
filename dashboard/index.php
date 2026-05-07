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

<div class="dashboard-page">
    <aside class="dashboard-sidebar">
        <div class="sidebar-brand">
            <div class="brand-title">ISMO-SkillSwap</div>
            <div class="brand-subtitle">Student Skill Sharing</div>
        </div>

        <nav class="dashboard-menu">
            <a href="index.php" class="active"><i class="fas fa-home"></i> Dashboard</a>
            <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
            <a href="skills.php"><i class="fas fa-book"></i> Skills</a>
            <a href="#"><i class="fas fa-question-circle"></i> Help Requests</a>
            <a href="#"><i class="fas fa-plus-circle"></i> Create Request</a>
            <a href="#"><i class="fas fa-award"></i> Badges &amp; Levels</a>
            <a href="#"><i class="fas fa-search"></i> Search</a>
            <a href="#"><i class="fas fa-chart-bar"></i> Statistics</a>
        </nav>

        <a href="../auth/logout.php" class="sidebar-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </aside>

    <main class="dashboard-main">
        <div class="dashboard-top">
            <div>
                <h1>Welcome back, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?>!</h1>
                <p>Here's what's happening with your skills today.</p>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-title">Active Requests</div>
                <div class="stat-value"><?php echo number_format($active_requests); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-title">Skills Shared</div>
                <div class="stat-value"><?php echo number_format($skills_shared); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-title">Total Points</div>
                <div class="stat-value"><?php echo number_format($total_points); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-title">Connections</div>
                <div class="stat-value"><?php echo number_format($connections); ?></div>
            </div>
        </div>

        <div class="dashboard-content-grid">
            <section class="recent-requests-card card">
                <div class="content-header">
                    <div>
                        <h2>Recent Help Requests</h2>
                        <p>See your latest requests and stay on top of progress.</p>
                    </div>
                    <a href="#" class="button small secondary">View All</a>
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
                            <p>No recent requests found. Create a new request to get help.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <aside class="right-panel">
                <div class="card quick-actions-card">
                    <h2>Quick Actions</h2>
                    <button class="button primary">Request Help</button>
                    <button class="button secondary">Manage Skills</button>
                    <button class="button outline">Find Mentors</button>
                </div>

                <div class="card progress-card">
                    <div class="content-header">
                        <div>
                            <h2>Your Progress</h2>
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

<?php include '../includes/footer.php'; ?>
