<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'blendhub');
$currentUser = $_SESSION['user_id'];

$result = $conn->query("SELECT role FROM users WHERE user_id = $currentUser");
$user = $result->fetch_assoc();
echo json_encode(['role' => $user['role']]);
$conn->close();
?>
