<?php
if (isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $allowed_extensions = ['jpg', 'jpeg', 'png'];

    // Dosya uzantısını kontrol et
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($file_extension, $allowed_extensions)) {
        http_response_code(400);
        die(json_encode(['error' => 'Geçersiz dosya formatı.']));
    }

    // Dosyayı bir dizine yükle
    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_name = uniqid() . '.' . $file_extension;
    $file_path = $upload_dir . $file_name;

    if (move_uploaded_file($file['tmp_name'], $file_path)) {
        echo json_encode(['location' => $file_path]);
    } else {
        http_response_code(500);
        die(json_encode(['error' => 'Dosya yüklenemedi.']));
    }
}
?>