<?php
session_start();

if (empty($_GET['s'])) {
    die("Lütfen bir arama terimi girin.");
}

function getGoogleSearchResults($query) {
  $api_key = 'AIzaSyDq_byUpj_4V3Ef78WKdXECunGNE3Srb1I'; // Google API Anahtarınız
  $cx = '20f48ef3b267743a2'; // Custom Search Engine ID
  $url = "https://www.googleapis.com/customsearch/v1?q=" . urlencode($query) . "&key=" . $api_key . "&cx=" . $cx;

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // SSL doğrulamayı devre dışı bırak
  $response = curl_exec($ch);

  if (curl_errno($ch)) {
      die("cURL Hatası: " . curl_error($ch));
  }

  curl_close($ch);

  if (!$response) {
      die("Google API'den yanıt alınamadı.");
  }

  $data = json_decode($response, true);

  if (isset($data['error'])) {
      die("Google API Hatası: " . $data['error']['message']);
  }

  return $data;
}

if (isset($_GET['s']) && !empty($_GET['s'])) {
  $search_query = $_GET['s'];
  $google_results = getGoogleSearchResults($search_query);

  if (isset($google_results['items']) && !empty($google_results['items'])) {
      $results = $google_results['items'];
  } else {
      $results = [];
  }
}
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

<div class="py-3"></div>

<!--<script async src="https://cse.google.com/cse.js?cx=20f48ef3b267743a2">
</script>
<div class="gcse-search"></div>-->
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 mb-4">
                <h1 class="h2 mb-4">Sonuç bulunamadı</h1>
                <p>Ancak aşağıda ilgili Google sonuçlarını bulabilirsiniz:</p>
            </div>
            <div class="col-lg-10">
                <?php if (!empty($results)): ?>
                    <ul class="list-group">
                        <?php foreach ($results as $item): ?>
                            <li class="list-group-item">
                                <a href="<?php echo htmlspecialchars($item['link']); ?>" target="_blank">
                                    <h5><?php echo htmlspecialchars($item['title']); ?></h5>
                                </a>
                                <p><?php echo htmlspecialchars($item['snippet']); ?></p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Google'da bu arama için de sonuç bulunamadı.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

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

  <script src="plugins/jQuery/jquery.min.js"></script>

  <script src="plugins/bootstrap/bootstrap.min.js"></script>

  <script src="plugins/slick/slick.min.js"></script>

  <script src="plugins/instafeed/instafeed.min.js"></script>

  <script src="js/script.js"></script></body>
</html>