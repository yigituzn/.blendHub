<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'blendhub');
$currentUser = $_SESSION['user_id'];

// Kullanıcı rolünü al
$result = $conn->query("SELECT role FROM users WHERE user_id = $currentUser");
$user = $result->fetch_assoc();
$role = $user['role'];

// Kullanıcıysa mentörleri listele
if ($role === 'user') {
    $query = "SELECT user_id, username, profile_picture AS profile_picture 
              FROM users 
              WHERE role = 'mentor'";
} 
// Mentörse kullanıcıları listele
elseif ($role === 'mentor') {
    $query = "SELECT user_id, username, profile_picture AS profile_picture 
              FROM users 
              WHERE role = 'user'";
}

$result = $conn->query($query);
$chatList = [];
while ($row = $result->fetch_assoc()) {
    $chatList[] = $row;
}
echo json_encode($chatList);
?>
