<?php
session_start();
?>
<!DOCTYPE html>
<html lang="tr-TR"><head>
  <meta charset="utf-8">
  <title>.blendHub | Hakkımızda</title>

  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  
  <link rel="stylesheet" href="plugins/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="plugins/themify-icons/themify-icons.css">
  <link rel="stylesheet" href="plugins/slick/slick.css">

  <link rel="stylesheet" href="css/style.css" media="screen">
  <link rel="stylesheet" href="css/chat.css" media="screen">

  <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">
  <link rel="icon" href="images/favicon.png" type="image/x-icon">
<style>
.team {
  display: flex;
  justify-content: center;
  gap: 30px;
  flex-wrap: wrap;
}
.team-card {
  background: #fff;
  border: 1px solid #ddd;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  text-align: center;
}
.team-card h3 {
  font-size: 22px;
  margin-bottom: 10px;
}
.team-card p {
  font-size: 16px;
  margin-bottom: 10px;
}
.qr-code {
  margin: 15px 0;
  width: 150px;
  height: 150px;
}
</style>
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

          <li class="nav-item">
            <a class="nav-link" href="about.php">hakkımızda</a>
          </li>
          <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
          <li class="nav-item">
            <a class="nav-link" href="admin-panel.php">Panel</a>
          </li>
          <?php endif; ?>
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
<?php include 'chat-widget.html'; ?>
<div class="header text-center">
  <div class="container">
    <div class="row">
      <div class="col-lg-9 mx-auto" style="margin-top: 10px">
        <h1 class="mb-4">Hakkımızda</h1>
        <ul class="list-inline">
          <li class="list-inline-item"><a class="text-default" href="index.php">Anasayfa
              &nbsp; &nbsp; /</a></li>
          <li class="list-inline-item text-primary">Hakkımızda</li>
        </ul>
      </div>
    </div>
  </div>

<section class="section-sm">
  <div class="container">
    <div class="row align-items-center justify-content-center">
      <div class="col-lg-5 col-md-6 mb-4 mb-md-0">
        <div class="image-wrapper">
          <img class="img-fluid w-100" src="images/about-us-1.jpg">
        </div>
      </div>
      <div class="col-lg-5 col-md-6">
        <div class="content pl-lg-3 pl-0">
          <h2>Misyonumuz</h2>
          <p>3D Modelleme, yaratıcı düşünce ve teknolojinin kesişim noktasıdır. Bizler, 3D sanatının sınırlarını keşfetmek, öğrenmek ve öğretmek için buradayız. Amacımız, 3D modelleme konusunda bilgi paylaşımı yapmak, bu alandaki en son trendleri ve teknikleri paylaşarak kullanıcılarımızın projelerine ilham kaynağı olmaktır. Blogumuzda, başlangıç seviyesinden ileri düzeye kadar 3D modelleme ile ilgili her şeyi bulabilirsiniz. Çünkü bizim için, yaratıcılık sınırsızdır!</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class=>
    <div class="container">
        <div class="row team">
        <!-- Kart 1 -->
        <div class="col-lg-6 col-md-6">
            <div class="team-card text-center">
            <h3>Esmanur Durak</h3>
            <p>Kurucu & 3D Modelleme Uzmanı</p>
            <img src="images/esma_qr.png" alt="LinkedIn QR Code" class="qr-code">
            <p class="bio">3D sanatına olan tutkusu ve sektördeki deneyimiyle blogun hayata geçmesini sağladı.</p>
            </div>
        </div>
        <!-- Kart 2 -->
        <div class="col-lg-6 col-md-6">
            <div class="team-card text-center">
            <h3>Yiğit Uzun</h3>
            <p>Kurucu & Yazılım Geliştirici</p>
            <img src="images/yiğit_qr.png" alt="LinkedIn QR Code" class="qr-code">
            <p class="bio">Teknik altyapı, web geliştirme ve 3D projelerin teknoloji entegrasyonundan sorumlu.</p>
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
      </div>
      <div class="col-12">
          <div class="border-bottom border-default"></div>
      </div>
      </div>
  </div>
  </footer>
  <script src="js/chat.js"></script>

  <script src="plugins/jQuery/jquery.min.js"></script>

  <script src="plugins/bootstrap/bootstrap.min.js"></script>

  <script src="plugins/slick/slick.min.js"></script>

  <script src="plugins/instafeed/instafeed.min.js"></script>

  <script src="js/script.js"></script></body>
</html>