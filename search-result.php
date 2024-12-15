<?php
session_start();
include 'db_connection.php';

$search_query = $_GET['s'] ?? '';

if (empty($search_query)) {
    die("Lütfen bir arama terimi girin.");
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$blogs_per_page = 5; // Her sayfada gösterilecek blog sayısı
$offset = ($page - 1) * $blogs_per_page; // Hangi kayıttan başlanacağını hesapla

// Profil veya arama sonuçlarında kullanmak için SQL sorgusu
$sql = "SELECT post_id, title, content, created_at, featured_image 
        FROM posts 
        WHERE status = 'published'"; // Profil için user_id ekleyin

// Arama sorgusu eklemek için
if (!empty($search_query)) {
    $sql .= " AND (title LIKE ? OR content LIKE ?)";
}
$sql .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";

// Hazırlama ve parametre bağlama
$stmt = $conn->prepare($sql);

if (!empty($search_query)) {
    $search_term = "%" . $search_query . "%";
    $stmt->bind_param("ssii", $search_term, $search_term, $blogs_per_page, $offset);
} else {
    $stmt->bind_param("ii", $blogs_per_page, $offset);
}

$stmt->execute();
$result = $stmt->get_result();

// Toplam blog sayısını hesapla
$count_query = "SELECT COUNT(*) AS total_blogs FROM posts WHERE status = 'published'";
if (!empty($search_query)) {
    $count_query .= " AND (title LIKE ? OR content LIKE ?)";
}
$count_stmt = $conn->prepare($count_query);

if (!empty($search_query)) {
    $count_stmt->bind_param("ss", $search_term, $search_term);
}
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_blogs = $count_result->fetch_assoc()['total_blogs'];
$total_pages = ceil($total_blogs / $blogs_per_page);

if ($result->num_rows === 0) {
    header("Location: search-not-found.php?s=" . urlencode($search_query));
    exit;
}

?>
<!DOCTYPE html>
<html lang="tr-TR">
<head>
  <meta charset="utf-8">
  <title>.blendHub</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="plugins/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="plugins/themify-icons/themify-icons.css">
  <link rel="stylesheet" href="plugins/slick/slick.css">
  <link rel="stylesheet" href="css/style.css" media="screen">
  <link rel="stylesheet" href="css/chat.css" media="screen">
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

<div class="py-3"></div>

<section class="section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10 mb-4">
        <h1 class="h2 mb-4">Arama sonuçları: 
          <mark><?php echo htmlspecialchars($search_query); ?></mark>
        </h1>
      </div>

      <div class="col-lg-10">
        <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <article class="card mb-4">
              <div class="row card-body">
                <?php if (!empty($row['featured_image'])): ?>
                <div class="col-md-4 mb-4 mb-md-0">
                  <div class="post-slider slider-sm">
                    <img src="data:image/jpeg;base64,<?php echo $row['featured_image']; ?>" class="card-img" alt="post-thumb" style="height:200px; object-fit: cover;">
                  </div>
                </div>
                <?php endif; ?>
                <div class="col-md-8">
                  <h3 class="h4 mb-3">
                    <a class="post-title" href="post-details.php?post_id=<?php echo $row['post_id']; ?>">
                      <?php echo htmlspecialchars($row['title']); ?>
                    </a>
                  </h3>
                  <ul class="card-meta list-inline">
                    <li class="list-inline-item">
                      <i class="ti-calendar"></i>
                      <?php
                        $formatter = new IntlDateFormatter('tr_TR', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
                        echo $formatter->format(new DateTime($row['created_at']));
                      ?>
                    </li>
                  </ul>
                  <p><?php echo htmlspecialchars(substr($row['content'], 0, 150)) . '...'; ?></p>
                  <a href="post-details.php?post_id=<?php echo $row['post_id']; ?>" class="btn btn-outline-primary">Devamını Oku</a>
                </div>
              </div>
            </article>
          <?php endwhile; ?>
        <?php else: ?>
          <p>Aradığınız kriterlere uygun sonuç bulunamadı. Lütfen başka bir terim deneyin.</p>
        <?php endif; ?>
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
<script src="js/script.js"></script>
</body>
</html>