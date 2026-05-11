<?php
require_once '../includes/config.php';
require_once '../database/connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$connection = $db->getConnection();

$user = ['first_name' => 'User', 'last_name' => '', 'username' => ''];
if ($stmt = $connection->prepare('SELECT username, first_name, last_name FROM users WHERE id = ?')) {
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($username, $first_name, $last_name);
    if ($stmt->fetch()) {
        $user = [
            'first_name' => $first_name ?: 'User',
            'last_name' => $last_name ?: '',
            'username' => $username,
        ];
    }
    $stmt->close();
}

$stats = [
    ['label' => 'Total Sessions', 'value' => '1,247', 'note' => '+12% this month', 'icon' => 'fas fa-question-circle'],
    ['label' => 'Active Users', 'value' => '342', 'note' => '+8% this month', 'icon' => 'fas fa-user-friends'],
    ['label' => 'Skills Shared', 'value' => '156', 'note' => '+5% this month', 'icon' => 'fas fa-award'],
    ['label' => 'Avg Rating', 'value' => '4.7', 'note' => 'Based on reviews', 'icon' => 'fas fa-star'],
];

$monthly_activity = [10, 18, 15, 22, 28, 24, 31];
$skills_requested = [
    ['skill' => 'React', 'value' => 16],
    ['skill' => 'Python', 'value' => 12],
    ['skill' => 'SQL', 'value' => 10],
    ['skill' => 'JavaScript', 'value' => 8],
    ['skill' => 'UI/UX', 'value' => 5],
];

$categories = [
    ['label' => 'Web Development', 'percentage' => '40%', 'color' => '#6366f1'],
    ['label' => 'Data Science', 'percentage' => '25%', 'color' => '#8b5cf6'],
    ['label' => 'Mobile Dev', 'percentage' => '15%', 'color' => '#ec4899'],
    ['label' => 'Design', 'percentage' => '12%', 'color' => '#f59e0b'],
    ['label' => 'DevOps', 'percentage' => '8%', 'color' => '#10b981'],
];

$top_mentors = [
    ['rank' => 1, 'name' => 'Alex Martinez', 'sessions' => 71, 'skills' => 'React, JavaScript', 'rating' => 4.9, 'badge' => 'Top'],
    ['rank' => 2, 'name' => 'Sarah Johnson', 'sessions' => 67, 'skills' => 'Python, SQL', 'rating' => 4.8, 'badge' => '2nd'],
    ['rank' => 3, 'name' => 'David Lee', 'sessions' => 52, 'skills' => 'Node.js, MongoDB', 'rating' => 4.9, 'badge' => '3rd'],
    ['rank' => 4, 'name' => 'Emma Wilson', 'sessions' => 45, 'skills' => 'UI/UX, React', 'rating' => 4.7, 'badge' => '#4'],
    ['rank' => 5, 'name' => 'Mike Chen', 'sessions' => 38, 'skills' => 'Java, SQL', 'rating' => 4.6, 'badge' => '#5'],
];

$page_title = 'Statistics';
include '../includes/header.php';
?>

<div class="app-shell">
    <?php $sidebar_active = 'statistics'; include '../includes/dashboard_sidebar.php'; ?>

    <div class="app-main">
        <?php $topbar_title = 'Statistics'; include '../includes/dashboard_topbar.php'; ?>

        <main class="app-content">
            <div class="page-header">
                <div>
                    <span class="eyebrow">Insights</span>
                    <h1 class="page-title"><span class="accent">Statistics</span> dashboard</h1>
                    <p class="page-subtitle">Platform analytics, top mentors, and activity insights.</p>
                </div>
            </div>

            <div class="stats-grid">
                <?php foreach ($stats as $stat): ?>
                    <div class="stat-card">
                        <div class="stat-content">
                            <h3><?php echo htmlspecialchars($stat['label']); ?></h3>
                            <div class="stat-value"><?php echo htmlspecialchars($stat['value']); ?></div>
                            <div class="stat-note"><?php echo htmlspecialchars($stat['note']); ?></div>
                        </div>
                        <div class="stat-icon"><i class="<?php echo htmlspecialchars($stat['icon']); ?>"></i></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="charts-grid">
                <div class="chart-card">
                    <div class="chart-header">
                        <h2>Monthly Activity</h2>
                        <span class="stat-note">Sessions</span>
                    </div>
                    <div class="chart-plot chart-line">
                        <div class="chart-axis">
                            <?php for ($i = 1; $i <= 7; $i++): ?>
                                <div class="chart-point point-<?php echo $i; ?>"></div>
                            <?php endfor; ?>
                        </div>
                        <div class="chart-labels">
                            <span>Oct</span>
                            <span>Nov</span>
                            <span>Dec</span>
                            <span>Jan</span>
                            <span>Feb</span>
                            <span>Mar</span>
                            <span>Apr</span>
                        </div>
                    </div>
                </div>

                <div class="chart-card">
                    <div class="chart-header">
                        <h2>Top Skills Requested</h2>
                    </div>
                    <div class="chart-bars">
                        <?php foreach ($skills_requested as $index => $skill): ?>
                            <div class="bar-item">
                                <span><?php echo htmlspecialchars($skill['skill']); ?></span>
                                <div class="bar-fill bar-<?php echo $index + 1; ?>"></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="charts-grid">
                <div class="chart-card pie-card">
                    <div class="chart-header">
                        <h2>Categories Distribution</h2>
                    </div>
                    <div class="pie-chart"></div>
                    <div class="chart-labels" style="flex-wrap: wrap; gap: 14px; margin-top: 24px; justify-content: center;">
                        <?php foreach ($categories as $category): ?>
                            <span style="display:inline-flex; align-items:center; gap:8px; font-size:14px; color:#374151;">
                                <span style="width:12px; height:12px; border-radius:50%; background:<?php echo $category['color']; ?>;"></span>
                                <?php echo htmlspecialchars($category['label'] . ' ' . $category['percentage']); ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="chart-card">
                    <div class="chart-header">
                        <h2>Top Mentors</h2>
                    </div>
                    <?php foreach ($top_mentors as $mentor): ?>
                        <div class="mentor-card" style="padding:18px 22px; margin-bottom:16px; background:#f8fafc;">
                            <div class="mentor-card-left">
                                <div class="mentor-avatar"><?php echo $mentor['rank']; ?></div>
                                <div>
                                    <h2 style="font-size:18px; margin-bottom:6px;"><?php echo htmlspecialchars($mentor['name']); ?></h2>
                                    <div class="mentor-meta" style="gap:12px; font-size:13px; color:#6b7280;">
                                        <span><?php echo htmlspecialchars($mentor['sessions']); ?> sessions</span>
                                        <span>•</span>
                                        <span><?php echo htmlspecialchars($mentor['skills']); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div style="text-align:right;">
                                <div style="color:#f59e0b; font-size:14px; margin-bottom:6px;">
                                    ★★★★☆ <?php echo htmlspecialchars($mentor['rating']); ?>
                                </div>
                                <span class="mentor-badge" style="background:#e0e7ff; color:#4338ca; padding:8px 14px;"><?php echo htmlspecialchars($mentor['badge']); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include '../includes/footer.php';
