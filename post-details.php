<?php
session_start();
include 'db_connection.php';

// Gelen post_id parametresini alın
$post_id = $_GET['post_id'] ?? null;
if (!$post_id) {
    die(header("Location: 404.html"));
}

// Post bilgilerini alın
$post_query = "SELECT posts.*, users.username, users.profile_picture 
               FROM posts 
               INNER JOIN users ON posts.user_id = users.user_id 
               WHERE posts.post_id = ?";
$stmt = $conn->prepare($post_query);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$post_result = $stmt->get_result();

if ($post_result->num_rows === 0) {
    die(header("Location: 404.html"));
}

$post = $post_result->fetch_assoc();

// Kategorileri çek
$category_query = "SELECT categories.name FROM postcategories 
                   JOIN categories ON postcategories.category_id = categories.category_id 
                   WHERE postcategories.post_id = ?";
$category_stmt = $conn->prepare($category_query);
$category_stmt->bind_param("i", $post_id);
$category_stmt->execute();
$category_result = $category_stmt->get_result();
$categories = [];
while ($category = $category_result->fetch_assoc()) {
    $categories[] = $category['name'];
}

// Okuma süresini hesapla
function calculateReadingTime($content, $words_per_minute = 200) {
    $word_count = str_word_count(strip_tags($content));
    return max(ceil($word_count / $words_per_minute), 1);
}
$reading_time = calculateReadingTime($post['content']);

// Yorumları ve cevapları al
$comment_query = "SELECT comments.*, users.username, users.slug, users.profile_picture 
                   FROM comments 
                   INNER JOIN users ON comments.user_id = users.user_id 
                   WHERE comments.post_id = ? AND comments.status = 'approved'
                   ORDER BY comments.created_at ASC";
$comment_stmt = $conn->prepare($comment_query);
$comment_stmt->bind_param("i", $post_id);
$comment_stmt->execute();
$comment_result = $comment_stmt->get_result();
$comments = [];
while ($row = $comment_result->fetch_assoc()) {
    $comments[$row['parent_comment_id']][] = $row;
}

// Yorum ve cevap ekleme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment = trim($_POST['comment'] ?? '');
    $parent_id = $_POST['parent_id'] ?? null;
    $user_id = $_SESSION['user_id'] ?? null;

    if ($user_id && !empty($comment)) {
        $insert_comment_query = "INSERT INTO comments (post_id, user_id, content, parent_comment_id, status) 
                                 VALUES (?, ?, ?, ?, 'pending')";
        $insert_stmt = $conn->prepare($insert_comment_query);
        $insert_stmt->bind_param("iisi", $post_id, $user_id, $comment, $parent_id);
        $insert_stmt->execute();
        $insert_stmt->close();
        echo "<script>alert('Yorumunuz incelenmek üzere gönderildi.'); window.location.href = 'post-details.php?post_id=$post_id';</script>";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="tr-TR"><head>
  <meta charset="utf-8">
  <title>.blendHub | <?php echo htmlspecialchars($post['title']); ?></title>

  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  
  <link rel="stylesheet" href="plugins/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="plugins/themify-icons/themify-icons.css">
  <link rel="stylesheet" href="plugins/slick/slick.css">

  <link rel="stylesheet" href="css/style.css" media="screen">

  <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">
  <link rel="icon" href="images/favicon.png" type="image/x-icon">
  <style>
.d-none {
    display: none; /* Görünmez yapar */
}
.reply-btn {
    color: #007bff; /* Yanıtla butonunun rengini belirler */
    cursor: pointer; /* El işareti */
    border: none;
    background: none;
    padding: 0;
    font-size: 14px;
    text-decoration: underline; /* Alt çizgi */
}
.media .media {
  margin-top: 20px; /* Alt yorumlar için üst boşluk */
}
    </style>
</head>
<body>
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
        </div>
      </div>

    </nav>
  </div>
</header>

<div class="py-4"></div>
<section class="section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-9 mb-5">
        <article>
          <div class="post-slider mb-4">
            <img src="<?php echo $post['featured_image'] ? 'data:image/jpeg;base64,' . $post['featured_image'] : 'images/default_post.jpg'; ?>" class="card-img" alt="<?php echo htmlspecialchars($post['title']); ?>">
          </div>
          
          <h1 class="h2"><?php echo htmlspecialchars($post['title']); ?></h1>
          <ul class="card-meta my-3 list-inline">
            <li class="list-inline-item">
              <a href="profile.php?slug=<?php echo urlencode($post['username']); ?>" class="card-meta-author">
                <img src="<?php echo $post['profile_picture'] ? 'data:image/jpeg;base64,' . $post['profile_picture'] : 'images/dprofile.jpg'; ?>" alt="<?php echo htmlspecialchars($post['username']); ?>">
                <span><?php echo htmlspecialchars($post['username']); ?></span>
              </a>
            </li>
            <li class="list-inline-item"><i class="ti-timer"></i><?php echo $reading_time; ?> Min To Read</li>
            <li class="list-inline-item">
  <i class="ti-calendar"></i>
  <?php
  $formatter = new IntlDateFormatter('tr_TR', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
  echo $formatter->format(new DateTime($post['created_at']));
  ?>
</li>
            <li class="list-inline-item">
              <ul class="card-meta-tag list-inline">
                <?php foreach ($categories as $category) : ?>
                  <li class="list-inline-item"><a href="categories.php?category=<?php echo urlencode($category); ?>"><?php echo htmlspecialchars($category); ?></a></li>
                <?php endforeach; ?>
              </ul>
            </li>
          </ul>
          <div class="content">
            <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
          </div>
        </article>
        
      </div>

      <div class="col-lg-9 col-md-12">
          <div class="mb-5 border-top mt-4 pt-5">
              <h3 class="mb-4">Yorumlar</h3>
              <?php
              function renderComments($comments, $parent_id = null) {   
                if (isset($comments[$parent_id])) {
                  foreach ($comments[$parent_id] as $comment) {
                ?>
              <div class="media d-block d-sm-flex mb-4 pb-4">
                  
                  <?php if ($comment['parent_comment_id']) : ?>
                    <img class="mr-3" src="images/post/arrow.png">
                <?php endif; ?>
                <a class="d-inline-block mr-2 mb-3 mb-md-0" href="profile.php?slug=<?php echo urlencode($comment['slug']); ?>">
                  <img src="<?php echo $comment['profile_picture'] ? 'data:image/jpeg;base64,' . $comment['profile_picture'] : 'images/dprofile.jpg'; ?>" class="mr-3 rounded-circle">    
                  </a>
                  <div class="media-body">
                  <a href="profile.php?slug=<?php echo urlencode($comment['slug']); ?>" class="h4 d-inline-block mb-3">
                        <?php echo htmlspecialchars($comment['username']); ?>
                      </a>

                      <p><?php echo htmlspecialchars($comment['content']); ?></p>  
                      <span class="text-black-800 mr-3 font-weight-600"><?php
                $formatter = new IntlDateFormatter('tr_TR', IntlDateFormatter::LONG, IntlDateFormatter::SHORT);
                echo $formatter->format(new DateTime($comment['created_at']));
                ?></span>
                  <button class="text-primary font-weight-600 reply-btn" type="button" style="text-decoration: none; "data-comment-id="<?php echo $comment['comment_id']; ?>">Yanıtla</button>
                  <div id="reply-form-<?php echo $comment['comment_id']; ?>" class="reply-form d-none">
                  <form method="POST">
                      <input type="hidden" name="parent_id" value="<?php echo $comment['comment_id']; ?>">
                      <textarea  class="form-control shadow-none" style="margin-top: 10px; margin-bottom: 20px;" name="comment" rows="2" required></textarea>
                  </form>
                  </div>
                  <?php renderComments($comments, $comment['comment_id']); ?>
                    </div>
              </div>
              <?php
                  }
                }
              }
              renderComments($comments);
              ?>
          </div>

          <div>
              <h3 class="mb-4">Yorum Yap</h3>
              <form method="POST">
                  <div class="row">
                      <div class="form-group col-md-12">
                          <textarea class="form-control shadow-none" name="comment" rows="7" required></textarea>
                      </div>
                  </div>
                  <button class="btn btn-primary" type="submit">PAYLAŞ</button>
              </form>
          </div>
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
  <script>
document.addEventListener('DOMContentLoaded', function() {
    const replyButtons = document.querySelectorAll('.reply-btn');

    replyButtons.forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            const replyForm = document.getElementById(`reply-form-${commentId}`);

            // Formu görünür yap
            replyForm.classList.remove('d-none');
        });
    });
});
</script>

  <!-- Main Script -->
  <script src="js/script.js"></script></body>
</html>