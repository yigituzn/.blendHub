<?php
$host = '34.159.84.150'; // Cloud SQL'in genel IP adresi
$user = 'root'; // MySQL kullanıcı adı
$password = ''; // Root şifresi
$database = 'blendhub'; // Veritabanı adı

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}
?>