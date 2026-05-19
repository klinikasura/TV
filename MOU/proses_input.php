<?php
// koneksi ke database
$conn = mysqli_connect("10.10.20.250", "root", "", "sikdraisyah");

// cek koneksi
if (!$conn) {
  die("Koneksi gagal: " . mysqli_connect_error());
}

// ambil data dari form
$id = $_POST['id'];
$nama_pt = $_POST['nama_pt'];
$alamat_pt = $_POST['alamat_pt'];
$mulai_mou = $_POST['mulai_mou'];
$habis_mou = $_POST['habis_mou'];
$no_mou = $_POST['no_mou'];
$pj_klinik = $_POST['pj_klinik'];
$pj_pt = $_POST['pj_pt'];
$nama_klinik = $_POST['nama_klinik'];

// query insert data
$query = "INSERT INTO robot80_mou (id, nama_pt, alamat_pt, mulai_mou, habis_mou, no_mou, pj_klinik, pj_pt, nama_klinik)
VALUES ('$id', '$nama_pt', '$alamat_pt', '$mulai_mou', '$habis_mou', '$no_mou', '$pj_klinik', '$pj_pt', '$nama_klinik')";

// eksekusi query
if (mysqli_query($conn, $query)) {
  header("Location: index.php");
} else {
  echo "Error: " . mysqli_error($conn);
}

// tutup koneksi
mysqli_close($conn);
?>

