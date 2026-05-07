<?php
/**
 * Register Page
 */

require_once '../includes/config.php';
require_once '../database/connection.php';
session_start();

$page_title = 'Register';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    $first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
    $last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';

    // Validation
    if (empty($username) || empty($email) || empty($password)) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } else {
        // Check if username or email already exists
        $connection = $db->getConnection();
        $check_stmt = $connection->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
        $check_stmt->bind_param('ss', $username, $email);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = 'Username or email already exists.';
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new user
            $insert_stmt = $connection->prepare('INSERT INTO users (username, email, password, first_name, last_name) VALUES (?, ?, ?, ?, ?)');
            $insert_stmt->bind_param('sssss', $username, $email, $hashed_password, $first_name, $last_name);
            
            if ($insert_stmt->execute()) {
                $success = 'Account created successfully! Redirecting to login...';
                header('refresh:2;url=login.php');
            } else {
                $error = 'An error occurred while creating your account. Please try again.';
            }
            
            $insert_stmt->close();
        }
        
        $check_stmt->close();
    }
}

include '../includes/header.php';
?>

<div class="auth-container">
    <div class="auth-card">
        <h1><?php echo SITE_NAME; ?></h1>
        <p class="subtitle">Create your account</p>
        
        <form method="POST" class="register-form">
            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name">
            </div>

            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <button type="submit" class="button primary">Register</button>
        </form>

        <p class="auth-link">
            Already have an account? <a href="login.php">Login here</a>
        </p>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
