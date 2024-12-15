<?php
//$host = 'localhost';
$host = '34.159.78.122';
$user = 'root';
$password = '';
//$password = 'nV~]<)euXOV6iKzy';
$database = 'blendhub';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}
?>