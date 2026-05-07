<?php
/**
 * Create Reviews Table
 */

require_once 'includes/config.php';

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("<p style='color: red;'>Connection failed: " . $conn->connect_error . "</p>");
    }

    echo "<h2>Creating Reviews Table</h2>";

    // Create reviews table
    $sql_reviews = "CREATE TABLE IF NOT EXISTS reviews (
        id INT PRIMARY KEY AUTO_INCREMENT,
        reviewer_id INT NOT NULL,
        reviewed_user_id INT NOT NULL,
        rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
        comment TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (reviewer_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (reviewed_user_id) REFERENCES users(id) ON DELETE CASCADE
    )";

    if ($conn->query($sql_reviews) === TRUE) {
        echo "<p style='color: green;'>✓ Reviews table created successfully</p>";
    } else {
        echo "<p style='color: red;'>✗ Error: " . $conn->error . "</p>";
    }

    $conn->close();

    echo "<p style='color: green;'><strong>✓ Done! You can now view reviews on the profile page.</strong></p>";
    echo "<p><a href='dashboard/profile.php'>Go to Profile</a></p>";

} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>