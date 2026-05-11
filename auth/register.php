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
                $success = 'Account created successfully! Redirecting to login…';
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

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-icon"><i class="fas fa-user-plus"></i></div>
        <h1>Join <?php echo SITE_NAME; ?></h1>
        <p class="subtitle">Create your account and start sharing skills with peers.</p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><i class="fas fa-circle-exclamation"></i><span><?php echo htmlspecialchars($error); ?></span></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><i class="fas fa-circle-check"></i><span><?php echo htmlspecialchars($success); ?></span></div>
        <?php endif; ?>

        <form method="POST" class="register-form">
            <div class="form-group">
                <label for="full_name">Full name</label>
                <input type="text" id="full_name" name="full_name" placeholder="Jane Doe" value="<?php echo htmlspecialchars($full_name ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email address</label>
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
                <input type="password" id="password" name="password" placeholder="At least 6 characters" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm password</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="••••••••" required>
            </div>

            <button type="submit" class="button primary">Create account</button>
        </form>

        <p class="auth-link">
            Already have an account? <a href="login.php">Sign in</a>
        </p>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
