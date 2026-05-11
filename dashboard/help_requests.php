<?php
/**
 * Help Requests Page
 */

require_once '../includes/config.php';
require_once '../database/connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$connection = $db->getConnection();
$help_requests = [];

if ($stmt = $connection->prepare('SELECT hr.title, hr.description, hr.skill_category, hr.status, hr.created_at, u.first_name, u.last_name, u.username FROM help_requests hr LEFT JOIN users u ON hr.user_id = u.id WHERE hr.user_id != ? AND hr.status = ? ORDER BY hr.created_at DESC LIMIT 12')) {
    $status = 'open';
    $stmt->bind_param('is', $user_id, $status);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $help_requests[] = $row;
    }
    $stmt->close();
}

if (empty($help_requests)) {
    $help_requests = [
        [
            'title' => 'Need help with React Hooks',
            'description' => 'Looking for someone to explain useState and useEffect in detail. I\'m building a todo app and struggling with state management.',
            'skill_category' => 'React',
            'status' => 'beginner',
            'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours')),
            'first_name' => 'Sarah',
            'last_name' => 'Johnson',
            'username' => 'sjohnson'
        ],
        [
            'title' => 'Python data analysis project',
            'description' => 'Need assistance with pandas and matplotlib for data visualization. Working on a sales analysis dashboard.',
            'skill_category' => 'Python',
            'status' => 'intermediate',
            'created_at' => date('Y-m-d H:i:s', strtotime('-5 hours')),
            'first_name' => 'Mike',
            'last_name' => 'Chen',
            'username' => 'mchen'
        ],
        [
            'title' => 'Database design review',
            'description' => 'Looking for feedback on my MySQL schema for an e-commerce platform. Want to ensure proper normalization.',
            'skill_category' => 'SQL',
            'status' => 'intermediate',
            'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
            'first_name' => 'Emma',
            'last_name' => 'Wilson',
            'username' => 'ewilson'
        ],
    ];
}

function timeAgo($datetime)
{
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;

    if ($diff < 60) {
        return 'Just now';
    }
    if ($diff < 3600) {
        return floor($diff / 60) . ' minutes ago';
    }
    if ($diff < 86400) {
        return floor($diff / 3600) . ' hours ago';
    }
    return floor($diff / 86400) . ' days ago';
}

$page_title = 'Help Requests';
include '../includes/header.php';
?>

<div class="app-shell">
    <?php $sidebar_active = 'help_requests'; include '../includes/dashboard_sidebar.php'; ?>

    <div class="app-main">
        <?php $topbar_title = 'Help Requests'; include '../includes/dashboard_topbar.php'; ?>

        <main class="app-content">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Help Requests</h1>
                    <p class="page-subtitle">Browse and respond to students seeking assistance.</p>
                </div>
                <div class="help-actions-row">
                    <button class="button secondary"><i class="fas fa-filter"></i> Filters</button>
                    <a href="create_request.php" class="button primary"><i class="fas fa-plus"></i> Create request</a>
                </div>
            </div>

            <div class="help-card-list">
                <?php foreach ($help_requests as $request): ?>
                    <?php
                        $initials = '';
                        if (!empty($request['first_name']) || !empty($request['last_name'])) {
                            $initials = strtoupper(substr($request['first_name'], 0, 1) . substr($request['last_name'], 0, 1));
                        } else {
                            $initials = strtoupper(substr($request['username'], 0, 2));
                        }
                    ?>
                    <article class="help-card">
                        <div class="help-card-main">
                            <div class="profile-badge-circle"><?php echo htmlspecialchars($initials); ?></div>
                            <div>
                                <h3 class="help-card-title"><?php echo htmlspecialchars($request['title']); ?></h3>
                                <div class="help-card-tags">
                                    <span class="tag info"><?php echo htmlspecialchars($request['skill_category']); ?></span>
                                    <span class="tag info"><?php echo ucfirst(htmlspecialchars($request['status'])); ?></span>
                                </div>
                                <p class="help-card-description"><?php echo htmlspecialchars($request['description']); ?></p>
                                <div class="help-card-footer">
                                    <span><?php echo htmlspecialchars(trim($request['first_name'] . ' ' . $request['last_name'])) ?: htmlspecialchars($request['username']); ?></span>
                                    <span><?php echo timeAgo($request['created_at']); ?></span>
                                </div>
                            </div>
                        </div>
                        <button class="offer-help-btn">Offer help <i class="fas fa-arrow-right"></i></button>
                    </article>
                <?php endforeach; ?>
            </div>
        </main>
    </div>
</div>

<?php include '../includes/footer.php'; ?>