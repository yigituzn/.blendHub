<?php
session_start();
include 'db_connection.php';

$category_name = $_GET['category'] ?? null;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Sayfa numarası (varsayılan: 1)
$blogs_per_page = 5; // Her sayfada gösterilecek blog sayısı
$offset = ($page - 1) * $blogs_per_page; // OFFSET hesaplanır

if (!$category_name) {
    die(header("Location: 404.php"));
}

// Kategoriye ait blogları çekmek için SQL sorgusu
$sql = "SELECT posts.post_id, posts.title, posts.content, posts.created_at, posts.featured_image 
        FROM posts 
        INNER JOIN postcategories ON posts.post_id = postcategories.post_id
        INNER JOIN categories ON postcategories.category_id = categories.category_id
        WHERE categories.name = ? AND posts.status = 'published'
        ORDER BY posts.created_at DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $category_name, $blogs_per_page, $offset);
$stmt->execute();
$result = $stmt->get_result();

// Toplam blog sayısını hesapla
$count_query = "SELECT COUNT(*) AS total_blogs 
                FROM posts 
                INNER JOIN postcategories ON posts.post_id = postcategories.post_id
                INNER JOIN categories ON postcategories.category_id = categories.category_id
                WHERE categories.name = ? AND posts.status = 'published'";
$count_stmt = $conn->prepare($count_query);
$count_stmt->bind_param("s", $category_name);
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_blogs = $count_result->fetch_assoc()['total_blogs'];
$total_pages = ceil($total_blogs / $blogs_per_page);

$recent_posts_sql = "SELECT post_id, title, content, created_at FROM posts WHERE status = 'published' ORDER BY created_at DESC LIMIT 3";
$recent_posts_result = $conn->query($recent_posts_sql);
?>
<!DOCTYPE html>
<html lang="tr-TR"><head>
  <meta charset="utf-8">
  <title>.blendHub | <?php echo htmlspecialchars($category_name); ?></title>

  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  
  <link rel="stylesheet" href="plugins/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="plugins/themify-icons/themify-icons.css">
  <link rel="stylesheet" href="plugins/slick/slick.css">

  <link rel="stylesheet" href="css/style.css" media="screen">
  <link rel="stylesheet" href="css/chat.css" media="screen">

  <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">
  <link rel="icon" href="images/favicon.png" type="image/x-icon">
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
<section class="section">
  <div class="py-4"></div>
  <div class="container">
    <div class="row">
    <div class="col-lg-8 mb-5 mb-lg-0">
    <h1 class="h2 mb-4"><mark><?php echo htmlspecialchars($category_name); ?></mark> Kategorisi</h1>
    <?php while ($row = $result->fetch_assoc()): ?>
        <article class="card mb-4">
            <?php if (!empty($row['featured_image'])): ?>
                <div class="post-slider">
                    <img src="data:image/jpeg;base64,<?php echo $row['featured_image']; ?>" class="card-img-top" alt="post-thumb">
                </div>
            <?php endif; ?>
            <div class="card-body">
                <h3 class="mb-3">
                    <a class="post-title" href="post-details.php?post_id=<?php echo $row['post_id']; ?>">
                        <?php echo htmlspecialchars($row['title']); ?>
                    </a>
                </h3>
                <ul class="card-meta list-inline">
                    <li class="list-inline-item">
                        <i class="ti-calendar"></i>
                        <?php 
                        $tarih = $row['created_at'];
                        $date = new DateTime($tarih);

                        $formatter = new IntlDateFormatter('tr_TR', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
                        echo $formatter->format($date);?>
                    </li>
                </ul>
                <p><?php echo htmlspecialchars(substr($row['content'], 0, 150)) . '...'; ?></p>
                <a href="post-details.php?post_id=<?php echo $row['post_id']; ?>" class="btn btn-outline-primary">Devamını Oku</a>
            </div>
        </article>
    <?php endwhile; ?>
</div>
<aside class="col-lg-4 sidebar-inner">
  <!-- categories -->
  <div class="widget widget-categories">
    <h4 class="widget-title"><span>Kategoriler</span></h4>
    <ul class="list-unstyled widget-list">
      <?php
        include 'db_connection.php';
        try {
          $query = "
              SELECT c.name AS category_name, COUNT(pc.post_id) AS blog_count
              FROM categories c
              LEFT JOIN postcategories pc ON c.category_id = pc.category_id
              GROUP BY c.category_id, c.name
              ORDER BY c.name ASC;
          ";
          $stmt = $conn->prepare($query);
          $stmt->execute();
          $categories = $stmt->get_result();
          if ($categories->num_rows > 0) {
              while ($row = $categories->fetch_assoc()) {
                  echo '<li>';
                  echo '<a href="category.php?category=' . htmlspecialchars($row['category_name']) . '" class="d-flex">';
                  echo htmlspecialchars($row['category_name']);
                  echo ' <small class="ml-auto">(' . $row['blog_count'] . ')</small>';
                  echo '</a>';
                  echo '</li>';
              }
          } else {
              echo '<li>Kategori bulunamadı.</li>';
          }
      } catch (Exception $e) {
          echo '<li>Hata: ' . $e->getMessage() . '</li>';
      }
      $conn->close();
      ?>
    </ul>
  </div>
  
  <div class="widget">
    <h4 class="widget-title">Son Bloglar</h4>

    <?php while ($recent_post = $recent_posts_result->fetch_assoc()): ?>
    <article class="widget-card">
      <div class="d-flex">
      <?php 
        // content içerisinden ilk resmi çek
        preg_match('/<img[^>]+src="([^">]+)"/', $recent_post['content'], $matches);
        $image_src = !empty($matches[1]) ? $matches[1] : null;
        ?>
        <?php if($image_src): ?>
        <img class="card-img-sm" src="<?php echo htmlspecialchars($image_src, ENT_QUOTES, 'UTF-8'); ?>" alt="Post Image">
        <?php endif; ?>
        <div class="ml-3">
          <h5><a class="post-title" href="post-details.php?post_id=<?php echo $recent_post['post_id']; ?>"><?php echo htmlspecialchars($recent_post['title']); ?></a></h5>
          <ul class="card-meta list-inline mb-0">
            <li class="list-inline-item mb-0">
                <i class="ti-calendar"></i>
                  <?php 
                  $tarih = $recent_post['created_at'];
                  $date = new DateTime($tarih);

                  $formatter = new IntlDateFormatter('tr_TR', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
                  echo $formatter->format($date);?>
            </li>
          </ul>
        </div>
      </div>
    </article>
    <?php endwhile; ?>
</aside>
    </div>
  </div>
</section>

<ul class="pagination justify-content-center">
    <?php if ($page > 1): ?>
        <li class="page-item">
            <a href="?category=<?php echo urlencode($category_name); ?>&page=<?php echo $page - 1; ?>" class="page-link">&laquo;</a>
        </li>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
            <a href="?category=<?php echo urlencode($category_name); ?>&page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a>
        </li>
    <?php endfor; ?>

    <?php if ($page < $total_pages): ?>
        <li class="page-item">
            <a href="?category=<?php echo urlencode($category_name); ?>&page=<?php echo $page + 1; ?>" class="page-link">&raquo;</a>
        </li>
    <?php endif; ?>
</ul>

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