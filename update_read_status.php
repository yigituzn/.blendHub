<?php
session_start();
include 'db_connection.php';

// Giriş yapan kullanıcı
$currentUser = $_SESSION['user_id'] ?? null;

// Gönderici ID'si (mentorId)
$senderId = $_POST['sender_id'] ?? null;

// Eksik parametreleri kontrol et
if (!$currentUser) {
    http_response_code(400); // Yanlış istek
    echo json_encode(['success' => false, 'message' => 'Kullanıcı oturumu yok.']);
    exit;
}

if (!$senderId) {
    http_response_code(400); // Yanlış istek
    echo json_encode(['success' => false, 'message' => 'Eksik parametreler: sender_id gerekli.']);
    exit;
}

// Güncelleme sorgusu
$query = "UPDATE messages 
          SET is_read = 1 
          WHERE recipient_id = ? AND sender_id = ? AND is_read = 0";
$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $currentUser, $senderId);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Okuma durumu güncellendi.']);
} else {
    http_response_code(500); // Sunucu hatası
    echo json_encode(['success' => false, 'message' => 'Okuma durumu güncellenemedi.']);
}

$stmt->close();
$conn->close();
?>
