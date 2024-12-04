<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Yetkisiz
    exit;
}

$userId = $_SESSION['user_id'];
$query = "UPDATE users SET last_active = NOW() WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $userId);
$stmt->execute();
$stmt->close();
$conn->close();
?>