<?php
session_start();
include 'db_connection.php';
$currentUser = $_SESSION['user_id']; // Giriş yapan kullanıcı ID'si
$mentorId = $_GET['mentorId']; // Sohbet yapılan kişi ID'si

$result = $conn->query("
    SELECT m.message, 
           m.is_read,
           u.username AS sender_name,
           IF(m.sender_id = $currentUser, 1, 0) AS is_current_user
    FROM messages m 
    JOIN users u ON m.sender_id = u.user_id 
    WHERE (m.sender_id = $currentUser AND m.recipient_id = $mentorId) 
       OR (m.sender_id = $mentorId AND m.recipient_id = $currentUser)
    ORDER BY m.created_at ASC
");

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode($messages, JSON_PRETTY_PRINT);
?>