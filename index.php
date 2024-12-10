<?php
session_start();
include 'header.php';
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

  <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">
  <link rel="icon" href="images/favicon.png" type="image/x-icon">
  <link rel="stylesheet" href="css/profilephoto.css">
  <style>
/* Sohbet Widget */
.chat-widget {
  position: fixed;
  bottom: 20px;
  right: 20px;
  width: 350px;
  height: 500px; /* Sabit yükseklik */
  background: #f9f9f9;
  border: 1px solid #ccc;
  border-radius: 10px;
  overflow: hidden;
  display: none;
  flex-direction: column;
  z-index: 9999;
}

.chat-header {
  background: #21ad26;
  color: white;
  padding: 10px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.chat-header button {
  background: transparent;
  border: none;
  color: white;
  font-size: 20px;
  cursor: pointer;
}

/* Sohbet İçeriği */
.chat-content {
  display: flex;
  flex-direction: column;
  height: 100%;
}

.mentor-list {
  padding: 10px;
  border-bottom: 1px solid #ccc;
  overflow-y: auto;
  max-height: 150px;
  max-height: calc(100% - 50px);
}

.status-indicator {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  margin-left: auto; /* Align to the right */
}

/* Mentör Listesi */
.mentor-item {
  display: flex;
  align-items: center;
  padding: 5px 0;
  cursor: pointer;
}

.mentor-item img {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  margin-right: 10px;
}

.mentor-list.hidden {
  display: none; /* Mentör listesi gizlemek için */
}

.chat-box {
  display: none; /* Başlangıçta gizli */
  flex-grow: 1;
  flex-direction: column;
  max-height: calc(100% - 50px);
  overflow-y: auto;
}

.chat-box.active {
  display: flex; /* Mentöre tıklandığında görünür */
}

.messages {
  flex-grow: 1;
  padding: 10px;
  overflow-y: auto;
  border-top: 1px solid #ccc;
}

textarea {
  width: 100%;
  padding: 10px;
  border: none;
  border-top: 1px solid #ccc;
  resize: none;
}

#send-btn {
  background: #21ad26;
  color: white;
  border: none;
  padding: 10px;
  cursor: pointer;
}

.chat-toggle {
  position: fixed;
  bottom: 20px;
  right: 20px;
  width: 60px;
  height: 60px;
  background: #21ad26;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  z-index: 10000;
}
#back-btn {
  font-size: 16px;
  margin-right: 10px;
  background: transparent;
  border: none;
  color: white;
  cursor: pointer;
  display: none; /* Başlangıçta gizli */
}

/* Sohbet ekranı aktifken geri butonu görünsün */
.chat-box.active #back-btn {
  display: inline-block;
}
.messages {
  padding: 10px;
  overflow-y: auto; /* Kaydırılabilir içerik */
  max-height: 400px; /* Sabit yüksekliği koru */
}

/* Soldaki mesajlar (karşı tarafın mesajları) */
.message-left {
  text-align: left; /* Yazıyı sola hizala */
  color: #000; /* Siyah yazı */
  margin: 5px 0;
  padding: 5px;
}

/* Sağdaki mesajlar (kullanıcının mesajları) */
.message-right {
  text-align: right; /* Yazıyı sağa hizala */
  color: #007bff; /* Mavi yazı */
  margin: 5px 0;
  padding: 5px;
}
    </style>
</head>
<body>
<div class="chat-widget">
  <div class="chat-header">
    <button id="back-btn" onclick="goBackToList()" style="display: none;">&larr;</button>
    <span id="chat-header-title">Mentörler ile Canlı Sohbet</span>
    <button id="close-btn" onclick="toggleChat()">&times;</button>
  </div>
    <div class="chat-content">
      <div class="mentor-list" id="mentor-list"></div>
      <div class="chat-box" id="chat-box">
        <div class="messages" id="messages"></div>
        <textarea id="message-input" placeholder="Mesajınızı yazın..."></textarea>
        <button id="send-btn" onclick="sendMessage()">Gönder</button>
      </div>
    </div>
  </div>

  <div class="chat-toggle" onclick="toggleChat()">
  💬
  </div>
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
                <i class="ti-timer"></i> <?php echo $reading_time; ?> Min To Read
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
      <form id="addPostForm" method="POST" action="add_post.php" enctype="multipart/form-data">
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

      <!-- Mentör Ol Button 
<div class="widget">
    <h4 class="widget-title"><span>Sen de mentörlerimiz arasına katılmak ister misin?</span></h4>
      <button type="submit" id="mentorButton" class="btn btn-primary btn-block" name="mentorapplication" data-toggle="modal" data-target="#mentorModal">Mentör Ol</button>
  </div>

 Mentör Ol Modal 
<div id="mentorModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 1000; justify-content: center; align-items: center;">
    <div class="modal-content" style="background: #fff; padding: 20px; border-radius: 8px; width: 90%; max-width: 500px;">
        <h3>Mentörlük Başvuru Formu</h3>
        <form id="mentorForm" method="POST" action="mentor_application.php">
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
            <button type="submit" class="btn btn-success">Başvur</button>
            <button type="button" id="closeModal" class="btn btn-secondary">İptal</button>
        </form>
    </div>
</div>  -->
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
      <form id="mentorForm" method="POST" action="mentor_application.php" enctype="multipart/form-data">
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
            <button type="button" id="closeModal" class="btn btn-secondary">İptal</button>
      </form>
    </div>
  </div>
</div>
                    </div>
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

  <script>
async function updateChatHeader() {
  const response = await fetch('get_user_role.php');
  const { role } = await response.json(); // Kullanıcı rolünü al
  const chatHeader = document.querySelector('.chat-header span');
  chatHeader.textContent = role === 'mentor' 
    ? 'Kullanıcılar ile Canlı Sohbet' 
    : 'Mentörler ile Canlı Sohbet';
}

// Sohbet widget'ı ve listeyi yükle
document.addEventListener('DOMContentLoaded', () => {
  updateChatHeader(); // Başlığı güncelle
  loadChatList(); // Sohbet listesi yüklensin
});

let chatInterval;

async function loadChatList() {
  const response = await fetch('get_chat_list.php');
  const chatList = await response.json();
  const mentorList = document.getElementById('mentor-list');
  mentorList.innerHTML = '';

  chatList.forEach(person => {
    const div = document.createElement('div');
    div.className = 'mentor-item';
    div.innerHTML = person.profile_picture
      ? `<img src="data:image/jpeg;base64,${person.profile_picture}" alt="${person.username}">`
      : `<img src="images/dprofile.jpg" alt="Default Profile Picture">`;

    div.innerHTML += `<span>${person.username}</span>`;
    div.innerHTML += `<div class="status-indicator" style="background-color: ${
  (person.is_online == 1) ? 'green' : 'gray'
};"></div>`;
    div.onclick = () => openChat(person.user_id, person.username);
    mentorList.appendChild(div);
  });
}

// Sohbet widget'ını aç/kapa
function toggleChat() {
  const chatWidget = document.querySelector('.chat-widget');
  const chatToggle = document.querySelector('.chat-toggle');
  const chatBox = document.getElementById('chat-box');
  const mentorList = document.getElementById('mentor-list');
  const backBtn = document.getElementById('back-btn');

  // Sohbet widget'ını aç/kapa
  if (chatWidget.style.display === 'flex') {
    chatWidget.style.display = 'none'; // Kapat
    chatToggle.style.display = 'flex'; // Balon görünsün
    chatBox.classList.remove('active'); // Sohbet ekranını gizle
    mentorList.style.display = 'block'; // Mentör listesi gösterilsin
    backBtn.style.display = 'none'; // Geri butonunu gizle

    // Mesaj yenileme intervalini durdur
    if (chatInterval) {
      clearInterval(chatInterval);
    }
  } else {
    chatWidget.style.display = 'flex'; // Aç
    chatToggle.style.display = 'none'; // Balon gizlensin
  }
}

// Sohbet ekranını aç ve mesajları yükle
async function openChat(mentorId, mentorName) {
  const chatBox = document.getElementById('chat-box');
  const messagesDiv = document.getElementById('messages');
  const mentorList = document.getElementById('mentor-list');
  const backBtn = document.getElementById('back-btn');

  mentorList.style.display = 'none'; // Mentör listesini gizle
  chatBox.dataset.mentorId = mentorId;
  chatBox.classList.add('active'); // Sohbet ekranını görünür yap
  backBtn.style.display = 'inline-block'; // Geri butonunu göster
  messagesDiv.innerHTML = `<h3>${mentorName} ile sohbet</h3>`; // Başlığı güncelle

  await loadMessages(mentorId); // Mesajları yükle

  // Mesajları otomatik yenile
  if (chatInterval) {
    clearInterval(chatInterval); // Daha önceki interval varsa temizle
  }
  chatInterval = setInterval(() => {
    loadMessages(mentorId);
  }, 2000);

}

// Sohbet listesini geri yükle
function goBackToList() {
  const chatBox = document.getElementById('chat-box');
  const mentorList = document.getElementById('mentor-list');
  const backBtn = document.getElementById('back-btn');

  chatBox.classList.remove('active'); // Sohbet ekranını gizle
  mentorList.style.display = 'block'; // Liste ekranını göster
  backBtn.style.display = 'none'; // Geri butonunu gizle

  if (chatInterval) {
    clearInterval(chatInterval); // Mesaj yenileme intervalini durdur
  }
}

// Sohbet ekranını kapat
function closeChat() {
  const chatBox = document.getElementById('chat-box');
  const mentorList = document.getElementById('mentor-list');
  chatBox.classList.remove('active');
  mentorList.style.display = 'block'; // Mentör listesini tekrar göster
  if (chatInterval) {
    clearInterval(chatInterval); // Interval durdur
  }
}

// Mesajları yükle
async function loadMessages(mentorId) {
  const response = await fetch(`get_messages.php?mentorId=${mentorId}`);
  const messages = await response.json();
  const messagesDiv = document.getElementById('messages');
  messagesDiv.innerHTML = ''; // Önceki mesajları temizle

  // Son mesajı bulmak için toplam mesaj sayısını kontrol edin
  const totalMessages = messages.length;

  messages.forEach((message, index) => {
    const isCurrentUser = message.is_current_user;
    const div = document.createElement('div');
    div.className = isCurrentUser == 1 ? 'message-right' : 'message-left';

    // Mesaj içeriğini ekle
    if (isCurrentUser == 1) {
      div.innerHTML = `<span>${message.message}</span>`;
    } else {
      div.innerHTML = `<strong>${message.sender_name}:</strong> <span>${message.message}</span>`;
    }

    messagesDiv.appendChild(div);
  });
}

// Mesaj gönderme
async function sendMessage() {
  const mentorId = document.getElementById('chat-box').dataset.mentorId;
  const messageInput = document.getElementById('message-input');
  const message = messageInput.value;
  if (message.trim()) {
    const response = await fetch('send_message.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ mentorId, message }),
    });
    const result = await response.json();
    if (result.success) {
      messageInput.value = ''; // Mesajı temizle
      loadMessages(mentorId); // Mesajları yeniden yükle
    } else {
      alert('Mesaj gönderilirken bir hata oluştu.');
    }
  }
}

// Giriş durumu kontrolü
async function checkLoginStatus() {
  const response = await fetch('is_logged_in.php');
  const data = await response.json();

  // Blog Ekleme Formu
  const blogForm = document.getElementById('blog-form');
  const blogWarning = document.getElementById('blog-login-warning');
  const blogSubmit = document.getElementById('submit-blog');

  // Mentörlük Başvuru Formu
  const mentorForm = document.getElementById('mentor-form');
  const mentorWarning = document.getElementById('mentor-login-warning');
  const mentorSubmit = document.getElementById('submit-mentor');

  if (data.logged_in) {
    // Kullanıcı giriş yapmışsa butonları aktif et
    if (blogForm) blogSubmit.disabled = false;
    if (blogWarning) blogWarning.style.display = 'none';

    if (mentorForm) mentorSubmit.disabled = false;
    if (mentorWarning) mentorWarning.style.display = 'none';
  } else {
    // Kullanıcı giriş yapmamışsa uyarı göster
    if (blogForm) blogSubmit.disabled = true;
    if (blogWarning) blogWarning.style.display = 'block';

    if (mentorForm) mentorSubmit.disabled = true;
    if (mentorWarning) mentorWarning.style.display = 'block';
  }
}

setInterval(async () => {
  await fetch('update_activity.php', { method: 'POST' });
}, 12000);
  </script>

  <script src="plugins/jQuery/jquery.min.js"></script>

  <script src="plugins/bootstrap/bootstrap.min.js"></script>

  <script src="plugins/slick/slick.min.js"></script>

  <script src="plugins/instafeed/instafeed.min.js"></script>

  <script src="js/script.js"></script></body>
</html>
