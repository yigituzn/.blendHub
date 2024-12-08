<?php
session_start();

//include 'header.php';
include 'db_connection.php';

// Slug bilgisi URL'den alınır
$slug = $_GET['slug'] ?? null;

if (!$slug) {
    die(header("Location: 404.html"));
}

$sql = "SELECT user_id, username, created_at, profile_picture FROM users WHERE slug = ?";
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
  <!-- navigation -->
  <header class="navigation fixed-top">
  <div class="container">
    <nav class="navbar navbar-expand-lg navbar-white">
      <a class="navbar-brand order-1" href="index.php">
        <img class="img-fluid" width="100px" src="images/logo.png">
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
            <a class="nav-link" href="shop.html">Yardım</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="shop.html">İletişim</a>
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
        <div class="dropdown" style="margin-left: 25px;">
            <a href="#" class="dropdown-toggle" id="profileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
        </div>
      </div>

    </nav>
  </div>
</header>
<!-- /navigation -->

<div class="author">
	<div class="container">
		<div class="row no-gutters justify-content-center">
			<div class="col-lg-3 col-md-4 mb-4 mb-md-0">
				
      <img class="author-image" src="<?php echo $profile_picture; ?>" alt="Profil Fotoğrafı">
				
			</div>
			<div class="col-md-8 col-lg-6 text-center text-md-left">
				<h3 class="mb-2"><?php echo htmlspecialchars($user['username']); ?></h2>
					<strong class="mb-2 d-block">Author &amp; developer of Bexer, Biztrox theme</strong>
					<div class="content">
                        <p>Şu tarihten itibaren üye: <?php echo $membership_duration; ?></p>
					</div>
					
					<a class="post-count mb-1" href="author-single.html#post"><i class="ti-pencil-alt mr-2"></i><span
							class="text-primary">2</span> Posts by this author</a>
					<ul class="list-inline social-icons">
						
						<li class="list-inline-item"><a href="#"><i class="ti-facebook"></i></a></li>
						
						<li class="list-inline-item"><a href="#"><i class="ti-twitter-alt"></i></a></li>
						
						<li class="list-inline-item"><a href="#"><i class="ti-github"></i></a></li>
						
						<li class="list-inline-item"><a href="#"><i class="ti-link"></i></a></li>
						
					</ul>
			</div>
		</div>
	</div>
	
	<svg class="author-shape-1" width="39" height="40" viewBox="0 0 39 40" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M0.965848 20.6397L0.943848 38.3906L18.6947 38.4126L18.7167 20.6617L0.965848 20.6397Z" stroke="#040306"
			stroke-miterlimit="10" />
		<path class="path" d="M10.4966 11.1283L10.4746 28.8792L28.2255 28.9012L28.2475 11.1503L10.4966 11.1283Z" />
		<path d="M20.0078 1.62949L19.9858 19.3804L37.7367 19.4024L37.7587 1.65149L20.0078 1.62949Z" stroke="#040306"
			stroke-miterlimit="10" />
	</svg>

	
	<svg class="author-shape-2" width="39" height="39" viewBox="0 0 39 39" fill="none" xmlns="http://www.w3.org/2000/svg">
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

	
	<svg class="author-shape-3" width="39" height="40" viewBox="0 0 39 40" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M0.965848 20.6397L0.943848 38.3906L18.6947 38.4126L18.7167 20.6617L0.965848 20.6397Z" stroke="#040306"
			stroke-miterlimit="10" />
		<path class="path" d="M10.4966 11.1283L10.4746 28.8792L28.2255 28.9012L28.2475 11.1503L10.4966 11.1283Z" />
		<path d="M20.0078 1.62949L19.9858 19.3804L37.7367 19.4024L37.7587 1.65149L20.0078 1.62949Z" stroke="#040306"
			stroke-miterlimit="10" />
	</svg>

	
	<svg class="author-border" height="240" viewBox="0 0 2202 240" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path
      d="M1 123.043C67.2858 167.865 259.022 257.325 549.762 188.784C764.181 125.427 967.75 112.601 1200.42 169.707C1347.76 205.869 1901.91 374.562 2201 1"
      stroke-width="2" />
  </svg>
</div>

<section class="section-sm" id="post">
	<div class="container">
		<div class="row">
			
			<div class="col-lg-8 mx-auto">
				<article class="card mb-4">
					
					<div class="post-slider">
						<img src="images/post/post-7.jpg" class="card-img-top" alt="post-thumb">
					</div>
					
					<div class="card-body">
						<h3 class="mb-3"><a class="post-title" href="post-details.html">Advice From a Twenty Something</a></h3>
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
						<p>It’s no secret that the digital industry is booming. From exciting startups to global brands, companies are reaching out to digital agencies, responding to the new possibilities available.</p>
						<a href="post-details.html" class="btn btn-outline-primary">Read More</a>
					</div>
				</article>
			</div>
			
			<div class="col-lg-8 mx-auto">
				<article class="card mb-4">
					<div class="post-slider">
						<img src="images/post/post-1.jpg" class="card-img-top" alt="post-thumb">
					</div>
					<div class="card-body">
						<h3 class="mb-3"><a class="post-title" href="post/post-1/">Use apples to give your bakes caramel and a moist texture</a></h3>
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
								</ul>
							</li>
						</ul>
						<p>It’s no secret that the digital industry is booming. From exciting startups to global brands, companies are reaching out to digital agencies, responding to the new possibilities available.</p>
						<a href="post/post-1/" class="btn btn-outline-primary">Read More</a>
					</div>
				</article>
			</div>
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