<?php
include 'db_connection.php';

// Kullanıcı artışı
$user_trend_query = "SELECT DATE(created_at) as date, COUNT(*) as count 
                     FROM users 
                     GROUP BY DATE(created_at) 
                     ORDER BY DATE(created_at)";
$user_trend_result = $conn->query($user_trend_query);
$user_trend_data = [];
while ($row = $user_trend_result->fetch_assoc()) {
    $user_trend_data[] = $row;
}

// Blog artışı
$blog_trend_query = "SELECT DATE(created_at) as date, COUNT(*) as count 
                     FROM posts 
                     GROUP BY DATE(created_at) 
                     ORDER BY DATE(created_at)";
$blog_trend_result = $conn->query($blog_trend_query);
$blog_trend_data = [];
while ($row = $blog_trend_result->fetch_assoc()) {
    $blog_trend_data[] = $row;
}

// En popüler kategoriler
$category_query = "SELECT categories.name, COUNT(postcategories.post_id) as count 
                   FROM categories 
                   LEFT JOIN postcategories ON categories.category_id = postcategories.category_id 
                   GROUP BY categories.name 
                   ORDER BY count DESC 
                   LIMIT 5";
$category_result = $conn->query($category_query);
$categories_data = [];
while ($row = $category_result->fetch_assoc()) {
    $categories_data[] = $row;
}

// Kategorilere göre blog sayısı
$blog_category_query = "SELECT categories.name, COUNT(postcategories.post_id) as count 
                        FROM categories 
                        LEFT JOIN postcategories ON categories.category_id = postcategories.category_id 
                        GROUP BY categories.name 
                        ORDER BY count DESC";
$blog_category_result = $conn->query($blog_category_query);
$blog_category_data = [];
while ($row = $blog_category_result->fetch_assoc()) {
    $blog_category_data[] = $row;
}

// JSON olarak döndür
echo json_encode([
    'user_trend' => $user_trend_data,
    'blog_trend' => $blog_trend_data,
    'popular_categories' => $categories_data,
    'blog_categories' => $blog_category_data,
]);
$conn->close();
?>
