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

<style>
body {
  background: linear-gradient(180deg, #f3e8ff 0%, #f8f4ff 100%);
}

.auth-container {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 60px 20px;
}

.auth-card {
  width: 100%;
  max-width: 440px;
  padding: 46px 38px;
  border-radius: 32px;
  background: #ffffff;
  box-shadow: 0 28px 70px rgba(99, 102, 241, 0.14);
  border: 1px solid rgba(99, 102, 241, 0.14);
  text-align: center;
}

.auth-icon {
  width: 72px;
  height: 72px;
  margin: 0 auto 24px;
  display: grid;
  place-items: center;
  border-radius: 22px;
  background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
  color: white;
  font-size: 28px;
}

.auth-card h1 {
  font-size: 32px;
  font-weight: 700;
  margin-bottom: 8px;
  color: #111827;
}

.auth-card .subtitle {
  font-size: 15px;
  color: #6b7280;
  margin-bottom: 28px;
  line-height: 1.7;
}

.login-form .form-group {
  margin-bottom: 18px;
  text-align: left;
}

.login-form label {
  display: block;
  margin-bottom: 8px;
  font-size: 14px;
  color: #374151;
}

.login-form input {
  width: 100%;
  border: 1px solid #d1d5db;
  border-radius: 16px;
  padding: 16px 18px;
  font-size: 15px;
  color: #111827;
  background: #f8fafc;
}

.login-form input:focus {
  outline: none;
  border-color: #6366f1;
  box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.14);
}

.form-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 12px;
  margin: 18px 0 18px;
  flex-wrap: wrap;
}

.checkbox-group {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  color: #4b5563;
  font-size: 14px;
}

.checkbox-group input {
  width: auto;
  margin: 0;
}

.forgot-link {
  color: #6366f1;
  font-size: 14px;
  font-weight: 600;
  text-decoration: none;
}

.forgot-link:hover {
  text-decoration: underline;
}

.auth-card .button.primary {
  width: 100%;
  padding: 16px 18px;
}

.auth-link {
  margin-top: 28px;
  color: #6b7280;
  font-size: 14px;
}

.auth-link a {
  color: #6366f1;
  font-weight: 600;
  text-decoration: none;
}

.auth-link a:hover {
  text-decoration: underline;
}
</style>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-icon"><i class="fas fa-sign-in-alt"></i></div>
        <h1><?php echo SITE_NAME; ?></h1>
        <p class="subtitle">Welcome back! Please login to continue.</p>

        <form method="POST" class="login-form">
            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="student@ismo.edu" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>

            <div class="form-row">
                <label class="checkbox-group">
                    <input type="checkbox" name="remember" /> Remember me
                </label>
                <a href="#" class="forgot-link">Forgot password?</a>
            </div>

            <button type="submit" class="button primary">Sign In</button>
        </form>

        <p class="auth-link">
            Don't have an account? <a href="register.php">Sign up</a>
        </p>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
