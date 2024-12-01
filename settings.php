<?php
session_start();

// Kullanıcı oturum kontrolü
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db_connection.php';

$user_id = $_SESSION['user_id'];
$error_message = '';
$success_message = '';

// Profil fotoğrafını güncelle
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
    $image = $_FILES['profile_picture']['tmp_name'];
    $image_data = file_get_contents($image); // Dosya içeriğini al
    $base64_image = base64_encode($image_data); // Base64'e çevir

    // Veritabanını güncelle
    $sql = "UPDATE users SET profile_picture = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $base64_image, $user_id);

    if ($stmt->execute()) {
        $success_message = "Profil fotoğrafınız başarıyla güncellendi!";
        $_SESSION['profile_picture'] = $base64_image; // Oturum değişkenini güncelle
    } else {
        $error_message = "Veritabanı güncellenirken hata oluştu.";
    }

    $stmt->close();
}

// Kullanıcı mevcut profil fotoğrafını çek
$sql = "SELECT profile_picture FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($profile_picture);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ayarlar</title>
</head>
<body>
    <h1>Ayarlar</h1>
    <?php if (!empty($error_message)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
    <?php if (!empty($success_message)): ?>
        <p style="color: green;"><?php echo htmlspecialchars($success_message); ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label for="profile_picture">Profil Fotoğrafı:</label>
        <br>
        <?php if (!empty($profile_picture)): ?>
            <img src="data:image/jpeg;base64,<?php echo htmlspecialchars($profile_picture); ?>" alt="Profil Fotoğrafı" width="100">
        <?php else: ?>
            <img src="data:image/jpeg;base64,<?php echo htmlspecialchars(base64_encode(file_get_contents('images/dprofile.jpg'))); ?>" width="100">
        <?php endif; ?>
        <br><br>
        <input type="file" name="profile_picture" id="profile_picture" required>
        <br><br>
        <button type="submit">Güncelle</button>
    </form>
</body>
</html>