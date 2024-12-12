<?php
session_start();

include 'db_connection.php';

// Slug bilgisi URL'den alınır
$slug = $_GET['slug'] ?? null;

if (!$slug) {
    die(header("Location: 404.html"));
}

$sql = "SELECT user_id, username, created_at, profile_picture, role FROM users WHERE slug = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Profil fotoğrafını belirle
    $profile_picture = !empty($user['profile_picture']) 
        ? 'data:image/png;base64,' . $user['profile_picture'] 
        : 'images/dprofile.jpg';

    $role_display = 'Kullanıcı';
    if ($user['role'] === 'mentor') {
        $role_display = 'Mentör';
    } elseif ($user['role'] === 'admin') {
        $role_display = 'Yönetici';
    }
} else {
    die(header("Location: 404.html"));
}

// Kullanıcı kayıt tarihi
$created_at = $user['created_at'];
$current_date = new DateTime();
$registration_date = new DateTime($created_at);
$interval = $registration_date->diff($current_date);

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

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$blogs_per_page = 5;
$offset = ($page - 1) * $blogs_per_page;

// Profildeki blogları almak için SQL
$posts_sql = "SELECT post_id, title, content, created_at, featured_image 
              FROM posts WHERE user_id = ? AND status = 'published' 
              ORDER BY created_at DESC LIMIT ? OFFSET ?";
$post_stmt = $conn->prepare($posts_sql);
$post_stmt->bind_param("iii", $user['user_id'], $blogs_per_page, $offset);
$post_stmt->execute();
$posts_result = $post_stmt->get_result();

// Toplam blog sayısını hesaplama
$count_query = "SELECT COUNT(*) AS total_blogs FROM posts WHERE user_id = ? AND status = 'published'";
$count_stmt = $conn->prepare($count_query);
$count_stmt->bind_param("i", $user['user_id']);
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_blogs = $count_result->fetch_assoc()['total_blogs'];
$total_pages = ceil($total_blogs / $blogs_per_page);

$total_posts_query = "SELECT COUNT(*) AS total_posts FROM posts WHERE user_id = ? AND status = 'published'";
$total_posts_stmt = $conn->prepare($total_posts_query);
$total_posts_stmt->bind_param("i", $user['user_id']);
$total_posts_stmt->execute();
$total_posts_result = $total_posts_stmt->get_result();
$total_posts = $total_posts_result->fetch_assoc()['total_posts'];
$total_posts_stmt->close();

function calculateReadingTime($content, $words_per_minute = 200) {
  $word_count = str_word_count(strip_tags($content));
  return max(ceil($word_count / $words_per_minute), 1);
}

$post_stmt->close();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>

<html lang="tr-TR"><head>
  <meta charset="utf-8">
  <title>.blendHub | <?php echo htmlspecialchars($user['username']); ?></title>

  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

  <!-- plugins -->
  
  <link rel="stylesheet" href="plugins/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="plugins/themify-icons/themify-icons.css">
  <link rel="stylesheet" href="plugins/slick/slick.css">

  <!-- Main Stylesheet -->
  <link rel="stylesheet" href="css/style.css" media="screen">

  <!--Favicon-->
  <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">
  <link rel="icon" href="images/favicon.png" type="image/x-icon">
  <link rel="stylesheet" href="/css/profilephoto.css">
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
        
        <form class="search-bar">
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

<div class="author">
	<div class="container">
		<div class="row no-gutters justify-content-center">
			<div class="col-lg-3 col-md-4 mb-4 mb-md-0">
				
      <img class="author-image" src="<?php echo $profile_picture; ?>" alt="Profil Fotoğrafı">
				
			</div>
			<div class="col-md-8 col-lg-6 text-center text-md-left">
				<h3 class="mb-2"><?php echo htmlspecialchars($user['username']); ?></h2>
          <strong class="mb-2 d-block"><?php echo htmlspecialchars($role_display); ?></strong>
					<div class="content">
                        <p>Şu tarihten itibaren üye: <?php echo $membership_duration; ?></p>
					</div>
					
					<a class="post-count mb-1"><i class="ti-pencil-alt mr-2"></i><span
							class="text-primary"><?php echo $total_posts; ?></span> Gönderi</a>
					<ul class="list-inline social-icons">
						
						<li class="list-inline-item"><a href="#"><i class="ti-facebook"></i></a></li>
						
						<li class="list-inline-item"><a href="#"><i class="ti-twitter-alt"></i></a></li>
						
						<li class="list-inline-item"><a href="#"><i class="ti-github"></i></a></li>
						
						<li class="list-inline-item"><a href="#"><i class="ti-link"></i></a></li>
						
					</ul>
			</div>
		</div>
	</div>
</div>

<section class="section-sm" id="post">
	<div class="container">
		<div class="row">
      <?php while ($post = $posts_result->fetch_assoc()): 
          $reading_time = calculateReadingTime($post['content']);
          $post_id = $post['post_id'];
      ?>
				<div class="col-lg-8 mx-auto">
					<article class="card mb-4">
						<?php if (!empty($post['featured_image'])): ?>
						<div class="post-slider">
							<img src="data:image/jpeg;base64,<?php echo $post['featured_image']; ?>" class="card-img-top" alt="post-thumb">
						</div>
						<?php endif; ?>
						<div class="card-body">
							<h3 class="mb-3">
                <a class="post-title" href="post-details.php?post_id=<?php echo $post['post_id']; ?>">
                  <?php echo htmlspecialchars($post['title']); ?>
                </a>
              </h3>
							<ul class="card-meta list-inline">
                <li class="list-inline-item">
                <i class="ti-timer"></i><?php echo $reading_time; ?> dakikada okuyabilirsiniz
                </li>	
                <li class="list-inline-item">
                  <i class="ti-calendar"></i>
                  <?php
                    $formatter = new IntlDateFormatter('tr_TR', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
                    echo $formatter->format(new DateTime($post['created_at']));
                  ?>
								</li>
                <li class="list-inline-item">
                <ul class="card-meta-tag list-inline">
                    <?php
                    include 'db_connection.php';

                    $category_query = "SELECT categories.name AS category_name 
                                       FROM postcategories 
                                       JOIN categories ON postcategories.category_id = categories.category_id 
                                       WHERE postcategories.post_id = ?";
                    $category_stmt = $conn->prepare($category_query);
                    $category_stmt->bind_param("i", $post_id);
                    $category_stmt->execute();
                    $categories_result = $category_stmt->get_result();

                    while ($category = $categories_result->fetch_assoc()) :
                    ?>
                        <li class="list-inline-item">
                            <a href="category.php?category=<?php echo urlencode($category['category_name']); ?>">
                                <?php echo htmlspecialchars($category['category_name'], ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </li>
							</ul>
							<p><?php echo htmlspecialchars(substr($post['content'], 0, 150)) . '...'; ?></p>
							<a href="post-details.php?post_id=<?php echo $post['post_id']; ?>" class="btn btn-outline-primary">Devamını Oku</a>
						</div>
					</article>
				</div>
			<?php endwhile; ?>
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
            <li class="list-inline-item"><a href="privacy-policy.html">Privacy Policy</a></li>
            <li class="list-inline-item"><a href="terms-conditions.html">Terms Conditions</a></li>
          </ul>
      </div>
      <div class="col-md-2 text-center mb-4">
          <a href="index.html"><img class="img-fluid" width="100px" src="images/logo.png" alt="Reader | Hugo Personal Blog Template"></a>
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


  <!-- JS Plugins -->
  <script src="plugins/jQuery/jquery.min.js"></script>

  <script src="plugins/bootstrap/bootstrap.min.js"></script>

  <script src="plugins/slick/slick.min.js"></script>

  <script src="plugins/instafeed/instafeed.min.js"></script>


  <!-- Main Script -->
  <script src="js/script.js"></script></body>
</html>