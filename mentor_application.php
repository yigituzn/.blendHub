<?php
session_start();

include 'db_connection.php';

// Form gönderildiğinde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $expertise = $_POST['expertise'];

    // E-posta zaten kayıtlı mı kontrol et
    $sql = "SELECT id FROM mentorshipapplications WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // E-posta zaten mevcut
        echo "<script>
                alert('Bu e-posta ile daha önce başvuru yapılmış!');
                window.location.href = 'index.php';
              </script>";
        exit;
    }

    $stmt->close();

    // Veritabanına başvuruyu ekle
    $sql = "INSERT INTO mentorshipapplications (full_name, email, expertise, status) VALUES (?, ?, ?, 'pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $fullName, $email, $expertise);

    if ($stmt->execute()) {
        echo "<script>
                alert('Başvurunuz başarıyla alındı!');
                window.location.href = 'index.php';
              </script>";
    } else {
        echo "<script>
                alert('Başvuru sırasında bir hata oluştu.');
                window.location.href = 'index.php';
              </script>";
    }

    $stmt->close();
}

$conn->close();
?>