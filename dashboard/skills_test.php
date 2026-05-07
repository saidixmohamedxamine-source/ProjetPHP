<?php
/**
 * Skills Test Page
 */

require_once '../includes/config.php';
require_once '../database/connection.php';
session_start();

echo "<h2>Skills Page Test</h2>";

if (!isset($_SESSION['user_id'])) {
    echo "<p style='color: red;'>ERROR: Not logged in. Redirecting...</p>";
    header('Location: ../auth/login.php');
    exit;
}

echo "<p style='color: green;'>✓ Session user_id: " . $_SESSION['user_id'] . "</p>";

$user_id = $_SESSION['user_id'];
$connection = $db->getConnection();

echo "<p>Fetching skills...</p>";

try {
    $stmt = $connection->prepare('SELECT id, skill_name, level, description FROM skills WHERE user_id = ? ORDER BY skill_name');
    
    if (!$stmt) {
        echo "<p style='color: red;'>Error: " . $connection->error . "</p>";
        die;
    }
    
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    echo "<p>✓ Query executed successfully</p>";
    echo "<p>Rows found: " . $result->num_rows . "</p>";
    
    $stmt->close();
    echo "<p style='color: green;'>✓ Connection working! Now go to <a href='skills.php'>skills.php</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>