<?php
session_start();
$error_message = ''; // Hata mesajını tutmak için

include 'db_connection.php';

// Form gönderildiğinde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Kullanıcıyı e-posta ile bul
    $sql = "SELECT user_id, username, password, slug, profile_picture FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $username, $dbPassword, $slug, $profile_picture);
        $stmt->fetch();

        // Şifreyi kontrol et
        if ($password === $dbPassword) { // Şifre hash'lenmişse password_verify() kullanılmalı
            // Oturum değişkenlerini ayarla
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['slug'] = $slug; // Slug bilgisini oturuma ekle
            $_SESSION['profile_picture'] = $profile_picture; // Profil fotoğrafını oturuma ekle
            $_SESSION['user_email'] = $_POST['email'];
            // Giriş başarılı, yönlendir
            echo "<script>window.location.href = 'index.php';</script>";
            exit; // İşlemi sonlandır
        } else {
            $error_message = 'Geçersiz şifre!';
        }
    } else {
        $error_message = 'Bu e-posta ile kayıtlı bir kullanıcı bulunamadı!';
    }

    $stmt->close();
}
$conn->close();

if (isset($_SESSION['user_id'])) {
  header('Location: index.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="tr-TR">
<head>
  <meta charset="utf-8">
  <title>.blendHub | Giriş Yap</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@400;700&display=swap" rel="stylesheet">
  <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">
  <link rel="icon" href="images/favicon.png" type="image/x-icon">
  <style>
    body {
      margin: 0;
      padding: 0;
      background-image: url('images/accountpage.jpg');
      background-size: cover;
      background-repeat: no-repeat;
      font-family: 'Poppins', sans-serif;
      font-weight: 400;
    }

    input {
      font-family: 'Poppins', sans-serif; /* Poppins fontu */
      font-weight: 400; /* Bold */
      color: white;
    }

    input::placeholder {
      font-family: 'Poppins', sans-serif; /* Poppins fontu */
      font-weight: 400; /* Bold */
      color: rgba(0, 0, 0, 0.5); /* Placeholder rengi (isteğe bağlı) */
      opacity: 1; /* Tüm tarayıcılarda görünebilirlik */
    }
    .bold-text {
      font-weight: 700; /* Bold yazı */
    }
    .form-container {
      width: 450px;
      /*margin: 300px 250px 50px auto;*/
      padding: 20px;
      position: absolute;
      top: 135px;
      left: 845px;
    }
    .form-container a {
        text-decoration: none;
        color: white;
    } 
    .form-container a:visited {
        text-decoration: none;
        color: white;
    }
    .form-header h1 {
        text-align: left;
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 42px;
        color: white;
        opacity: 1;
    }
    .form-header h2 {
        text-align: left;
        margin-bottom: 70px;
        font-family: 'Poppins', sans-serif;
        font-weight: 400;
        font-size: 20px;
        color: white;
        opacity: 1;
    }
    .form-group input {
      width: 430px; /* Form genişliğine uyum sağlar */
      height: 5px; /* Sabit yükseklik */
      padding: 10px; /* Kenar boşluğu */
      margin-top: 0px;
      /*margin-bottom: 34px; /* Alanlar arasında boşluk */
      font-size: 14px; /* Yazı boyutu */
      /*border: 1px solid #FFFFFF;*/
      border: none;
      border-radius: 5px; /* Köşeleri yuvarla */
      outline: none;
      box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
      background-color: rgba(255, 255, 255, 0.2);
    }
    .form-group {
      margin-bottom: 15px;
    }
    .form-group label {
      display: block;
      margin-bottom: 5px;
    }
    .form-group input:focus {
      border-color: #4CAF50;
    }
    .form-button {
      width: 490px; /* Buton genişliği */
      height: 50px; /* Buton yüksekliği */
      margin-top: 55px;
      margin-bottom: 10px;
      background-color: #40d045; /* Buton arka plan rengi */
      color: white; /* Yazı rengi */
      font-size: 22px; /* Yazı boyutu */
      font-family: 'Poppins', sans-serif; /* Yazı tipi */
      font-weight: 700;
      border: none; /* Çerçeve kaldır */
      border-radius: 10px; /* Çerçeve köşelerini yuvarla */
      cursor: pointer; /* Tıklanabilir işaretçi */
      transition: all 0.3s ease; /* Yumuşak geçiş efekti */
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Hafif gölge efekti */
    }

    .form-button:hover {
      background-color: #45a049; /* Hover (üzerine gelince) rengi */
      box-shadow: 0 6px 10px rgba(0, 0, 0, 0.3); /* Hover gölgesi */
    }

    .form-button:active {
      transform: scale(0.98); /* Tıklanınca hafif küçültme efekti */
      box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2); /* Tıklama gölgesi */
    }
    .login-link {
      text-align: center;
      margin-top: 20px;
      color: white;
    }
    .login-link a {
      color: white;
      text-decoration: none;
    }
    
    .password-wrapper {
      position: relative; /* Göz ikonunu inputa göre hizalamak için gerekli */
      width: 100%;
    }

    .password-wrapper input {
      width: 100%; /* Genişlik ayarlanır */
      /*padding-right: 30px; /* Göz ikonu için boşluk bırakılır */
      height: 25px; /* Input yüksekliği */
      font-size: 14px;
      border: none;
      border-radius: 5px;
      box-sizing: border-box;
      outline: none;
      box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
    }

    .toggle-password {
      position: absolute; /* Input'un sağında hizalama için */
      top: 50%; /* Dikeyde ortala */
      left: 460px;
      transform: translateY(-20%); /* Dikey hizalamayı tamamla */
      width: 30px; /* İkonun genişliği */
      height: auto; /* İkonun yüksekliği */
      cursor: pointer;
    }

    .toggle-password:hover {
      opacity: 0.8; /* Hover efekti */
    }
    label {
      color: white;
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      font-size: 20px;
    }
    #error-message {
      color: red;
      font-weight: bold;
      margin-bottom: 15px;
    }
</style>
</head>
<body>
    <div class="form-container">
        <div class="form-header">
          <h1>Giriş Yap</h1>
          <h2>Hoşgeldin! Siteye erişebilmek için giriş yap.<br><span class="bold-text"><a href="forgot_password.php">Şifrenizi mi unuttunuz?</a></span></h2>
        </div>
        <form id="registerForm" method="POST" action="">
          <div class="form-group">
            <label class="bold-text" for="email">E-posta Adresi</label>
            <input type="email" id="email" name="email" required>
            <hr style="height:2px;width:490px;background-color:white">
          </div>
          <div class="form-group password-container">
            <div class="password-wrapper">
              <label class="bold-text" for="password">Şifre</label>
              <input type="password" id="password" name="password" required>
              <img id="togglePassword" class="toggle-password" src="images/eye.png">
              <hr style="height:2px;width:490px;background-color:white">
            </div>
            <br>
            <div id="error-message">
                <?php if (!empty($error_message)) echo $error_message; ?>
            </div>
          </div>

          <button type="submit" class="form-button">Giriş Yap</button>
        </form>
        <div class="login-link">
          Hesabınız Yok mu? <a href="#" onclick="redirectToLogin()"><span class="bold-text">Kayıt Ol</span></a>
        </div>
      </div>
<script>
    function redirectToLogin() {
      window.location.href = "register.php"; // Giriş yapma sayfasının URL'sini buraya yazın
    }
      
    // Şifre alanı için
    function initPasswordToggles() {
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    togglePassword.addEventListener('click', function () {
        const isPasswordVisible = passwordInput.type === 'text';
        passwordInput.type = isPasswordVisible ? 'password' : 'text';
        togglePassword.src = isPasswordVisible ? 'images/eye.png' : 'images/open-eye.png';
    });

    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const confirmPasswordInput = document.getElementById('confirm-password');

    toggleConfirmPassword.addEventListener('click', function () {
        const isConfirmPasswordVisible = confirmPasswordInput.type === 'text';
        confirmPasswordInput.type = isConfirmPasswordVisible ? 'password' : 'text';
        toggleConfirmPassword.src = isConfirmPasswordVisible ? 'images/eye.png' : 'images/open-eye.png';
    });
}

// Sayfa yüklendiğinde veya PHP'den dönen hata sonrası olayları yeniden bağla
window.onload = initPasswordToggles;
  </script>
</body>
</html>