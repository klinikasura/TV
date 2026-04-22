<?php
// koneksi.php
$host = "10.10.20.250";
$user = "root";
$pass = "";
$db   = "sikdraisyah";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
