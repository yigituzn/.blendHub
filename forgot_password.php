<?php
$error_message = ''; // Hata mesajını tutmak için

// Veritabanı bağlantısı
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blendhub";
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

// Form gönderildiğinde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Şifrelerin uyuşup uyuşmadığını kontrol et
    if ($newPassword !== $confirmPassword) {
        $error_message = 'Şifreler uyuşmuyor! Lütfen tekrar deneyin.';
    } else {
        // E-posta kontrolü
        $sql = "SELECT email FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Şifreyi güncelle
            $stmt->close();
            $sql = "UPDATE users SET password = ? WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $newPassword, $email);

            if ($stmt->execute()) {
                echo "<script>alert('Şifre başarıyla güncellendi! Giriş yapabilirsiniz.'); window.location.href = 'login.html';</script>";
                exit;
            } else {
                $error_message = 'Şifre güncellenemedi. Lütfen tekrar deneyin!';
            }
        } else {
            $error_message = 'Bu e-posta ile kayıtlı bir kullanıcı bulunamadı!';
        }

        $stmt->close();
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="tr-TR">
<head>
  <meta charset="UTF-8">
  <title>.blendHub | Şifremi Unuttum</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@400;700&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      padding: 0;
      background-image: url('Images/accountpage.jpg');
      background-size: cover;
      background-repeat: no-repeat;
      font-family: 'Poppins', sans-serif;
    }

    .form-container {
      width: 450px;
      padding: 20px;
      position: absolute;
      top: 100px;
      left: 845px;
    }

    .form-header h1 {
      text-align: left;
      margin-bottom: 30px;
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      font-size: 42px;
      color: white;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      display: block;
      margin-bottom: 5px;
      color: white;
      font-weight: bold;
    }

    .form-group input {
      width: 100%;
      padding: 10px;
      border-radius: 5px;
      border: none;
      background: rgba(255, 255, 255, 0.2);
      color: white;
      outline: none;
      box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
    }

    .form-group input:focus {
      border: 2px solid #4CAF50;
    }

    .form-button {
      width: 100%;
      padding: 10px;
      border: none;
      border-radius: 5px;
      background: #40d045;
      color: white;
      font-size: 22px;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-top: 10px;
    }

    .form-button:hover {
      background: #45a049;
    }

    #error-message {
      color: red;
      font-weight: bold;
      margin-bottom: 25px;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <div class="form-header">
      <h1>Şifremi Unuttum</h1>
      <!-- Hata mesajını göstermek için -->
    <div id="error-message">
      <?php if (!empty($error_message)) echo $error_message; ?>
    </div>
    </div>

    <form method="POST" action="" id="resetForm">
      <div class="form-group">
        <label for="email">E-posta</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="form-group">
        <label for="new-password">Yeni Şifre</label>
        <input type="password" id="new-password" name="new_password" required>
      </div>
      <div class="form-group">
        <label for="confirm-password">Yeni Şifreyi Doğrula</label>
        <input type="password" id="confirm-password" name="confirm_password" required>
      </div>
      <button type="submit" class="form-button">Şifreyi Güncelle</button>
    </form>
    <!-- Geri Dön Butonu -->
    <div style="margin-top: 20px;">
      <a href="login.php" style="text-decoration: none; color: green; font-size: 18px; display: flex; align-items: center;">
        <span style="font-size: 24px; margin-right: 5px;">&larr;</span>
      </a>
    </div>
  </div>

  <script>
    // Form gönderilmeden önce şifreleri kontrol et
    document.getElementById("resetForm").addEventListener("submit", function(event) {
      const newPassword = document.getElementById("new-password").value;
      const confirmPassword = document.getElementById("confirm-password").value;

      if (newPassword !== confirmPassword) {
        event.preventDefault(); // Form gönderimini durdur
        document.getElementById("error-message").innerText = "Şifreler uyuşmuyor! Lütfen tekrar deneyin.";
      }
    });
  </script>
</body>
</html>
