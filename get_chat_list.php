<?php
session_start();
include 'db_connection.php';
$currentUser = $_SESSION['user_id'];

// Kullanıcı rolünü al
$result = $conn->query("SELECT role FROM users WHERE user_id = $currentUser");
$user = $result->fetch_assoc();
$role = $user['role'];

if ($role === 'user') {
    $query = "SELECT user_id, 
                     username, 
                     profile_picture, 
                     IF(last_active IS NOT NULL AND TIMESTAMPDIFF(MINUTE, last_active, NOW()) <= 5, 1, 0) AS is_online 
              FROM users 
              WHERE role = 'mentor'";
} elseif ($role === 'mentor') {
    $query = "SELECT user_id, 
                     username, 
                     profile_picture, 
                     IF(last_active IS NOT NULL AND TIMESTAMPDIFF(MINUTE, last_active, NOW()) <= 5, 1, 0) AS is_online 
              FROM users 
              WHERE role = 'user'";
}

$result = $conn->query($query);
$chatList = [];
while ($row = $result->fetch_assoc()) {
    $chatList[] = $row;
}
echo json_encode($chatList);
$conn->close();
?>
