<?php
session_start();
?>
<!DOCTYPE html>
<html lang="tr-TR"><head>
  <meta charset="utf-8">
  <title>.blendHub | Bloglar</title>

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

<div class="py-3"></div>

<?php
include 'db_connection.php';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$blogs_per_page = 5;
$offset = ($page - 1) * $blogs_per_page;

// Blog sorgusu
$query = "SELECT posts.*, users.username, users.slug, users.profile_picture 
          FROM posts 
          INNER JOIN users ON posts.user_id = users.user_id 
          WHERE posts.status = 'published' 
          ORDER BY posts.created_at DESC 
          LIMIT $blogs_per_page OFFSET $offset";
$result = $conn->query($query);

// Toplam blog sayısını hesapla
$count_query = "SELECT COUNT(*) AS total_blogs FROM posts WHERE status = 'published'";
$count_result = $conn->query($count_query);
$total_blogs = $count_result->fetch_assoc()['total_blogs'];
$total_pages = ceil($total_blogs / $blogs_per_page);

setlocale(LC_TIME, 'tr_TR.UTF-8', 'turkish');

// Fonksiyon: Okuma süresi hesaplama
function calculateReadingTime($content, $words_per_minute = 200) {
    $cleaned_content = strip_tags($content);
    $cleaned_content = preg_replace('/[^\w\s]/u', '', $cleaned_content); // Noktalama işaretlerini kaldır
    $cleaned_content = trim(preg_replace('/\s+/', ' ', $cleaned_content)); // Çoklu boşlukları temizle
    $word_count = str_word_count($cleaned_content);

    if ($word_count === 0) {
        return 1; // Minimum okuma süresi
    }

    return ceil($word_count / $words_per_minute);
}
?>

<section class="section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <?php while ($row = $result->fetch_assoc()) : ?>
          <article class="card mb-4">
            <div class="row card-body">
            <?php
              // Resim kontrolü
              preg_match_all('/<img[^>]+src="([^">]+)"/', $row['content'], $matches);
              if (!empty($matches[1])) : ?>
                <div class="col-md-4 mb-4 mb-md-0">
                  <div class="post-slider slider-sm">
                    <?php foreach ($matches[1] as $img_src) : ?>
                      <div>
                        <img src="<?php echo htmlspecialchars($img_src, ENT_QUOTES, 'UTF-8'); ?>" class="card-img" style="height:200px; object-fit: cover;" alt="<?php echo htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8'); ?>">
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>
              <?php endif; ?>
                <div class="col-md-8">
                <div class="col-12">
                <h3 class="h4 mb-3"><a class="post-title" href="post-details.php?post_id=<?php echo $row['post_id']; ?>"><?php echo htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8'); ?></a></h3>
                <ul class="card-meta list-inline">
                  <li class="list-inline-item">
                    <a href="profile.php?slug=<?php echo htmlspecialchars($row['slug'], ENT_QUOTES, 'UTF-8'); ?>" class="card-meta-author">
                      <img src="<?php echo !empty($row['profile_picture']) ? 'data:image/png;base64,' . $row['profile_picture'] : 'images/dprofile.jpg'; ?>" alt="<?php echo htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8'); ?>">
                      <span><?php echo htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </a>
                  </li>
                  <li class="list-inline-item">
                    <i class="ti-timer"></i> <?php echo calculateReadingTime($row['content']); ?> dk. okunabilir
                  </li>
                  <li class="list-inline-item">
                    <i class="ti-calendar"></i> <?php 
                      $tarih = $row['created_at'];
                      $date = new DateTime($tarih);
                      $formatter = new IntlDateFormatter('tr_TR', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
                      echo $formatter->format($date); 
                    ?>
                  </li>
                  <li class="list-inline-item">
                    <ul class="card-meta-tag list-inline">
                      <?php
                        $category_query = "SELECT categories.name AS category_name 
                                           FROM postcategories 
                                           JOIN categories ON postcategories.category_id = categories.category_id 
                                           WHERE postcategories.post_id = ?";
                        $category_stmt = $conn->prepare($category_query);
                        $category_stmt->bind_param("i", $row['post_id']);
                        $category_stmt->execute();
                        $categories_result = $category_stmt->get_result();

                        while ($category = $categories_result->fetch_assoc()) :
                      ?>
                        <li class="list-inline-item">
                          <a href="categories.php?category=<?php echo urlencode($category['category_name']); ?>">
                            <?php echo htmlspecialchars($category['category_name'], ENT_QUOTES, 'UTF-8'); ?>
                          </a>
                        </li>
                      <?php endwhile; ?>
                    </ul>
                  </li>
                </ul>
                <p><?php echo substr(strip_tags($row['content']), 0, 150) . '...'; ?></p>
                <a href="post-details.php?post_id=<?php echo $row['post_id']; ?>" class="btn btn-outline-primary">Read More</a>
              </div>
            </div>
          </article>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
</section>

<ul class="pagination justify-content-center">
    <?php if ($page > 1): ?>
        <li class="page-item">
            <a href="?page=<?php echo $page - 1; ?>" class="page-link">&laquo;</a>
        </li>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
            <a href="?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a>
        </li>
    <?php endfor; ?>

    <?php if ($page < $total_pages): ?>
        <li class="page-item">
            <a href="?page=<?php echo $page + 1; ?>" class="page-link">&raquo;</a>
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

  <script src="js/script.js"></script>

  <script src="js/chat.js"></script></body>
</html>