<?php
include 'db_connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id']) && isset($_POST['status'])) {
    $post_id = (int)$_POST['post_id'];
    $status = $_POST['status'];

    // Blog status'unu güncelle
    $update_query = "UPDATE posts SET status = ? WHERE post_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $status, $post_id);
    $stmt->execute();

    // Eğer status published olduysa ve session'da kategori bilgisi varsa
    if ($status === 'published' && isset($_SESSION['pending_categories'][$post_id])) {
        $categories = $_SESSION['pending_categories'][$post_id];

        // Kategorileri postcategories tablosuna ekle
        $insert_category_query = "INSERT INTO postcategories (post_id, category_id) VALUES (?, ?)";
        $category_stmt = $conn->prepare($insert_category_query);

        foreach ($categories as $category_id) {
            $category_stmt->bind_param("ii", $post_id, $category_id);
            $category_stmt->execute();
        }

        unset($_SESSION['pending_categories'][$post_id]); // İşlenen kategoriyi temizle
        $category_stmt->close();
    }

    $stmt->close();
    header("Location: admin_panel.php"); // Admin paneline yönlendir
    exit();
} else {
    echo "Yetkisiz işlem!";
}
?>
