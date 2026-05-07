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

<div class="dashboard-page">
    <aside class="dashboard-sidebar">
        <div class="sidebar-brand">
            <div class="brand-title">ISMO-SkillSwap</div>
            <div class="brand-subtitle">Student Skill Sharing</div>
        </div>

        <nav class="dashboard-menu">
            <a href="index.php"><i class="fas fa-home"></i> Dashboard</a>
            <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
            <a href="skills.php" class="active"><i class="fas fa-book"></i> Skills</a>
            <a href="#"><i class="fas fa-question-circle"></i> Help Requests</a>
            <a href="#"><i class="fas fa-plus-circle"></i> Create Request</a>
            <a href="#"><i class="fas fa-award"></i> Badges &amp; Levels</a>
            <a href="#"><i class="fas fa-search"></i> Search</a>
            <a href="#"><i class="fas fa-chart-bar"></i> Statistics</a>
        </nav>

        <a href="../auth/logout.php" class="sidebar-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </aside>

    <main class="dashboard-main">
        <div class="skills-management-page">
            <div class="skills-header">
                <div>
                    <h1>Skills Management</h1>
                    <p>Add, edit, or remove your skills and expertise levels.</p>
                </div>
                <button class="add-skill-btn"><i class="fas fa-plus"></i> Add Skill</button>
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
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>
