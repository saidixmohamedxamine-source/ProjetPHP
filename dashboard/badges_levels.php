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

$user = [
    'first_name' => 'User',
    'last_name' => '',
    'username' => '',
];

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

$helped = 0;
$skill_count = 0;
$total_points = 0;
$badges = 8;

if ($stmt = $connection->prepare('SELECT COUNT(*) FROM help_requests WHERE user_id = ?')) {
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($helped);
    $stmt->fetch();
    $stmt->close();
}

if ($stmt = $connection->prepare('SELECT COUNT(*) FROM skills WHERE user_id = ?')) {
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($skill_count);
    $stmt->fetch();
    $stmt->close();
}

$total_points = ($skill_count * 80) + ($helped * 25);

$levels = [
    ['rank' => 1, 'title' => 'Newcomer', 'xp' => 0],
    ['rank' => 2, 'title' => 'Learner', 'xp' => 100],
    ['rank' => 3, 'title' => 'Contributor', 'xp' => 250],
    ['rank' => 4, 'title' => 'Helper', 'xp' => 500],
    ['rank' => 5, 'title' => 'Mentor', 'xp' => 800],
    ['rank' => 6, 'title' => 'Expert', 'xp' => 1200],
    ['rank' => 7, 'title' => 'Expert Helper', 'xp' => 1700],
    ['rank' => 8, 'title' => 'Master', 'xp' => 2300],
    ['rank' => 9, 'title' => 'Legend', 'xp' => 3000],
    ['rank' => 10, 'title' => 'Champion', 'xp' => 4000],
];

$current_level = 7;
$current_level_title = 'Expert Helper';
$next_level = $levels[$current_level] ?? null;
$next_level_xp = $next_level ? $next_level['xp'] : $levels[count($levels) - 1]['xp'];
$current_xp = min(1700, $total_points);
$progress_required = $next_level_xp - ($levels[$current_level - 1]['xp'] ?? 0);
$progress_earned = $current_xp - ($levels[$current_level - 1]['xp'] ?? 0);
$progress_ratio = $progress_required > 0 ? max(0, min(1, $progress_earned / $progress_required)) : 0;

$badge_cards = [
    ['title' => 'Helper Hero', 'description' => 'Helped 50 students', 'status' => 'Earned', 'icon' => 'fas fa-trophy', 'earned' => true],
    ['title' => 'Quick Responder', 'description' => 'Response time < 1 hour', 'status' => 'Earned', 'icon' => 'fas fa-bolt', 'earned' => true],
    ['title' => 'Knowledge Sharer', 'description' => 'Shared 10+ skills', 'status' => 'Earned', 'icon' => 'fas fa-book-open', 'earned' => true],
    ['title' => 'Top Rated', 'description' => 'Maintain 4.5+ rating', 'status' => 'Earned', 'icon' => 'fas fa-star', 'earned' => true],
    ['title' => 'Consistent Helper', 'description' => 'Help 7 days in a row', 'status' => 'Locked', 'icon' => 'fas fa-fire', 'earned' => false],
    ['title' => 'Master Mentor', 'description' => 'Complete 100 sessions', 'status' => 'Locked', 'icon' => 'fas fa-crown', 'earned' => false],
    ['title' => 'Early Bird', 'description' => 'Help before 9 AM 10 times', 'status' => 'Locked', 'icon' => 'fas fa-sun', 'earned' => false],
    ['title' => 'Subject Expert', 'description' => '50 sessions in one skill', 'status' => 'Locked', 'icon' => 'fas fa-bullseye', 'earned' => false],
];

$page_title = 'Badges & Levels';
include '../includes/header.php';
?>

<div class="app-shell">
    <?php $sidebar_active = 'badges'; include '../includes/dashboard_sidebar.php'; ?>

    <div class="app-main">
        <?php $topbar_title = 'Badges & Levels'; include '../includes/dashboard_topbar.php'; ?>

        <main class="app-content">
            <div class="page-header">
                <div>
                    <span class="eyebrow">Progress</span>
                    <h1 class="page-title"><span class="accent">Badges</span> &amp; levels</h1>
                    <p class="page-subtitle">Track your progress, earn badges, and level up.</p>
                </div>
                <span class="badge primary">Signed in as <?php echo htmlspecialchars($user['username'] ?: trim($user['first_name'] . ' ' . $user['last_name'])); ?></span>
            </div>

            <div class="level-summary-grid">
                <div class="level-card level-current">
                    <div class="level-card-top">
                        <span>Current Level</span>
                        <i class="fas fa-award"></i>
                    </div>
                    <div>
                        <div class="level-number"><?php echo $current_level; ?></div>
                        <div class="level-label"><?php echo htmlspecialchars($current_level_title); ?></div>
                    </div>
                </div>

                <div class="level-card level-stats">
                    <div class="level-card-top">
                        <span>Total Points</span>
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <div class="level-number"><?php echo number_format($total_points); ?></div>
                        <div class="level-label">XP earned</div>
                    </div>
                </div>

                <div class="level-card level-progress">
                    <div class="level-card-top">
                        <span>Progress to Level <?php echo ($current_level + 1); ?></span>
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <div>
                        <div class="progress-bar">
                            <div class="progress-fill level-fill" style="width: <?php echo round($progress_ratio * 100); ?>%;"></div>
                        </div>
                        <div class="progress-text"><?php echo number_format($current_xp); ?> / <?php echo number_format($next_level_xp); ?> XP</div>
                    </div>
                </div>
            </div>

            <div class="badges-page-grid">
                <div class="section-title-with-icon">
                    <i class="section-icon fas fa-medal"></i>
                    <h2>Badges & Achievements</h2>
                </div>

                <div class="badges-grid">
                    <?php foreach ($badge_cards as $badge): ?>
                        <div class="badge-card <?php echo $badge['earned'] ? 'earned' : 'locked'; ?>">
                            <div class="badge-icon <?php echo $badge['earned'] ? 'earned' : 'locked'; ?>"><i class="<?php echo $badge['icon']; ?>"></i></div>
                            <h3><?php echo htmlspecialchars($badge['title']); ?></h3>
                            <p><?php echo htmlspecialchars($badge['description']); ?></p>
                            <div class="badge-status"><?php echo htmlspecialchars($badge['status']); ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="level-progression-section">
                <div class="section-title-with-icon">
                    <i class="section-icon fas fa-layer-group"></i>
                    <h2>Level Progression</h2>
                </div>

                <div class="level-list">
                    <?php foreach ($levels as $level): ?>
                        <?php $is_current = $level['rank'] === $current_level; ?>
                        <?php $is_upcoming = $level['rank'] > $current_level; ?>
                        <div class="level-item <?php echo $is_current ? 'current' : ($is_upcoming ? 'upcoming' : ''); ?>">
                            <div class="level-dot <?php echo $is_current ? 'current-dot' : 'upcoming-dot'; ?>"><?php echo $level['rank']; ?></div>
                            <div class="level-item-content">
                                <h3><?php echo htmlspecialchars($level['title']); ?></h3>
                                <p><?php echo number_format($level['xp']); ?> XP required</p>
                            </div>
                            <div class="level-status-group">
                                <?php if ($is_current): ?>
                                    <span class="level-current-tag">Current Level</span>
                                <?php else: ?>
                                    <span class="level-badge-tag"><?php echo $level['rank'] <= $current_level ? 'Achieved' : 'Upcoming'; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include '../includes/footer.php';
