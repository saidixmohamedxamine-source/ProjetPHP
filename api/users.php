<?php
/**
 * Users API Endpoint
 */

header('Content-Type: application/json');

require_once '../includes/config.php';
require_once '../database/connection.php';

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Simple API routing
switch ($method) {
    case 'GET':
        getUsers();
        break;
    case 'POST':
        createUser();
        break;
    case 'PUT':
        updateUser();
        break;
    case 'DELETE':
        deleteUser();
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method Not Allowed']);
        break;
}

/**
 * Get all users or specific user
 */
function getUsers() {
    global $db;
    
    if (isset($_GET['id'])) {
        // Get specific user
        $user_id = intval($_GET['id']);
        
        $sql = "SELECT id, username, email, first_name, last_name, bio, role FROM users WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            echo json_encode($result->fetch_assoc());
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'User not found']);
        }
    } else {
        // Get all users
        $sql = "SELECT id, username, email, first_name, last_name, role FROM users LIMIT 50";
        $result = $db->query($sql);
        
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        
        echo json_encode($users);
    }
}

/**
 * Create new user
 */
function createUser() {
    global $db;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate required fields
    if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        return;
    }
    
    // TODO: Hash password and validate email
    $username = $data['username'];
    $email = $data['email'];
    $password = password_hash($data['password'], PASSWORD_DEFAULT);
    $first_name = isset($data['first_name']) ? $data['first_name'] : '';
    $last_name = isset($data['last_name']) ? $data['last_name'] : '';
    $role = isset($data['role']) ? trim($data['role']) : 'Stagiaire';
    $allowed_roles = ['Stagiaire', 'Formateur', 'Administrateur'];
    if (!in_array($role, $allowed_roles, true)) {
        $role = 'Stagiaire';
    }
    
    $sql = "INSERT INTO users (username, email, password, first_name, last_name, role) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('ssssss', $username, $email, $password, $first_name, $last_name, $role);
    
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(['id' => $stmt->insert_id, 'message' => 'User created successfully']);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Failed to create user']);
    }
}

/**
 * Update user
 */
function updateUser() {
    // TODO: Implement update logic
    echo json_encode(['message' => 'Update not implemented yet']);
}

/**
 * Delete user
 */
function deleteUser() {
    // TODO: Implement delete logic
    echo json_encode(['message' => 'Delete not implemented yet']);
}
