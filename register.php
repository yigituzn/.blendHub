<?php
$error_message = ''; // Hata mesajı için bir değişken tanımlayın

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
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    // Şifre doğrulama
    if ($password !== $confirmPassword) {
        $error_message = 'Şifreler uyuşmuyor!';
    } else {
        // E-posta kontrolü
        $sql = "SELECT email FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error_message = 'Bu e-posta zaten kayıtlı!';
        } else {
            // Benzersiz slug oluştur
            $slug = strtolower(str_replace(' ', '-', $fullname)) . '-' . rand(1000, 9999);

            // Slug'ın benzersizliğini kontrol et
            $checkSlugSql = "SELECT user_id FROM users WHERE slug = ?";
            $slugStmt = $conn->prepare($checkSlugSql);
            $slugStmt->bind_param("s", $slug);
            $slugStmt->execute();
            $slugStmt->store_result();

            while ($slugStmt->num_rows > 0) {
                $slug = strtolower(str_replace(' ', '-', $fullname)) . '-' . rand(1000, 9999);
                $slugStmt->execute();
            }

            $slugStmt->close();

            $sql = "INSERT INTO users (username, email, password, slug) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $fullname, $email, $password, $slug);

            if ($stmt->execute()) {
                echo "<script>alert('Kayıt başarılı! Giriş yapabilirsiniz.'); window.location.href = 'login.php';</script>";
                exit;
            } else {
                $error_message = 'Kayıt başarısız: ' . $stmt->error;
            }
        }

        $stmt->close();
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="tr-TR">
<head>
  <meta charset="utf-8">
  <title>.blendHub | Kayıt Ol</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@400;700&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      padding: 0;
      background-image: url('Images/accountpage.jpg');
      background-size: cover;
      background-repeat: no-repeat;
      font-family: 'Poppins', sans-serif;
      font-weight: 400;
    }

    input {
      font-family: 'Poppins', sans-serif; /* Poppins fontu */
      font-weight: 400; /* Bold */
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
      top: 100px;
      left: 845px;
    }
    .form-header h1 {
        text-align: left;
        margin-bottom: 105px;
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 46px;
        color: white;
        opacity: 1;
    }
    .form-group input {
      width: 430px; /* Form genişliğine uyum sağlar */
      height: 5px; /* Sabit yükseklik */
      padding: 10px; /* Kenar boşluğu */
      /*margin-bottom: 34px; /* Alanlar arasında boşluk */
      /*margin-top: 20px;
      font-size: 14px; /* Yazı boyutu */
      /*border: 1px solid #FFFFFF;*/
      border: none;
      border-radius: 5px; /* Köşeleri yuvarla */
      outline: none;
      box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
      background-color: rgba(255, 255, 255, 0.2);
      color: white;
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
      padding-right: 30px; /* Göz ikonu için boşluk bırakılır */
      height: 25px; /* Input yüksekliği */
      font-size: 14px;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box;
      outline: none;
      box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
    }

    .toggle-password {
      position: absolute; /* Input'un sağında hizalama için */
      top: 50%; /* Dikeyde ortala */
      /*right: 100px; /* Inputun sağında boşluk bırak */
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
      font-size: 16px;
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
        <h1>Yeni Hesap Oluştur</h1>
      </div>
        <form id="registerForm" method="POST" action="">
          <div class="form-group">
            <label class="bold-text" for="fullname">Ad Soyad</label>
            <input type="text" id="fullname" name="fullname" required>
            <hr style="height:2px;width:490px;background-color:white">
          </div>
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
          </div>
          <div class="form-group password-container">
            <div class="password-wrapper">
              <label class="bold-text" for="password">Şifreyi Doğrula</label>
              <input type="password" id="confirm-password" name="confirm-password" required>
              <img id="toggleConfirmPassword" class="toggle-password" src="images/eye.png">
              <hr style="height:2px;width:490px;background-color:white">
            </div>
            <br>
            <?php if (!empty($error_message)) : ?>
            <div id="error-message">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
          </div>

          <button type="submit" class="form-button">Kayıt Ol</button>
        </form>
        <div class="login-link">
          Zaten bir hesabınız var mı? <a href="#" onclick="redirectToLogin()"><span class="bold-text">Giriş Yap</span></a>
        </div>
      </div>
      
<script>
    function redirectToLogin() {
      window.location.href = "login.php"; // Giriş yapma sayfasının URL'sini buraya yazın
    }
    function handleRegister() {
      // Form alanlarını kontrol et
      const fullname = document.getElementById("fullname").value.trim();
      const email = document.getElementById("email").value.trim();
      const password = document.getElementById("password").value.trim();
      const confirmPassword = document.getElementById("confirm-password").value.trim();

      return true;
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