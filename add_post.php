<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    die("Lütfen giriş yapın!");
}

include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];
    $status = 'pending';

    // Öne çıkan görsel
    $featured_image = null;
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
        $image = file_get_contents($_FILES['featured_image']['tmp_name']);
        $featured_image = base64_encode($image);
    }

    // Blog gönderisini kaydet
    $query = "INSERT INTO posts (user_id, title, content, status) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isss", $user_id, $title, $content, $status);

    if ($stmt->execute()) {
        echo "<script>alert('Gönderiniz incelenmek üzere gönderildi!'); window.location.href = 'index.php';</script>";
    } else {
        echo "Hata: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>