<?php
include "koneksi2.php";

$id=$_GET['id'];

$data=mysqli_fetch_assoc(
mysqli_query($koneksi,"
SELECT *
FROM robotv80_obat_keluar
WHERE id='$id'
")
);

if(isset($_POST['update'])){

    $jumlah_keluar=$_POST['jumlah_keluar'];
    $tujuan=$_POST['tujuan'];
    $petugas=$_POST['petugas'];
    $keterangan=$_POST['keterangan'];

    mysqli_query($koneksi,"
    UPDATE robotv80_obat_keluar
    SET
        jumlah_keluar='$jumlah_keluar',
        tujuan='$tujuan',
        petugas='$petugas',
        keterangan='$keterangan'
    WHERE id='$id'
    ");

    echo "
    <script>
    alert('Data berhasil diupdate');
    location='cek-so.php';
    </script>
    ";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Obat Keluar</title>

<style>

body{
    font-family:Arial;
    background:#f4f6f9;
}

.card{
    width:90%;
    margin:auto;
    margin-top:20px;
    background:white;
    padding:20px;
    border-radius:15px;
}

input,
select,
textarea{
    width:100%;
    padding:12px;
    border:1px solid #ccc;
    border-radius:10px;
    margin-bottom:15px;
}

button{
    background:#0d6efd;
    color:white;
    border:none;
    padding:12px 20px;
    border-radius:10px;
}

</style>

</head>

<body>

<div class="card">

<h2>Edit Obat Keluar</h2>

<form method="POST">

<label>Nama Obat</label>
<input type="text"
value="<?=$data['nama_brng']?>"
readonly>

<label>Jumlah Keluar</label>
<input type="number"
name="jumlah_keluar"
value="<?=$data['jumlah_keluar']?>">

<label>Poli</label>
<input type="text"
name="tujuan"
value="<?=$data['tujuan']?>">

<label>Petugas</label>
<input type="text"
name="petugas"
value="<?=$data['petugas']?>">

<label>Keterangan</label>
<textarea name="keterangan"><?=$data['keterangan']?></textarea>

<button name="update">
Update Data
</button>

</form>

</div>

</body>
</html>
