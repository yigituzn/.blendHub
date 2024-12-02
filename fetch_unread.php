<?php
session_start();
include 'db_connection.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT COUNT(*) AS unread_count FROM messages WHERE recipient_id = ? AND is_read = 0";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

echo json_encode(['unread_count' => $result['unread_count']]);
?>