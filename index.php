<?php
session_start();

// Kullanıcı giriş yapmadıysa login sayfasına yönlendir
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
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
  <style>
    /* Profil resmi boyutu ve stil */
    .dropdown img {
      vertical-align: middle; /* Dikey olarak hizalar */
      margin: 0; /* Fazladan boşluk bırakmaz */
      border: 2px solid #ffffff; /* Beyaz kenarlık */
      cursor: pointer;
    }
    /* Açılır menü stili */
    .dropdown-menu {
      min-width: 200px; /* Genişlik */
      border-radius: 8px; /* Yuvarlak köşeler */
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Hafif gölge */
    }

    .dropdown-menu a {
      color: #333; /* Yazı rengi */
      padding: 10px 15px; /* İç boşluk */
      text-decoration: none; /* Alt çizgi yok */
    }

    .dropdown-menu a:hover {
      background-color: #f0f0f0; /* Üzerine gelince arka plan */
    }
    .default-profile {
      background-image: url('images/dprofile.jpg');
      background-size: cover;
      background-position: center;
      transform: translateY(+20%);
      width: 40px;
      height: 40px;
      border-radius: 50%; /* Yuvarlak yapar */
    }
  </style>
</head>
<body>
<header class="navigation fixed-top">
  <div class="container">
    <nav class="navbar navbar-expand-lg navbar-white">
      <a class="navbar-brand order-1" href="index.php">
        <img class="img-fluid" width="100px" src="images/logo.png"
          alt="Reader | Hugo Personal Blog Template">
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
              hakkımızda <i class="ti-angle-down ml-1"></i>
            </a>
            <div class="dropdown-menu">
              
              <a class="dropdown-item" href="about-me.html">About Me</a>
              
              <a class="dropdown-item" href="about-us.html">About Us</a>
              
            </div>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="contact.html">Contact</a>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
              aria-expanded="false">Pages <i class="ti-angle-down ml-1"></i>
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
            <a class="nav-link" href="shop.html">Shop</a>
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
              <div class="default-profile"></div>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profileDropdown">
              <a class="dropdown-item" href="profile.php">Profilim</a>
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

<div class="banner text-center">
  <div class="container">
    <div class="row">
      <div class="col-lg-9 mx-auto">
        <h1 class="mb-5">Esmanur <br> Like To Read Today?</h1>
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

<section class="section-sm">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8  mb-5 mb-lg-0">
  <h2 class="h5 section-title">Recent Post</h2>
  <article class="card mb-4">
  <div class="post-slider">
      <img src="images/post/post-10.jpg" class="card-img-top" alt="post-thumb">
      <img src="images/post/post-1.jpg" class="card-img-top" alt="post-thumb">
  </div>
  <div class="card-body">
      <h3 class="mb-3"><a class="post-title" href="post-elements.html">Elements That You Can Use In This Template.</a></h3>
      <ul class="card-meta list-inline">
      <li class="list-inline-item">
          <a href="author-single.html" class="card-meta-author">
          <img src="images/john-doe.jpg" alt="John Doe">
          <span>John Doe</span>
          </a>
      </li>
      <li class="list-inline-item">
          <i class="ti-timer"></i>3 Min To Read
      </li>
      <li class="list-inline-item">
          <i class="ti-calendar"></i>15 jan, 2020
      </li>
      <li class="list-inline-item">
          <ul class="card-meta-tag list-inline">
          <li class="list-inline-item"><a href="tags.html">Demo</a></li>
          <li class="list-inline-item"><a href="tags.html">Elements</a></li>
          </ul>
      </li>
      </ul>
      <p>Heading example Here is example of hedings. You can use this heading by following markdownify rules. For example: use # for heading 1 and use ###### for heading 6.</p>
      <a href="post-elements.html" class="btn btn-outline-primary">Read More</a>
  </div>
  </article>

  <article class="card mb-4">
  <div class="post-slider">
      <img src="images/post/post-3.jpg" class="card-img-top" alt="post-thumb">
  </div>
  <div class="card-body">
      <h3 class="mb-3"><a class="post-title" href="post-details.html">Advice From a Twenty Something</a></h3>
      <ul class="card-meta list-inline">
      <li class="list-inline-item">
          <a href="author-single.html" class="card-meta-author">
          <img src="images/john-doe.jpg">
          <span>Mark Dinn</span>
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
          <li class="list-inline-item"><a href="tags.html">Decorate</a></li>
          <li class="list-inline-item"><a href="tags.html">Creative</a></li>
          </ul>
      </li>
      </ul>
      <p>It’s no secret that the digital industry is booming. From exciting startups to global brands, companies are reaching out to digital agencies, responding to the new possibilities available.</p>
      <a href="post-details.html" class="btn btn-outline-primary">Read More</a>
  </div>
  </article>

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
  
  <article class="card mb-4">
  <div class="card-body">
      <h3 class="mb-3"><a class="post-title" href="post-details.html">Cheerful Loving Couple Bakers Drinking Coffee</a></h3>
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
          <li class="list-inline-item"><a href="tags.html">Wow</a></li>
          <li class="list-inline-item"><a href="tags.html">Tasty</a></li>
          </ul>
      </li>
      </ul>
      <p>It’s no secret that the digital industry is booming. From exciting startups to global brands, companies are reaching out to digital agencies, responding to the new possibilities available.</p>
      <a href="post-details.html" class="btn btn-outline-primary">Read More</a>
  </div>
  </article>
  
  <article class="card mb-4">
  <div class="post-slider">
      <img src="images/post/post-5.jpg" class="card-img-top" alt="post-thumb">
  </div>
  <div class="card-body">
      <h3 class="mb-3"><a class="post-title" href="post-details.html">How To Make Cupcakes and Cashmere Recipe At Home</a></h3>
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
      <p>It’s no secret that the digital industry is booming. From exciting startups to global brands, companies are reaching out to digital agencies, responding to the new possibilities available.</p>
      <a href="post-details.html" class="btn btn-outline-primary">Read More</a>
  </div>
  </article>
  
  <article class="card mb-4">
  <div class="post-slider">
      <img src="images/post/post-8.jpg" class="card-img-top" alt="post-thumb">
      <img src="images/post/post-9.jpg" class="card-img-top" alt="post-thumb">
  </div>
  <div class="card-body">
      <h3 class="mb-3"><a class="post-title" href="post-details.html">How To Make Cupcakes and Cashmere Recipe At Home</a></h3>
      <ul class="card-meta list-inline">
      <li class="list-inline-item">
          <a href="author-single.html" class="card-meta-author">
          <img src="images/john-doe.jpg" alt="John Doe">
          <span>John Doe</span>
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
  
  <ul class="pagination justify-content-center">
    <li class="page-item page-item active ">
        <a href="#!" class="page-link">1</a>
    </li>
    <li class="page-item">
        <a href="#!" class="page-link">2</a>
    </li>
    <li class="page-item">
        <a href="#!" class="page-link">&raquo;</a>
    </li>
  </ul>
</div>
      <aside class="col-lg-4 sidebar-home">
  <div class="widget">
    <h4 class="widget-title"><span>Search</span></h4>
    <form action="#!" class="widget-search">
      <input class="mb-3" id="search-query" name="s" type="search" placeholder="Type &amp; Hit Enter...">
      <i class="ti-search"></i>
      <button type="submit" class="btn btn-primary btn-block">Search</button>
    </form>
  </div>

  <div class="widget widget-about">
    <h4 class="widget-title">Hi, I am Alex!</h4>
    <img class="img-fluid" src="images/author.jpg" alt="Themefisher">
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vel in in donec iaculis tempus odio nunc laoreet . Libero ullamcorper.</p>
    <ul class="list-inline social-icons mb-3">
      
      <li class="list-inline-item"><a href="#"><i class="ti-facebook"></i></a></li>
      
      <li class="list-inline-item"><a href="#"><i class="ti-twitter-alt"></i></a></li>
      
      <li class="list-inline-item"><a href="#"><i class="ti-linkedin"></i></a></li>
      
      <li class="list-inline-item"><a href="#"><i class="ti-github"></i></a></li>
      
      <li class="list-inline-item"><a href="#"><i class="ti-youtube"></i></a></li>
      
    </ul>
    <a href="about-me.html" class="btn btn-primary mb-2">About me</a>
  </div>
  
  <div class="promotion">
    <img src="images/promotion.jpg" class="img-fluid w-100">
    <div class="promotion-content">
      <h5 class="text-white mb-3">Create Stunning Website!!</h5>
      <p class="text-white mb-4">Lorem ipsum dolor sit amet, consectetur sociis. Etiam nunc amet id dignissim. Feugiat id tempor vel sit ornare turpis posuere.</p>
      <a href="https://themefisher.com/" class="btn btn-primary">Get Started</a>
    </div>
  </div>

  <div class="widget widget-author">
    <h4 class="widget-title">Authors</h4>
    <div class="media align-items-center">
      <div class="mr-3">
        <img class="widget-author-image" src="images/john-doe.jpg">
      </div>
      <div class="media-body">
        <h5 class="mb-1"><a class="post-title" href="author-single.html">Charls Xaviar</a></h5>
        <span>Author &amp; developer of Bexer, Biztrox theme</span>
      </div>
    </div>
    <div class="media align-items-center">
      <div class="mr-3">
        <img class="widget-author-image" src="images/kate-stone.jpg">
      </div>
      <div class="media-body">
        <h5 class="mb-1"><a class="post-title" href="author-single.html">Kate Stone</a></h5>
        <span>Author &amp; developer of Bexer, Biztrox theme</span>
      </div>
    </div>
    <div class="media align-items-center">
      <div class="mr-3">
        <img class="widget-author-image" src="images/john-doe.jpg" alt="John Doe">
      </div>
      <div class="media-body">
        <h5 class="mb-1"><a class="post-title" href="author-single.html">John Doe</a></h5>
        <span>Author &amp; developer of Bexer, Biztrox theme</span>
      </div>
    </div>
  </div>
  
  <div class="widget">
    <h4 class="widget-title"><span>Never Miss A News</span></h4>
    <form action="#!" method="post" name="mc-embedded-subscribe-form" target="_blank"
      class="widget-search">
      <input class="mb-3" id="search-query" name="s" type="search" placeholder="Your Email Address">
      <i class="ti-email"></i>
      <button type="submit" class="btn btn-primary btn-block" name="subscribe">Subscribe now</button>
      <div style="position: absolute; left: -5000px;" aria-hidden="true">
        <input type="text" name="b_463ee871f45d2d93748e77cad_a0a2c6d074" tabindex="-1">
      </div>
    </form>
  </div>

  <div class="widget widget-categories">
    <h4 class="widget-title"><span>Categories</span></h4>
    <ul class="list-unstyled widget-list">
      <li><a href="tags.html" class="d-flex">Creativity <small class="ml-auto">(4)</small></a></li>
      <li><a href="tags.html" class="d-flex">Demo <small class="ml-auto">(1)</small></a></li>
      <li><a href="tags.html" class="d-flex">Elements <small class="ml-auto">(1)</small></a></li>
      <li><a href="tags.html" class="d-flex">Food <small class="ml-auto">(1)</small></a></li>
      <li><a href="tags.html" class="d-flex">Microwave <small class="ml-auto">(1)</small></a></li>
      <li><a href="tags.html" class="d-flex">Natural <small class="ml-auto">(3)</small></a></li>
      <li><a href="tags.html" class="d-flex">Newyork city <small class="ml-auto">(1)</small></a></li>
      <li><a href="tags.html" class="d-flex">Nice <small class="ml-auto">(1)</small></a></li>
      <li><a href="tags.html" class="d-flex">Tech <small class="ml-auto">(2)</small></a></li>
      <li><a href="tags.html" class="d-flex">Videography <small class="ml-auto">(1)</small></a></li>
      <li><a href="tags.html" class="d-flex">Vlog <small class="ml-auto">(1)</small></a></li>
      <li><a href="tags.html" class="d-flex">Wondarland <small class="ml-auto">(1)</small></a></li>
    </ul>
  </div>
  <div class="widget">
    <h4 class="widget-title"><span>Tags</span></h4>
    <ul class="list-inline widget-list-inline widget-card">
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
  <div class="widget">
    <h4 class="widget-title">Recent Post</h4>

    <article class="widget-card">
      <div class="d-flex">
        <img class="card-img-sm" src="images/post/post-10.jpg">
        <div class="ml-3">
          <h5><a class="post-title" href="post/elements/">Elements That You Can Use In This Template.</a></h5>
          <ul class="card-meta list-inline mb-0">
            <li class="list-inline-item mb-0">
              <i class="ti-calendar"></i>15 jan, 2020
            </li>
          </ul>
        </div>
      </div>
    </article>
    
    <article class="widget-card">
      <div class="d-flex">
        <img class="card-img-sm" src="images/post/post-3.jpg">
        <div class="ml-3">
          <h5><a class="post-title" href="post-details.html">Advice From a Twenty Something</a></h5>
          <ul class="card-meta list-inline mb-0">
            <li class="list-inline-item mb-0">
              <i class="ti-calendar"></i>14 jan, 2020
            </li>
          </ul>
        </div>
      </div>
    </article>
    
    <article class="widget-card">
      <div class="d-flex">
        <img class="card-img-sm" src="images/post/post-7.jpg">
        <div class="ml-3">
          <h5><a class="post-title" href="post-details.html">Advice From a Twenty Something</a></h5>
          <ul class="card-meta list-inline mb-0">
            <li class="list-inline-item mb-0">
              <i class="ti-calendar"></i>14 jan, 2020
            </li>
          </ul>
        </div>
      </div>
    </article>
  </div>

  <!-- Social -->
  <div class="widget">
    <h4 class="widget-title"><span>Social Links</span></h4>
    <ul class="list-inline widget-social">
      <li class="list-inline-item"><a href="#"><i class="ti-facebook"></i></a></li>
      <li class="list-inline-item"><a href="#"><i class="ti-twitter-alt"></i></a></li>
      <li class="list-inline-item"><a href="#"><i class="ti-linkedin"></i></a></li>
      <li class="list-inline-item"><a href="#"><i class="ti-github"></i></a></li>
      <li class="list-inline-item"><a href="#"><i class="ti-youtube"></i></a></li>
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

  <script src="plugins/jQuery/jquery.min.js"></script>

  <script src="plugins/bootstrap/bootstrap.min.js"></script>

  <script src="plugins/slick/slick.min.js"></script>

  <script src="plugins/instafeed/instafeed.min.js"></script>

  <script src="js/script.js"></script></body>
</html>
