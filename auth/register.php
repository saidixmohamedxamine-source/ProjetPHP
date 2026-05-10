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
    $full_name = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    $role = isset($_POST['role']) ? trim($_POST['role']) : 'Stagiaire';
    $allowed_roles = ['Stagiaire', 'Formateur', 'Administrateur'];
    if (!in_array($role, $allowed_roles, true)) {
        $role = 'Stagiaire';
    }

    $name_parts = preg_split('/\s+/', $full_name, 2, PREG_SPLIT_NO_EMPTY);
    $first_name = $name_parts[0] ?? '';
    $last_name = $name_parts[1] ?? '';

    // Validation
    if (empty($full_name) || empty($email) || empty($password)) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } else {
        $connection = $db->getConnection();

        $email_stmt = $connection->prepare('SELECT id FROM users WHERE email = ?');
        $email_stmt->bind_param('s', $email);
        $email_stmt->execute();
        $email_stmt->store_result();

        if ($email_stmt->num_rows > 0) {
            $error = 'Email already exists.';
        }

        $email_stmt->close();

        if (empty($error)) {
            $base_username = preg_replace('/[^a-z0-9]/', '', strtolower($first_name . ($last_name ? '.' . $last_name : '')));
            if (empty($base_username)) {
                $base_username = preg_replace('/[^a-z0-9]/', '', strtolower(strstr($email, '@', true)));
            }
            if (empty($base_username)) {
                $base_username = 'user' . time();
            }

            $username = $base_username;
            $suffix = 1;

            while ($stmt = $connection->prepare('SELECT id FROM users WHERE username = ?')) {
                $stmt->bind_param('s', $username);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $username = $base_username . $suffix;
                    $suffix++;
                    $stmt->close();
                    continue;
                }

                $stmt->close();
                break;
            }

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_stmt = $connection->prepare('INSERT INTO users (username, email, password, first_name, last_name, role) VALUES (?, ?, ?, ?, ?, ?)');
            $insert_stmt->bind_param('ssssss', $username, $email, $hashed_password, $first_name, $last_name, $role);

            if ($insert_stmt->execute()) {
                $success = 'Account created successfully! Redirecting to login...';
                header('refresh:2;url=login.php');
            } else {
                $error = 'An error occurred while creating your account. Please try again.';
            }

            $insert_stmt->close();
        }
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

.register-form .form-group {
  margin-bottom: 18px;
  text-align: left;
}

.register-form label {
  display: block;
  margin-bottom: 8px;
  font-size: 14px;
  color: #374151;
}

.register-form input,
.register-form select {
  width: 100%;
  border: 1px solid #d1d5db;
  border-radius: 16px;
  padding: 16px 18px;
  font-size: 15px;
  color: #111827;
  background: #f8fafc;
}

.register-form input:focus,
.register-form select:focus {
  outline: none;
  border-color: #6366f1;
  box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.14);
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
        <div class="auth-icon"><i class="fas fa-user-plus"></i></div>
        <h1>Join <?php echo SITE_NAME; ?></h1>
        <p class="subtitle">Create your account to start sharing skills.</p>
        
        <form method="POST" class="register-form">
            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" placeholder="John Doe" value="<?php echo htmlspecialchars($full_name ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="student@ismo.edu" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role">
                    <option value="Stagiaire" <?php echo isset($role) && $role === 'Stagiaire' ? 'selected' : ''; ?>>Stagiaire</option>
                    <option value="Formateur" <?php echo isset($role) && $role === 'Formateur' ? 'selected' : ''; ?>>Formateur</option>
                    <option value="Administrateur" <?php echo isset($role) && $role === 'Administrateur' ? 'selected' : ''; ?>>Administrateur</option>
                </select>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="••••••••" required>
            </div>

            <button type="submit" class="button primary">Create Account</button>
        </form>

        <p class="auth-link">
            Already have an account? <a href="login.php">Sign in</a>
        </p>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
