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
    $categories = isset($_POST['categories']) ? $_POST['categories'] : [];

    $query = "INSERT INTO posts (user_id, title, content, status) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isss", $user_id, $title, $content, $status);
    $post_id = $stmt->insert_id;

    if (!empty($categories)) {
        $category_query = "INSERT INTO postcategories (post_id, category_id) VALUES (?, ?)";
        $category_stmt = $conn->prepare($category_query);

        foreach ($categories as $category_id) {
            $category_stmt->bind_param("ii", $post_id, $category_id);
            $category_stmt->execute();
        }
        $category_stmt->close();
    }

    if ($stmt->execute()) {
        echo "<script>alert('Gönderiniz incelenmek üzere gönderildi!'); window.location.href = 'index.php';</script>";
    } else {
        echo "Hata: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>