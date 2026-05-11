<?php
/**
 * Skills Management Page
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
$skills = [];

// Get user's skills
if ($stmt = $connection->prepare('SELECT id, skill_name, level, description FROM skills WHERE user_id = ? ORDER BY skill_name')) {
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $skills[] = $row;
    }
    $stmt->close();
}

// If no skills, add sample data
if (empty($skills)) {
    $skills = [
        ['id' => 1, 'skill_name' => 'React', 'level' => 'Advanced', 'description' => 'Frontend framework'],
        ['id' => 2, 'skill_name' => 'Python', 'level' => 'Intermediate', 'description' => 'Programming language'],
        ['id' => 3, 'skill_name' => 'SQL', 'level' => 'Advanced', 'description' => 'Database management'],
        ['id' => 4, 'skill_name' => 'UI/UX Design', 'level' => 'Beginner', 'description' => 'Design principles'],
    ];
}

$page_title = 'Skills';
include '../includes/header.php';
?>

<div class="app-shell">
    <?php $sidebar_active = 'skills'; include '../includes/dashboard_sidebar.php'; ?>

    <div class="app-main">
        <?php $topbar_title = 'Skills'; include '../includes/dashboard_topbar.php'; ?>

        <main class="app-content">
            <div class="page-header">
                <div>
                    <span class="eyebrow">Workspace</span>
                    <h1 class="page-title"><span class="accent">Skills</span> Management</h1>
                    <p class="page-subtitle">Add, edit, or remove your skills and expertise levels.</p>
                </div>
                <button class="button primary"><i class="fas fa-plus"></i> Add skill</button>
            </div>

            <div class="skills-grid">
                <?php foreach ($skills as $skill): ?>
                    <?php
                        $level_class = strtolower($skill['level']);
                        $width = 80;
                        if ($level_class === 'beginner') {
                            $width = 55;
                        } elseif ($level_class === 'intermediate') {
                            $width = 70;
                        }
                        if ($level_class === 'advanced') {
                            $width = 95;
                        }
                    ?>
                    <div class="skill-card">
                        <div class="skill-card-header">
                            <div>
                                <h3 class="skill-card-title"><?php echo htmlspecialchars($skill['skill_name']); ?></h3>
                                <div class="skill-meta">
                                    <span class="skill-level <?php echo htmlspecialchars($level_class); ?>"><?php echo htmlspecialchars($skill['level']); ?></span>
                                    <span class="skill-experience">3 years</span>
                                </div>
                            </div>
                            <div class="skill-actions">
                                <button class="skill-action-btn edit" title="Edit"><i class="fas fa-pen"></i></button>
                                <button class="skill-action-btn delete" title="Delete"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                        <div class="skill-bar">
                            <div class="skill-progress <?php echo htmlspecialchars($level_class); ?>" style="width: <?php echo $width; ?>%;"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
