<?php
session_start();
$data = json_decode(file_get_contents('php://input'), true);
$conn = new mysqli('localhost', 'root', '', 'blendhub');
$currentUser = $_SESSION['user_id']; // Oturumdaki kullanıcı
$mentorId = $data['mentorId'];
$message = $conn->real_escape_string($data['message']);

$stmt = $conn->prepare("INSERT INTO messages (sender_id, recipient_id, message) VALUES (?, ?, ?)");
$stmt->bind_param('iis', $currentUser, $mentorId, $message);
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}

$conn->close();
?>