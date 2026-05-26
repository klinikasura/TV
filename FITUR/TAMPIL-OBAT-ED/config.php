<?php
$host = '10.10.20.250';
$db_user = 'root';
$db_pass = ''; // Sesuaikan password MySQL Anda
$db_name = 'sikdraisyah';

$mysqli = new mysqli($host, $db_user, $db_pass, $db_name);

if ($mysqli->connect_error) {
    die("Koneksi gagal: " . $mysqli->connect_error);
}
?>
