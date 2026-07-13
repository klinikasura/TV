<?php

$koneksi = mysqli_connect(
    "10.10.20.250",
    "root",
    "",
    "sikdraisyah"
);

$tanggal     = $_POST['tanggal'];
$kode_obat   = $_POST['kode_obat'];
$nama_obat   = $_POST['nama_obat'];
$jumlah      = $_POST['jumlah'];
$satuan      = $_POST['satuan'];
$tujuan      = $_POST['tujuan'];
$keterangan  = $_POST['keterangan'];
$petugas     = $_POST['petugas'];

$sql = mysqli_query($koneksi,"
INSERT INTO robotv80_obat_keluar(
    tanggal,
    kode_obat,
    nama_obat,
    jumlah,
    satuan,
    tujuan,
    keterangan,
    petugas
) VALUES (
    '$tanggal',
    '$kode_obat',
    '$nama_obat',
    '$jumlah',
    '$satuan',
    '$tujuan',
    '$keterangan',
    '$petugas'
)
");

if($sql){
    echo "Data obat keluar berhasil disimpan.";
}else{
    echo "Gagal menyimpan data : ".mysqli_error($koneksi);
}

?>
