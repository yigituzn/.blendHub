<?php
session_start();

if (!isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr-TR"><head>
  <meta charset="utf-8">
  <title>.blendHub | Dashboard</title>

  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  
  <link rel="stylesheet" href="plugins/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="plugins/themify-icons/themify-icons.css">
  <link rel="stylesheet" href="plugins/slick/slick.css">

  <link rel="stylesheet" href="css/style.css" media="screen">

  <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">
  <link rel="icon" href="images/favicon.png" type="image/x-icon">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
<div class="header text-center">
  <div class="container">
    <div class="row">
      <div class="col-lg-9 mx-auto" style="margin-top: 10px">
        <h1 class="mb-4">Dashboard</h1>
        <ul class="list-inline">
          <li class="list-inline-item"><a class="text-default" href="index.php">Anasayfa
              &nbsp; &nbsp; /</a></li>
          <li class="list-inline-item text-primary">Dashboard</li>
        </ul>
      </div>
    </div>
  </div>
  
<div class="chart-row">
    <div class="chart-container">
        <h3>Kullanıcı Artışı</h3>
        <canvas id="userTrendChart"></canvas>
    </div>
    <div class="chart-container">
        <h3>Blog Artışı</h3>
        <canvas id="blogTrendChart"></canvas>
    </div>
</div>
<div class="chart-row">
    <div class="chart-container">
        <h3>En Popüler Kategoriler</h3>
        <canvas id="popularCategoriesChart"></canvas>
    </div>
    <div class="chart-container">
        <h3>Kategorilere Göre Blog Sayısı</h3>
        <canvas id="blogCategoriesPieChart"></canvas>
    </div>
</div>

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

  <script>
        fetch('admin-data.php')
            .then(response => response.json())
            .then(data => {
                const userTrendChart = new Chart(document.getElementById('userTrendChart'), {
                    type: 'line',
                    data: {
                        labels: data.user_trend.map(item => item.date),
                        datasets: [{
                            label: 'Kullanıcı Artışı', // Eğer tamamen kaldırmak isterseniz, bu satırı da silebilirsiniz.
                            data: data.user_trend.map(item => item.count),
                            borderColor: 'blue',
                            fill: false,
                        }],
                    },
                    options: {
                        plugins: {
                            title: { 
                                display: false // Başlığı kaldırır
                            },
                            legend: {
                                display: false // Grafik içindeki legend (etiketi) kaldırır
                            }
                        },
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });

                // Blog artışı grafiği
                const blogTrendChart = new Chart(document.getElementById('blogTrendChart'), {
                    type: 'line',
                    data: {
                        labels: data.blog_trend.map(item => item.date),
                        datasets: [{
                            label: 'Blog Artışı',
                            data: data.blog_trend.map(item => item.count),
                            borderColor: 'green',
                            fill: false,
                        }],
                    },
                    options: {
                        plugins: {
                            title: { 
                                display: false // Başlığı kaldırır
                            },
                            legend: {
                                display: false // Grafik içindeki legend (etiketi) kaldırır
                            }
                        },
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });


                // En popüler kategoriler grafiği
                const popularCategoriesChart = new Chart(document.getElementById('popularCategoriesChart'), {
                    type: 'bar',
                    data: {
                        labels: data.popular_categories.map(item => item.name),
                        datasets: [{
                            label: 'En Popüler Kategoriler',
                            data: data.popular_categories.map(item => item.count),
                            backgroundColor: 'orange',
                        }],
                    },
                    options: {
                        plugins: {
                            title: { 
                                display: false // Başlığı kaldırır
                            },
                            legend: {
                                display: false // Grafik içindeki legend (etiketi) kaldırır
                            }
                        },
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });


                // Kategorilere göre blog sayısı pasta grafiği
                const blogCategoriesPieChart = new Chart(document.getElementById('blogCategoriesPieChart'), {
                    type: 'pie',
                    data: {
                        labels: data.blog_categories.map(item => item.name),
                        datasets: [{
                            label: 'Kategorilere Göre Blog Sayısı',
                            data: data.blog_categories.map(item => item.count),
                            backgroundColor: [
                                'red', 'blue', 'green', 'yellow', 'purple', 'orange'
                            ],
                        }],
                    },
                    options: {
                        plugins: {
                            title: { 
                                display: false // Başlığı kaldırır
                            },
                            legend: {
                                display: false // Grafik içindeki legend (etiketi) kaldırır
                            }
                        },
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });

            })
            .catch(error => console.error('Hata:', error));
  </script>

  <script src="plugins/jQuery/jquery.min.js"></script>

  <script src="plugins/bootstrap/bootstrap.min.js"></script>

  <script src="plugins/slick/slick.min.js"></script>

  <script src="plugins/instafeed/instafeed.min.js"></script>

  <script src="js/script.js"></script></body>
</html>