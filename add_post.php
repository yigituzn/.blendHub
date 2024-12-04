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
    $featured_image = null;

    }
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
        $image = file_get_contents($_FILES['featured_image']['tmp_name']);
        $featured_image = base64_encode($image);
    }

    // Veritabanına ekleme
    $query = "INSERT INTO posts (user_id, title, content, featured_image, status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("issss", $user_id, $title, $content, $featured_image, $status);

    if ($stmt->execute()) {
        echo "<script>alert('Gönderiniz incelenmek üzere gönderildi!'); window.location.href = 'index.php';</script>";
    } else {
        echo "Hata: " . $stmt->error;
    }

$stmt->close();
$conn->close();
?>
?>
