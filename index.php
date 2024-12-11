<?php
session_start();
//include 'header.php';
include 'db_connection.php';

$sql = "SELECT username, slug, profile_picture FROM users ORDER BY created_at DESC LIMIT 3";
$result = $conn->query($sql);

$authors = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $authors[] = $row; // Kullanıcıları diziye ekle
    }
}
$conn->close();
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
            <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
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
            <a class="nav-link" href="contact.html">mentörler</a>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
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

          <li class="nav-item">
            <a class="nav-link" href="shop.html">yardım</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="shop.html">iletişim</a>
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
<div class="banner text-center">
  <div class="container">
    <div class="row">
      <div class="col-lg-9 mx-auto">
        <h1 class="mb-5">Would You <br> Like To Read Today?</h1>
        <ul class="list-inline widget-list-inline">
          <li class="list-inline-item"><a href="tags.html">City</a></li>
          <li class="list-inline-item"><a href="tags.html">Color</a></li>
          <li class="list-inline-item"><a href="tags.html">Creative</a></li>
          <li class="list-inline-item"><a href="tags.html">Decorate</a></li>
          <li class="list-inline-item"><a href="tags.html">Demo</a></li>
          <li class="list-inline-item"><a href="tags.html">Elements</a></li>
          <li class="list-inline-item"><a href="tags.html">Fish</a></li>
          <li class="list-inline-item"><a href="tags.html">Food</a></li>
          <li class="list-inline-item"><a href="tags.html">Nice</a></li>
          <li class="list-inline-item"><a href="tags.html">Recipe</a></li>
          <li class="list-inline-item"><a href="tags.html">Season</a></li>
          <li class="list-inline-item"><a href="tags.html">Taste</a></li>
          <li class="list-inline-item"><a href="tags.html">Tasty</a></li>
          <li class="list-inline-item"><a href="tags.html">Vlog</a></li>
          <li class="list-inline-item"><a href="tags.html">Wow</a></li>
        </ul>
      </div>
    </div>
  </div>

  
  <svg class="banner-shape-1" width="39" height="40" viewBox="0 0 39 40" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M0.965848 20.6397L0.943848 38.3906L18.6947 38.4126L18.7167 20.6617L0.965848 20.6397Z" stroke="#040306"
      stroke-miterlimit="10" />
    <path class="path" d="M10.4966 11.1283L10.4746 28.8792L28.2255 28.9012L28.2475 11.1503L10.4966 11.1283Z" />
    <path d="M20.0078 1.62949L19.9858 19.3804L37.7367 19.4024L37.7587 1.65149L20.0078 1.62949Z" stroke="#040306"
      stroke-miterlimit="10" />
  </svg>
  
  <svg class="banner-shape-2" width="39" height="39" viewBox="0 0 39 39" fill="none" xmlns="http://www.w3.org/2000/svg">
    <g filter="url(#filter0_d)">
      <path class="path"
        d="M24.1587 21.5623C30.02 21.3764 34.6209 16.4742 34.435 10.6128C34.2491 4.75147 29.3468 0.1506 23.4855 0.336498C17.6241 0.522396 13.0233 5.42466 13.2092 11.286C13.3951 17.1474 18.2973 21.7482 24.1587 21.5623Z" />
      <path
        d="M5.64626 20.0297C11.1568 19.9267 15.7407 24.2062 16.0362 29.6855L24.631 29.4616L24.1476 10.8081L5.41797 11.296L5.64626 20.0297Z"
        stroke="#040306" stroke-miterlimit="10" />
    </g>
    <defs>
      <filter id="filter0_d" x="0.905273" y="0" width="37.8663" height="38.1979" filterUnits="userSpaceOnUse"
        color-interpolation-filters="sRGB">
        <feFlood flood-opacity="0" result="BackgroundImageFix" />
        <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" />
        <feOffset dy="4" />
        <feGaussianBlur stdDeviation="2" />
        <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0" />
        <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow" />
        <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow" result="shape" />
      </filter>
    </defs>
  </svg>

  
  <svg class="banner-shape-3" width="39" height="40" viewBox="0 0 39 40" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M0.965848 20.6397L0.943848 38.3906L18.6947 38.4126L18.7167 20.6617L0.965848 20.6397Z" stroke="#040306"
      stroke-miterlimit="10" />
    <path class="path" d="M10.4966 11.1283L10.4746 28.8792L28.2255 28.9012L28.2475 11.1503L10.4966 11.1283Z" />
    <path d="M20.0078 1.62949L19.9858 19.3804L37.7367 19.4024L37.7587 1.65149L20.0078 1.62949Z" stroke="#040306"
      stroke-miterlimit="10" />
  </svg>

  
  <svg class="banner-border" height="240" viewBox="0 0 2202 240" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path
      d="M1 123.043C67.2858 167.865 259.022 257.325 549.762 188.784C764.181 125.427 967.75 112.601 1200.42 169.707C1347.76 205.869 1901.91 374.562 2201 1"
      stroke-width="2" />
  </svg>
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
                <input type="email" id="email" name="email" class="form-control" required>
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
  <svg class="footer-border" height="214" viewBox="0 0 2204 214" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M2203 213C2136.58 157.994 1942.77 -33.1996 1633.1 53.0486C1414.13 114.038 1200.92 188.208 967.765 118.127C820.12 73.7483 263.977 -143.754 0.999958 158.899"
      stroke-width="2" />
  </svg>
  
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
