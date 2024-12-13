<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = '34.159.84.150';
$user = 'root';
$password = 'YOUR_PASSWORD';
$database = 'blendhub';

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Bağlantı hatası: " . mysqli_connect_error());
}
echo "Bağlantı başarılı!";
?>