<?php
session_start();
?>
<!DOCTYPE html>
<html lang="tr-TR"><head>
  <meta charset="utf-8">
  <title>.blendHub</title>

  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

  <link rel="stylesheet" href="plugins/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="plugins/themify-icons/themify-icons.css">
  <link rel="stylesheet" href="plugins/slick/slick.css">

  <link rel="stylesheet" href="css/style.css" media="screen">
  <link rel="stylesheet" href="css/chat.css" media="screen">

  <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">
  <link rel="icon" href="images/favicon.png" type="image/x-icon">
  <link rel="stylesheet" href="css/profilephoto.css">
  <style>

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
            <a class="nav-link" href="index.php" role="button" data-toggle="dropdown" aria-haspopup="true"
              aria-expanded="false">
              anasayfa <i class="ti-angle-down ml-1"></i>
            </a>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="index-full.html">Homepage Full Width</a>
              
              <a class="dropdown-item" href="index-full-left.html">Homepage Full With Left Sidebar</a>
              
              <a class="dropdown-item" href="index-full-right.html">Homepage Full With Right Sidebar</a>
              
              <a class="dropdown-item" href="index-list.html">Homepage List Style</a>
              
              <a class="dropdown-item" href="index-list-left.html">Homepage List With Left Sidebar</a>
              
              <a class="dropdown-item" href="index-list-right.html">Homepage List With Right Sidebar</a>
              
              <a class="dropdown-item" href="index-grid.html">Homepage Grid Style</a>
              
              <a class="dropdown-item" href="index-grid-left.html">Homepage Grid With Left Sidebar</a>
              
              <a class="dropdown-item" href="index-grid-right.html">Homepage Grid With Right Sidebar</a>
              
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
              aria-expanded="false">
              yazılar <i class="ti-angle-down ml-1"></i>
            </a>
            <div class="dropdown-menu">
              
              <a class="dropdown-item" href="about-me.html">About Me</a>
              
              <a class="dropdown-item" href="about-us.html">About Us</a>
              
            </div>
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
<?php include 'chat-widget.html'; ?>
<div class="text-center">
  <div class="container">
    <div class="row">
      <div class="col-lg-9 mx-auto" style="margin-top: 150px">
        <h1 class="mb-5">Bugün Ne <br>Modelleyeceksiniz?</h1>
        <ul class="list-inline widget-list-inline">
          <?php
          include 'db_connection.php';
          $sql = "SELECT name FROM categories";
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                  echo '<li class="list-inline-item"><a href="tags.html">' . htmlspecialchars($row['name']) . '</a></li>';
              }
          }
          $conn->close();
          ?>
        </ul>
      </div>
    </div>
  </div>
</div>
<!--
<section class="section pb-0">
  <div class="container">
    <div class="row">
      <div class="col-lg-4 mb-5">
        <h2 class="h5 section-title">Editors Pick</h2>
        <article class="card">
          <div class="post-slider slider-sm">
            <img src="images/post/post-1.jpg" class="card-img-top" alt="post-thumb">
          </div>
          
          <div class="card-body">
            <h3 class="h4 mb-3"><a class="post-title" href="post-details.html">Use apples to give your bakes caramel and a moist texture</a></h3>
            <ul class="card-meta list-inline">
              <li class="list-inline-item">
                <a href="author-single.html" class="card-meta-author">
                  <img src="images/john-doe.jpg">
                  <span>Charls Xaviar</span>
                </a>
              </li>
              <li class="list-inline-item">
                <i class="ti-timer"></i>2 Min To Read
              </li>
              <li class="list-inline-item">
                <i class="ti-calendar"></i>14 jan, 2020
              </li>
              <li class="list-inline-item">
                <ul class="card-meta-tag list-inline">
                  <li class="list-inline-item"><a href="tags.html">Color</a></li>
                  <li class="list-inline-item"><a href="tags.html">Recipe</a></li>
                  <li class="list-inline-item"><a href="tags.html">Fish</a></li>
                </ul>
              </li>
            </ul>
            <p>It’s no secret that the digital industry is booming. From exciting startups to …</p>
            <a href="post-details.html" class="btn btn-outline-primary">Read More</a>
          </div>
        </article>
      </div>
      <div class="col-lg-4 mb-5">
        <h2 class="h5 section-title">Trending Post</h2>
        
        <article class="card mb-4">
          <div class="card-body d-flex">
            <img class="card-img-sm" src="images/post/post-3.jpg">
            <div class="ml-3">
              <h4><a href="post-details.html" class="post-title">Advice From a Twenty Something</a></h4>
              <ul class="card-meta list-inline mb-0">
                <li class="list-inline-item mb-0">
                  <i class="ti-calendar"></i>14 jan, 2020
                </li>
                <li class="list-inline-item mb-0">
                  <i class="ti-timer"></i>2 Min To Read
                </li>
              </ul>
            </div>
          </div>
        </article>
        
        <article class="card mb-4">
          <div class="card-body d-flex">
            <img class="card-img-sm" src="images/post/post-2.jpg">
            <div class="ml-3">
              <h4><a href="post-details.html" class="post-title">The Design Files - Homes Minimalist</a></h4>
              <ul class="card-meta list-inline mb-0">
                <li class="list-inline-item mb-0">
                  <i class="ti-calendar"></i>14 jan, 2020
                </li>
                <li class="list-inline-item mb-0">
                  <i class="ti-timer"></i>2 Min To Read
                </li>
              </ul>
            </div>
          </div>
        </article>
        
        <article class="card mb-4">
          <div class="card-body d-flex">
            <img class="card-img-sm" src="images/post/post-4.jpg">
            <div class="ml-3">
              <h4><a href="post-details.html" class="post-title">The Skinny Confidential</a></h4>
              <ul class="card-meta list-inline mb-0">
                <li class="list-inline-item mb-0">
                  <i class="ti-calendar"></i>14 jan, 2020
                </li>
                <li class="list-inline-item mb-0">
                  <i class="ti-timer"></i>2 Min To Read
                </li>
              </ul>
            </div>
          </div>
        </article>
      </div>
      
      <div class="col-lg-4 mb-5">
        <h2 class="h5 section-title">Popular Post</h2>
        
        <article class="card">
          <div class="post-slider slider-sm">
            <img src="images/post/post-5.jpg" class="card-img-top" alt="post-thumb">
          </div>
          <div class="card-body">
            <h3 class="h4 mb-3"><a class="post-title" href="post-details.html">How To Make Cupcakes and Cashmere Recipe At Home</a></h3>
            <ul class="card-meta list-inline">
              <li class="list-inline-item">
                <a href="author-single.html" class="card-meta-author">
                  <img src="images/kate-stone.jpg" alt="Kate Stone">
                  <span>Kate Stone</span>
                </a>
              </li>
              <li class="list-inline-item">
                <i class="ti-timer"></i>2 Min To Read
              </li>
              <li class="list-inline-item">
                <i class="ti-calendar"></i>14 jan, 2020
              </li>
              <li class="list-inline-item">
                <ul class="card-meta-tag list-inline">
                  <li class="list-inline-item"><a href="tags.html">City</a></li>
                  <li class="list-inline-item"><a href="tags.html">Food</a></li>
                  <li class="list-inline-item"><a href="tags.html">Taste</a></li>
                </ul>
              </li>
            </ul>
            <p>It’s no secret that the digital industry is booming. From exciting startups to …</p>
            <a href="post-details.html" class="btn btn-outline-primary">Read More</a>
          </div>
        </article>
      </div>
      <div class="col-12">
        <div class="border-bottom border-default"></div>
      </div>
    </div>
  </div>
</section>
    -->
<section class="section-sm">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8  mb-5 mb-lg-0">
  <h2 class="h5 section-title">Son Bloglar</h2>
  <?php
include 'db_connection.php';

// Veritabanından son 5 blog gönderisini çekin
$query = "SELECT posts.*, users.username, users.slug, users.profile_picture FROM posts 
          INNER JOIN users ON posts.user_id = users.user_id 
          WHERE posts.status = 'published' 
          ORDER BY posts.created_at DESC 
          LIMIT 5";

$result = $conn->query($query);

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

// Blog içeriklerini çek ve listele
while ($row = $result->fetch_assoc()) :
    $content = $row['content'];
    $reading_time = calculateReadingTime($content);
    $post_id = $row['post_id'];

?>
<article class="card mb-4">
    <div class="post-slider">
    <?php if (!empty($row['featured_image'])) : ?>
      <img src="data:image/jpeg;base64,<?php echo $row['featured_image']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8'); ?>">
    <?php endif; ?>

    </div>
    <div class="card-body">
        <h3 class="mb-3">
            <a class="post-title" href="post-details.php?post_id=<?php echo $post_id; ?>">
                <?php echo htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8'); ?>
            </a>
        </h3>
        <ul class="card-meta list-inline">
            <li class="list-inline-item">
                <a href="profile.php?slug=<?php echo $row['slug']; ?>" class="card-meta-author">
                    <img src="<?php echo !empty($row['profile_picture']) ? 'data:image/png;base64,' . $row['profile_picture'] : 'images/dprofile.jpg'; ?>" alt="<?php echo htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8'); ?>">
                    <span><?php echo htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8'); ?></span>
                </a>
            </li>
            <li class="list-inline-item">
                <i class="ti-timer"></i> <?php echo $reading_time; ?> dk. okunabilir
            </li>
            <li class="list-inline-item">
                <i class="ti-calendar"></i> <?php 
                  $tarih = $row['created_at'];
                  $date = new DateTime($tarih);

                  $formatter = new IntlDateFormatter('tr_TR', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
                  echo $formatter->format($date);?>
            </li>
            <li class="list-inline-item">
                <ul class="card-meta-tag list-inline">
                    <?php
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
                            <a href="categories.php?category=<?php echo urlencode($category['category_name']); ?>">
                                <?php echo htmlspecialchars($category['category_name'], ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </li>
        </ul>
        <p>
            <?php echo htmlspecialchars(substr($content, 0, 150), ENT_QUOTES, 'UTF-8') . '...'; ?>
        </p>
        <a href="post-details.php?post_id=<?php echo $post_id; ?>" class="btn btn-outline-primary">Read More</a>
    </div>
</article>
<?php endwhile; ?>
</div>

<aside class="col-lg-4 sidebar-home">

<div class="widget">
  <h4 class="widget-title"><span>BLOG PAYLAŞ!</span></h4> 
    <button type="submit" id="addPostBtn" class="btn btn-primary btn-block" name="post-share" data-toggle="modal" data-target="#addPostModal">Blog Paylaş</button>
</div>
<div id="addPostModal" class="modal" tabindex="-1" role="dialog" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); justify-content: center; align-items: center;">
  <div class="modal-dialog modal-dialog-centered" role="document" style="border-radius: 8px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Yeni Blog Gönderisi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Kapat">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="blog-form" method="POST" action="add_post.php" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="form-group">
            <label for="postTitle">Başlık</label>
            <input type="text" class="form-control" id="postTitle" name="title" required>
          </div>
          <div class="form-group">
            <label for="postContent">İçerik</label>
            <textarea class="form-control" id="postContent" name="content" rows="5" required></textarea>
          </div>
          <div class="form-group">
            <label for="featuredImage">Öne Çıkan Resim</label>
            <input type="file" class="form-control-file" id="featuredImage" name="featured_image" accept=".jpg,.jpeg,.png">
            <small class="form-text text-muted">Sadece JPG ve PNG formatında, maksimum 5MB.</small>
          </div>
          <p id="blog-login-warning" style="color: red; display: none;">
            Blog ekleyebilmek için <a href="login.php">giriş yapınız.</a>
          </p>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Paylaş</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="widget">
  <h4 class="widget-title"><span>Sen de mentörlerimiz arasına katılmak ister misin?</span></h4>
    <button type="submit" id="mentorButton" class="btn btn-primary btn-block" name="mentorApplication" data-toggle="modal" data-target="#mentorModal">Başvur</button>
</div>
<div id="mentorModal" class="modal" style="border: 10px" tabindex="-1" role="dialog" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); justify-content: center; align-items: center;">
  <div class="modal-dialog modal-dialog-centered" role="document" style="border-radius: 8px;">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Mentörlük Başvuru Formu</h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Kapat">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="mentor-form" method="POST" action="mentor_application.php" enctype="multipart/form-data">
      <div class="modal-body">
      <div class="form-group">
                <label for="fullName">Ad Soyad</label>
                <input type="text" id="fullName" name="fullName" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="email">E-posta</label>
              <input 
                  type="email" 
                  id="email" 
                  name="email" 
                  class="form-control" 
                  value="<?php if (isset($_SESSION['user_email'])) {
                      $user_email = $_SESSION['user_email']; // Oturumdan e-posta al
                  } else {
                      $user_email = ''; // Kullanıcı giriş yapmamışsa boş bırak
                  }
                  echo htmlspecialchars($user_email); ?>" 
                  readonly 
                  required
              >
          </div>
            <div class="form-group">
                <label for="expertise">Uzmanlık Alanı</label>
                <input type="text" id="expertise" name="expertise" class="form-control" required>
            </div>
            <p id="mentor-login-warning" style="color: red; display: none;">
                Mentörlük başvurusu yapabilmek için <a href="login.php">giriş yapınız.</a>
            </p>
            <button type="submit" class="btn btn-primary">Başvur</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
      </form>
      </div>
    </div>
  </div>
</div>

  <!--<div class="widget widget-author">
    <h4 class="widget-title">Authors</h4>
    <?php foreach ($authors as $author): ?>
        <div class="media align-items-center">
            <div class="mr-3">
                <img class="widget-author-image" 
                     src="<?php echo !empty($author['profile_picture']) ? 'data:image/png;base64,' . $author['profile_picture'] : 'images/dprofile.jpg'; ?>" 
                     alt="<?php echo htmlspecialchars($author['username']); ?>">
            </div>
            <div class="media-body">
                <h5 class="mb-1">
                    <a class="post-title" href="profile.php?slug=<?php echo urlencode($author['slug']); ?>">
                        <?php echo htmlspecialchars($author['username']); ?>
                    </a>
                </h5>
                <span>Author &amp; Developer</span>
            </div>
        </div>
    <?php endforeach; ?>
  </div>
    -->
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
                  echo '<a href="tags.html" class="d-flex">';
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

</aside>
    </div>
  </div>
</section>

<footer class="footer">
  <!--
  <div class="instafeed text-center mb-5">
      <h2 class="h3 mb-4">INSTAGRAM POST</h2>
      
      <div class="instagram-slider">
        <div class="instagram-post"><img src="images/instagram/1.jpg"></div>
        <div class="instagram-post"><img src="images/instagram/2.jpg"></div>
        <div class="instagram-post"><img src="images/instagram/4.jpg"></div>
        <div class="instagram-post"><img src="images/instagram/3.jpg"></div>
        <div class="instagram-post"><img src="images/instagram/2.jpg"></div>
        <div class="instagram-post"><img src="images/instagram/1.jpg"></div>
        <div class="instagram-post"><img src="images/instagram/3.jpg"></div>
        <div class="instagram-post"><img src="images/instagram/4.jpg"></div>
        <div class="instagram-post"><img src="images/instagram/2.jpg"></div>
        <div class="instagram-post"><img src="images/instagram/4.jpg"></div>
      </div>
  </div>
    -->
  <div class="container">
      <div class="row align-items-center">
      <div class="col-md-5 text-center text-md-left mb-4">
          <ul class="list-inline footer-list mb-0">
            <li class="list-inline-item"><a href="privacy-policy.html">Privacy Policy</a></li>
            <li class="list-inline-item"><a href="terms-conditions.html">Terms Conditions</a></li>
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

  <script>
document.addEventListener('DOMContentLoaded', function () {
    checkLoginStatus();

    async function checkLoginStatus() {
        try {
            const response = await fetch('is_logged_in.php');
            if (!response.ok) {
                throw new Error('Sunucudan geçerli bir yanıt alınamadı');
            }

            const data = await response.json();

            // Blog Ekleme Formu
            const blogWarning = document.getElementById('blog-login-warning');
            const blogSubmit = document.querySelector('#blog-form button[type="submit"]');

            if (data.logged_in) {
                // Kullanıcı giriş yapmışsa uyarıyı gizle ve butonu aktif et
                if (blogWarning) blogWarning.style.display = 'none';
                if (blogSubmit) blogSubmit.disabled = false;
            } else {
                // Kullanıcı giriş yapmamışsa uyarıyı göster ve butonu pasif et
                if (blogWarning) blogWarning.style.display = 'block';
                if (blogSubmit) blogSubmit.disabled = true;
            }

            // Mentörlük Başvuru Formu
            const mentorWarning = document.getElementById('mentor-login-warning');
            const mentorSubmit = document.querySelector('#mentor-form button[type="submit"]');

            if (data.logged_in) {
                // Kullanıcı giriş yapmışsa uyarıyı gizle ve butonu aktif et
                if (mentorWarning) mentorWarning.style.display = 'none';
                if (mentorSubmit) mentorSubmit.disabled = false;
            } else {
                // Kullanıcı giriş yapmamışsa uyarıyı göster ve butonu pasif et
                if (mentorWarning) mentorWarning.style.display = 'block';
                if (mentorSubmit) mentorSubmit.disabled = true;
            }
        } catch (error) {
            console.error('Giriş durumu kontrol edilirken bir hata oluştu:', error);
        }
    }
});

  </script>

  <script src="js/chat.js"></script>

  <script src="plugins/jQuery/jquery.min.js"></script>

  <script src="plugins/bootstrap/bootstrap.min.js"></script>

  <script src="plugins/slick/slick.min.js"></script>

  <script src="plugins/instafeed/instafeed.min.js"></script>

  <script src="js/script.js"></script></body>
</html>
