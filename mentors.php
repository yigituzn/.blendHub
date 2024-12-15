<?php
session_start();
?>
<!DOCTYPE html>
<html lang="tr-TR"><head>
  <meta charset="utf-8">
  <title>.blendHub | Mentörler</title>

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
<div class="text-center">
  <div class="container">
    <div class="row">
      <div class="col-lg-9 mx-auto" style="margin-top: 150px">
        <h1 class="mb-4">Mentörler</h1>
        <ul class="list-inline">
          <li class="list-inline-item"><a class="text-default" href="index.php">Anasayfa
              &nbsp; &nbsp; /</a></li>
          <li class="list-inline-item text-primary">Mentörler</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<?php
include 'db_connection.php';

$sql = "
SELECT 
    u.user_id, 
    u.username, 
    u.profile_picture, 
    u.slug,
    u.created_at,
    ma.expertise, 
    COUNT(p.post_id) AS post_count 
FROM 
    users u
LEFT JOIN 
    mentorshipapplications ma ON u.email = ma.email
LEFT JOIN 
    posts p ON u.user_id = p.user_id
WHERE 
    u.role = 'mentor'
GROUP BY 
    u.user_id
";

$result = $conn->query($sql);

// HTML çıktı
if ($result->num_rows > 0) {
    echo '<section class="section-sm">';
    echo '<div class="container">';
    echo '<div class="row no-gutters">';

    while ($row = $result->fetch_assoc()) {
        $profile_picture = !empty($row['profile_picture']) ? 'data:image/png;base64,' . $row['profile_picture'] : 'images/dprofile.jpg';
        $username = htmlspecialchars($row['username']);
        $expertise = htmlspecialchars($row['expertise'] ?? '');
        $post_count = intval($row['post_count']);

        $created_at = new DateTime($row['created_at']);
        $current_date = new DateTime();
        $interval = $created_at->diff($current_date);
        if ($interval->y > 0) {
            $membership_duration = $interval->y . " yıl";
            if ($interval->m > 0) {
                $membership_duration .= " " . $interval->m . " ay";
            }
        } elseif ($interval->m > 0) {
            $membership_duration = $interval->m . " ay";
        } else {
            $membership_duration = $interval->d . " gün";
        }

        echo '<div class="col-lg-4 col-sm-6 author-block">';
        echo '<div class="author-card text-center">';
        echo '<img class="author-image" src="' . $profile_picture . '">';
        echo '<h3 class="mb-2"><a href="profile.php?slug=' . urlencode($row['slug']) . '" class="post-title">' . $username . '</a></h3>';
        echo '<p class="mb-3">' . $expertise . '</p>';
        echo '<p>Şu tarihten itibaren üye: ' . $membership_duration . '</p>';
        echo '<i class="ti-pencil-alt mr-2"></i><span class="text-primary">' . $post_count . '</span> Gönderi';
        echo '</div>';
        echo '</div>';
    }

    echo '</div>';
    echo '</div>';
    echo '</section>';
} else {
    echo '<p style="text-align: center; margin-top: 75px">Hiç mentör bulunamadı.</p>';
}

$conn->close();
?>

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