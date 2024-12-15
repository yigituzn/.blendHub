<?php
$host = '34.159.84.150';
$user = 'root';
$password = '';
$database = 'blendhub';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}
?>