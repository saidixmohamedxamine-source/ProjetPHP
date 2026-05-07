<?php
/**
 * Login Page
 */

require_once '../includes/config.php';
require_once '../database/connection.php';
session_start();

$page_title = 'Login';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($email) || empty($password)) {
        $error = 'Email and password are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format.';
    } else {
        // Check user credentials
        $connection = $db->getConnection();
        $stmt = $connection->prepare('SELECT id, username, password, first_name, last_name FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Login successful
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['username'] = $user['username'];

                header('Location: ../dashboard/index.php');
                exit;
            } else {
                $error = 'Invalid email or password.';
            }
        } else {
            $error = 'Invalid email or password.';
        }

        $stmt->close();
    }
}

include '../includes/header.php';
?>

<div class="auth-container">
    <div class="auth-card">
        <h1><?php echo SITE_NAME; ?></h1>
        <p class="subtitle"><?php echo SITE_SUBTITLE; ?></p>
        
        <form method="POST" class="login-form">
            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="button primary">Login</button>
        </form>

        <p class="auth-link">
            Don't have an account? <a href="register.php">Register here</a>
        </p>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
