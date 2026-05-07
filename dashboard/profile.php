<?php
/**
 * Profile Page
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

// Create reviews table if not exists
$create_reviews_table = "CREATE TABLE IF NOT EXISTS reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    reviewer_id INT NOT NULL,
    reviewed_user_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reviewer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewed_user_id) REFERENCES users(id) ON DELETE CASCADE
)";

@$connection->query($create_reviews_table);

$user = [
    'first_name' => 'User',
    'last_name' => '',
    'username' => '',
    'email' => '',
];

if ($stmt = $connection->prepare('SELECT username, email, first_name, last_name FROM users WHERE id = ?')) {
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($username, $email, $first_name, $last_name);
    if ($stmt->fetch()) {
        $user = [
            'first_name' => $first_name ?: 'User',
            'last_name' => $last_name ?: '',
            'username' => $username,
            'email' => $email,
        ];
    }
    $stmt->close();
}

$helped = 0;
$total_points = 0;
$badges = 8;
$skills = [];
$skill_count = 0;
$reviews = [];

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
    $total_points = $skill_count * 80 + $helped * 25;
}

if ($stmt = $connection->prepare('SELECT skill_name, level FROM skills WHERE user_id = ? LIMIT 4')) {
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $skills[] = $row;
    }
    $stmt->close();
}

if ($stmt = $connection->prepare('SELECT u.first_name, u.last_name, r.rating, r.comment, r.created_at FROM reviews r JOIN users u ON r.reviewer_id = u.id WHERE r.reviewed_user_id = ? ORDER BY r.created_at DESC LIMIT 5')) {
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
    $stmt->close();
}

if (empty($reviews)) {
    $reviews = [
        ['first_name' => 'Sarah', 'last_name' => 'J.', 'rating' => 5, 'comment' => 'Amazing help with React! Very patient and knowledgeable.', 'created_at' => date('Y-m-d H:i:s')],
        ['first_name' => 'Mike', 'last_name' => 'C.', 'rating' => 5, 'comment' => 'Explained Python concepts clearly. Highly recommend!', 'created_at' => date('Y-m-d H:i:s')],
        ['first_name' => 'Emma', 'last_name' => 'W.', 'rating' => 4, 'comment' => 'Great mentor, helped me understand database normalization.', 'created_at' => date('Y-m-d H:i:s')],
    ];
}

if (empty($skills)) {
    $skills = [
        ['skill_name' => 'React', 'level' => 'Advanced'],
        ['skill_name' => 'Python', 'level' => 'Intermediate'],
        ['skill_name' => 'SQL', 'level' => 'Advanced'],
        ['skill_name' => 'UI/UX Design', 'level' => 'Beginner'],
    ];
}

$page_title = 'Profile';
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
            <a href="profile.php" class="active"><i class="fas fa-user"></i> Profile</a>
            <a href="#"><i class="fas fa-book"></i> Skills</a>
            <a href="#"><i class="fas fa-question-circle"></i> Help Requests</a>
            <a href="#"><i class="fas fa-plus-circle"></i> Create Request</a>
            <a href="#"><i class="fas fa-award"></i> Badges &amp; Levels</a>
            <a href="#"><i class="fas fa-search"></i> Search</a>
            <a href="#"><i class="fas fa-chart-bar"></i> Statistics</a>
        </nav>

        <a href="../auth/logout.php" class="sidebar-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </aside>

    <main class="dashboard-main">
        <div class="profile-content">
            <div class="profile-container">
                <div class="profile-card">
                    <div class="profile-header">
                        <div class="profile-avatar-large">
                            <?php echo strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)); ?>
                        </div>
                        <div class="profile-name"><?php echo htmlspecialchars(trim($user['first_name'] . ' ' . $user['last_name'])); ?></div>
                        <div class="profile-username">@<?php echo htmlspecialchars($user['username'] ?: 'profile'); ?></div>
                    </div>

                    <div class="profile-rating">
                        <div class="stars">
                            <span class="fas fa-star"></span>
                            <span class="fas fa-star"></span>
                            <span class="fas fa-star"></span>
                            <span class="fas fa-star"></span>
                            <span class="fas fa-star"></span>
                            <span class="rating-value">(4.8)</span>
                        </div>
                        <div class="profile-badge">
                            <span class="level-badge">Level 7 - Expert Helper</span>
                        </div>
                    </div>

                    <div class="profile-info">
                        <div class="info-item"><span class="info-icon fas fa-envelope"></span><span class="info-text"><?php echo htmlspecialchars($user['email']); ?></span></div>
                        <div class="info-item"><span class="info-icon fas fa-map-marker-alt"></span><span class="info-text">Paris, France</span></div>
                        <div class="info-item"><span class="info-icon fas fa-calendar-alt"></span><span class="info-text">Joined March 2025</span></div>
                    </div>

                    <div class="profile-stats">
                        <div class="stat">
                            <div class="stat-number"><?php echo number_format($helped); ?></div>
                            <div class="stat-label">Helped</div>
                        </div>
                        <div class="stat">
                            <div class="stat-number"><?php echo number_format($total_points); ?></div>
                            <div class="stat-label">Points</div>
                        </div>
                        <div class="stat">
                            <div class="stat-number"><?php echo number_format($badges); ?></div>
                            <div class="stat-label">Badges</div>
                        </div>
                    </div>

                    <button class="edit-profile-btn">Edit Profile</button>
                </div>

                <div class="profile-right">
                    <div class="about-section">
                        <div class="section-title">About Me</div>
                        <p class="about-text">Passionate software developer and mentor with 3+ years of experience in web development. I love helping fellow students understand complex concepts and solve challenging problems. Specializing in React, Python, and database design. Always eager to learn and share knowledge!</p>
                    </div>

                    <div class="skills-section">
                        <div class="skills-header">
                            <div>
                                <div class="section-title">My Skills</div>
                            </div>
                            <button class="manage-skills-btn">Manage Skills</button>
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
                                <div class="skill-item">
                                    <div class="skill-header">
                                        <div class="skill-name"><?php echo htmlspecialchars($skill['skill_name']); ?></div>
                                        <span class="skill-level <?php echo htmlspecialchars($level_class); ?>"><?php echo htmlspecialchars($skill['level']); ?></span>
                                    </div>
                                    <div class="skill-bar">
                                        <div class="skill-progress <?php echo htmlspecialchars($level_class); ?>" style="width: <?php echo $width; ?>%;"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="achievements-section">
                        <div class="achievements-header">
                            <span class="achievements-icon fas fa-trophy"></span>
                            <div>
                                <div class="section-title">Achievements</div>
                            </div>
                        </div>
                        <div class="achievements-grid">
                            <div class="achievement-card">
                                <div class="achievement-icon fas fa-star"></div>
                                <div class="achievement-title">Helper Hero</div>
                                <div class="achievement-desc">Helped 50 students</div>
                            </div>
                            <div class="achievement-card">
                                <div class="achievement-icon fas fa-bolt"></div>
                                <div class="achievement-title">Quick Responder</div>
                                <div class="achievement-desc">Average response time &lt; 1 hour</div>
                            </div>
                        </div>
                    </div>

                    <div class="reviews-section">
                        <div class="reviews-header">
                            <span class="reviews-icon fas fa-star"></span>
                            <div class="section-title">Recent Reviews</div>
                        </div>
                        <div class="reviews-list">
                            <?php foreach ($reviews as $review): ?>
                                <div class="review-card">
                                    <div class="review-header">
                                        <div>
                                            <div class="review-author"><?php echo htmlspecialchars(trim($review['first_name'] . ' ' . $review['last_name'])); ?></div>
                                            <div class="review-stars">
                                                <?php for ($i = 0; $i < $review['rating']; $i++): ?>
                                                    <span class="fas fa-star" style="color: #fbbf24;"></span>
                                                <?php endfor; ?>
                                                <?php for ($i = $review['rating']; $i < 5; $i++): ?>
                                                    <span class="fas fa-star" style="color: #e5e7eb;"></span>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="review-text"><?php echo htmlspecialchars($review['comment']); ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>