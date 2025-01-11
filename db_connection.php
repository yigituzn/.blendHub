<?php
$host = 'localhost';
//$host = '34.159.78.122';
$user = 'root';
//user = 'user';
$password = '';
//$password = 'RU{1@=1|4nAGc1l~';
$database = 'blendhub';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}
?>