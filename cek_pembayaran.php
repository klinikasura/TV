<?php
// Konfigurasi koneksi database
$servername = "10.10.20.250";
$username   = "root";
$password   = "";
$dbname     = "sikdraisyah";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Hitung jumlah nota yang belum dibayar (contoh: tabel nota_jalan)
$sql = "
    SELECT COUNT(*) AS jml
    FROM nota_jalan
    WHERE status_bayar = 'Belum Bayar'";

$result = $conn->query($sql);
$jml    = $result->fetch_assoc()['jml'];

$conn->close();

// Output 1 jika ada data belum dibayar, 0 jika tidak
echo ($jml > 0) ? '1' : '0';
?>

