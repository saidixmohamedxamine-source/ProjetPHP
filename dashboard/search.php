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

$skills = ['React', 'Python', 'SQL', 'JavaScript', 'UI/UX', 'Git', 'Node.js', 'Java', 'CSS', 'MongoDB'];
$mentor_levels = ['All Levels', 'Beginner', 'Intermediate', 'Advanced', 'Expert'];
$availability_options = ['Any Time', 'Available Now', 'Available Today', 'Available Soon'];

$mentors = [
    [
        'name' => 'Alex Martinez',
        'initials' => 'AM',
        'rating' => 4.8,
        'reviews' => 45,
        'skills' => ['React', 'JavaScript', 'CSS'],
        'location' => 'Paris, France',
        'sessions' => 45,
        'availability' => 'Available Now',
        'level' => 'Expert',
    ],
    [
        'name' => 'Sarah Johnson',
        'initials' => 'SJ',
        'rating' => 4.9,
        'reviews' => 67,
        'skills' => ['Python', 'SQL', 'MongoDB'],
        'location' => 'Lyon, France',
        'sessions' => 67,
        'availability' => 'Available Today',
        'level' => 'Advanced',
    ],
    [
        'name' => 'David Lee',
        'initials' => 'DL',
        'rating' => 4.7,
        'reviews' => 38,
        'skills' => ['Java', 'Git', 'Node.js'],
        'location' => 'Berlin, Germany',
        'sessions' => 38,
        'availability' => 'Any Time',
        'level' => 'Intermediate',
    ],
    [
        'name' => 'Emma Thompson',
        'initials' => 'ET',
        'rating' => 5.0,
        'reviews' => 53,
        'skills' => ['UI/UX', 'React', 'CSS'],
        'location' => 'Madrid, Spain',
        'sessions' => 53,
        'availability' => 'Available Now',
        'level' => 'Expert',
    ],
    [
        'name' => 'Luca Rossi',
        'initials' => 'LR',
        'rating' => 4.6,
        'reviews' => 29,
        'skills' => ['Python', 'JavaScript', 'Git'],
        'location' => 'Rome, Italy',
        'sessions' => 29,
        'availability' => 'Available Today',
        'level' => 'Advanced',
    ],
    [
        'name' => 'Nina Kim',
        'initials' => 'NK',
        'rating' => 4.8,
        'reviews' => 42,
        'skills' => ['SQL', 'MongoDB', 'Node.js'],
        'location' => 'Seoul, South Korea',
        'sessions' => 42,
        'availability' => 'Any Time',
        'level' => 'Advanced',
    ],
];

$page_title = 'Search';
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
            <a href="create_request.php"><i class="fas fa-plus-circle"></i> Create Request</a>
            <a href="badges_levels.php"><i class="fas fa-award"></i> Badges &amp; Levels</a>
            <a href="search.php" class="active"><i class="fas fa-search"></i> Search</a>
            <a href="statistics.php"><i class="fas fa-chart-bar"></i> Statistics</a>
        </nav>

        <a href="../auth/logout.php" class="sidebar-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </aside>

    <main class="dashboard-main">
        <div class="search-page">
            <div class="page-header-row">
                <div>
                    <h1 class="page-title">Search Mentors</h1>
                    <p class="page-subtitle">Find the perfect mentor based on skills, level, and availability.</p>
                </div>
                <input type="text" class="search-input" placeholder="Search by name, skill, or location...">
            </div>

            <div class="search-grid">
                <aside class="search-sidebar">
                    <div class="filter-card">
                        <div class="filter-header">
                            <i class="fas fa-filter"></i>
                            <h2>Filters</h2>
                        </div>

                        <div class="filter-section">
                            <h3>Skills</h3>
                            <div class="filter-tags">
                                <?php foreach ($skills as $skill): ?>
                                    <span class="filter-tag"><?php echo htmlspecialchars($skill); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="filter-section">
                            <h3>Mentor Level</h3>
                            <select class="sort-select form-select">
                                <?php foreach ($mentor_levels as $level): ?>
                                    <option><?php echo htmlspecialchars($level); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="filter-section">
                            <h3>Availability</h3>
                            <select class="sort-select form-select">
                                <?php foreach ($availability_options as $option): ?>
                                    <option><?php echo htmlspecialchars($option); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <button class="primary-button clear-filters-button">Clear Filters</button>
                    </div>
                </aside>

                <section>
                    <div class="results-header">
                        <div class="results-count"><?php echo count($mentors); ?> mentors found</div>
                        <select class="sort-select">
                            <option>Sort by: Rating (High to Low)</option>
                            <option>Sort by: Sessions (High to Low)</option>
                            <option>Sort by: Availability</option>
                        </select>
                    </div>

                    <div class="search-results">
                        <?php foreach ($mentors as $mentor): ?>
                            <div class="mentor-card">
                                <div class="mentor-card-left">
                                    <div class="mentor-avatar"><?php echo htmlspecialchars($mentor['initials']); ?></div>
                                    <div>
                                        <h2><?php echo htmlspecialchars($mentor['name']); ?></h2>
                                        <div class="mentor-rating">
                                            <span class="fas fa-star" style="color: #f59e0b;"></span>
                                            <span class="fas fa-star" style="color: #f59e0b;"></span>
                                            <span class="fas fa-star" style="color: #f59e0b;"></span>
                                            <span class="fas fa-star" style="color: #f59e0b;"></span>
                                            <span class="fas fa-star-half-alt" style="color: #f59e0b;"></span>
                                            <?php echo htmlspecialchars($mentor['rating']); ?> (<?php echo htmlspecialchars($mentor['reviews']); ?> reviews)
                                        </div>
                                        <div class="mentor-tags">
                                            <?php foreach ($mentor['skills'] as $skill): ?>
                                                <span><?php echo htmlspecialchars($skill); ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                        <div class="mentor-meta">
                                            <span><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($mentor['location']); ?></span>
                                            <span><i class="fas fa-user-graduate"></i> <?php echo htmlspecialchars($mentor['sessions']); ?> sessions</span>
                                            <span><i class="fas fa-clock"></i> <?php echo htmlspecialchars($mentor['availability']); ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mentor-card-right">
                                    <span class="mentor-badge"><?php echo htmlspecialchars($mentor['level']); ?></span>
                                    <button class="primary-button request-help-button">Request Help</button>
                                    <button class="secondary-button view-profile-button">View Profile</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            </div>
        </div>
    </main>
</div>

<?php include '../includes/footer.php';
