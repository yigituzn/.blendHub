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

// Kullanıcı bilgilerini çek
$sql = "SELECT username, email, profile_picture FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($fullname, $email, $profile_picture);
$stmt->fetch();
$stmt->close();

// Kullanıcı bilgilerini güncelle
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Profil Fotoğrafı Güncelleme
    if (isset($_FILES['profile_picture']) && !empty($_FILES['profile_picture']['tmp_name'])) {
        $image = $_FILES['profile_picture']['tmp_name'];
        $image_data = file_get_contents($image); // Dosya içeriğini al
        $base64_image = base64_encode($image_data); // Base64'e çevir

        $sql = "UPDATE users SET profile_picture = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $base64_image, $user_id);

        if ($stmt->execute()) {
            $_SESSION['profile_picture'] = $base64_image; // Oturum değişkenini güncelle
        }
        $stmt->close();
    }

    // Ad Soyad ve E-posta Güncelleme
    $new_fullname = $_POST['fullname'] ?? $fullname;
    $new_email = $_POST['email'] ?? $email;

    if (!empty($new_fullname) && !empty($new_email)) {
        $sql = "UPDATE users SET username = ?, email = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $new_fullname, $new_email, $user_id);

        if ($stmt->execute()) {
            $success_message = "Profil bilgileri başarıyla güncellendi!";
        } else {
            $error_message = "Bilgiler güncellenirken bir hata oluştu.";
        }
        $stmt->close();
    }

    // Şifre Güncelleme
    if (!empty($_POST['password']) && !empty($_POST['confirm_password'])) {
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if ($password === $confirm_password) {
            //$hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "UPDATE users SET password = ? WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $password, $user_id);

            if ($stmt->execute()) {
                $success_message = "Şifre başarıyla güncellendi!";
            } else {
                $error_message = "Şifre güncellenirken bir hata oluştu.";
            }
            $stmt->close();
        } else {
            $error_message = "Şifreler eşleşmiyor.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ayarlar</title>
    <link rel="stylesheet" href="plugins/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/themify-icons/themify-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="navigation fixed-top">
  <div class="container">
    <nav class="navbar navbar-expand-lg navbar-white">
      <a class="navbar-brand order-1" href="index.php">
        <img class="img-fluid" width="100px" src="images/logo.png"
          alt=".blendHub">
      </a>
      <div class="collapse navbar-collapse text-center order-lg-2 order-3" id="navigation">
        <ul class="navbar-nav mx-auto">
          <li class="nav-item dropdown">
            <a class="nav-link" href="index.php">
              anasayfa
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="blogs.php">
              yazılar
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="mentors.php">mentörler</a>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link" href="about.php" role="button" data-toggle="dropdown" aria-haspopup="true"
              aria-expanded="false">hakkımızda <i class="ti-angle-down ml-1"></i>
            </a>
            <div class="dropdown-menu">
              
              <a class="dropdown-item" href="author.html">Author</a>
              
              <a class="dropdown-item" href="author-single.html">Author Single</a>

              <a class="dropdown-item" href="advertise.html">Advertise</a>
              
              <a class="dropdown-item" href="post-details.html">Post Details</a>
              
              <a class="dropdown-item" href="post-elements.html">Post Elements</a>
              
              <a class="dropdown-item" href="tags.html">Tags</a>

              <a class="dropdown-item" href="search-result.html">Search Result</a>

              <a class="dropdown-item" href="search-not-found.html">Search Not Found</a>
              
              <a class="dropdown-item" href="privacy-policy.html">Privacy Policy</a>
              
              <a class="dropdown-item" href="terms-conditions.html">Terms Conditions</a>

              <a class="dropdown-item" href="404.html">404 Page</a>
              
            </div>
          </li>
        </ul>
      </div>

      <div class="order-2 order-lg-3 d-flex align-items-center">
        
        <form class="search-bar" method="GET" action="search-result.php">
          <input id="search-query" name="s" type="search" placeholder="Type &amp; Hit Enter...">
        </form>
        
        <button class="navbar-toggler border-0 order-1" type="button" data-toggle="collapse" data-target="#navigation">
          <i class="ti-menu"></i>
        </button>
          <?php if (isset($_SESSION['user_id'])): ?>
        <div class="dropdown" style="margin-left: 25px;">
            <a href="profile.php?slug=<?php echo $_SESSION['slug']; ?>" class="dropdown-toggle" id="profileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php if (!empty($_SESSION['profile_picture'])): ?>
                    <img src="data:image/jpeg;base64,<?php echo htmlspecialchars($_SESSION['profile_picture']); ?>" alt="Profil Fotoğrafı" width="40" height="40" class="rounded-circle">
                <?php else: ?>
                    <img src="images/dprofile.jpg" alt="Varsayılan Profil Fotoğrafı" width="40" height="40" class="rounded-circle">
                <?php endif; ?>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profileDropdown">
                <a class="dropdown-item" href="profile.php?slug=<?php echo $_SESSION['slug']; ?>">Profilim</a>
                <a class="dropdown-item" href="settings.php">Ayarlar</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="logout.php" onclick="return confirm('Çıkış yapmak istediğinize emin misiniz?');">Çıkış Yap</a>
            </div>
        </div>
    <?php else: ?>
        <a href="login.php" style="margin-left: 25px; color: green">Giriş Yap</a>
    <?php endif; ?>
        </div>
      </div>

    </nav>
  </div>
</header>
<div class="py-5"></div>

<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header text-center">
                        <h3 class="h4">Ayarlar</h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($error_message)): ?>
                            <div class="alert alert-danger">
                                <?php echo htmlspecialchars($error_message); ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($success_message)): ?>
                            <div class="alert alert-success">
                                <?php echo htmlspecialchars($success_message); ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="form-group text-center">
                                <label for="profile_picture">Profil Fotoğrafı</label>
                                <div class="my-3">
                                    <?php if (!empty($profile_picture)): ?>
                                        <img src="data:image/jpeg;base64,<?php echo htmlspecialchars($profile_picture); ?>" alt="Profil Fotoğrafı" class="rounded-circle" width="120">
                                    <?php else: ?>
                                        <img src="images/dprofile.jpg" alt="Varsayılan Profil Fotoğrafı" class="rounded-circle" width="120">
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="profile_picture">Yeni Profil Fotoğrafı Yükle</label>
                                <input type="file" class="form-control-file" name="profile_picture" id="profile_picture">
                            </div>

                            <div class="form-group">
                                <label for="fullname">Ad Soyad</label>
                                <input type="text" class="form-control" name="fullname" id="fullname" value="<?php echo htmlspecialchars($fullname); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="email">E-posta</label>
                                <input type="email" class="form-control" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="password">Yeni Şifre</label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="Yeni şifrenizi girin">
                            </div>

                            <div class="form-group">
                                <label for="confirm_password">Yeni Şifre (Tekrar)</label>
                                <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Şifrenizi tekrar girin">
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-block">Bilgileri Güncelle</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="footer">
  <div class="container">
      <div class="row align-items-center">
      <div class="col-md-5 text-center text-md-left mb-4">
          <ul class="list-inline footer-list mb-0">
            <li class="list-inline-item">© 2024 .blendHub</li>
          </ul>
      </div>
      <div class="col-md-2 text-center mb-4">
          <a href="index.php"><img class="img-fluid" width="100px" src="images/logo.png" alt="blendHub"></a>
      </div>
      <div class="col-md-5 text-md-right text-center mb-4">
          <ul class="list-inline footer-list mb-0">
          
          <li class="list-inline-item"><a href="#"><i class="ti-facebook"></i></a></li>
          
          <li class="list-inline-item"><a href="#"><i class="ti-twitter-alt"></i></a></li>
          
          <li class="list-inline-item"><a href="#"><i class="ti-linkedin"></i></a></li>
          
          <li class="list-inline-item"><a href="#"><i class="ti-github"></i></a></li>
          
          <li class="list-inline-item"><a href="#"><i class="ti-youtube"></i></a></li>
          
          </ul>
      </div>
      <div class="col-12">
          <div class="border-bottom border-default"></div>
      </div>
      </div>
  </div>
  </footer>

<script src="plugins/jQuery/jquery.min.js"></script>
<script src="plugins/bootstrap/bootstrap.min.js"></script>
</body>
</html>
