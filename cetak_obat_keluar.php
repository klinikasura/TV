<?php
include "koneksi2.php";

$where="";

if(!empty($_GET['tgl1']) && !empty($_GET['tgl2'])){

    $tgl1=$_GET['tgl1'];
    $tgl2=$_GET['tgl2'];

    $where.=" AND DATE(tanggal)
              BETWEEN '$tgl1'
              AND '$tgl2'";
}

if(!empty($_GET['bulan'])){

    $bulan=$_GET['bulan'];

    $where.=" AND DATE_FORMAT(tanggal,'%Y-%m')
              ='$bulan'";
}

$q=mysqli_query($koneksi,"
SELECT *
FROM robotv80_obat_keluar
WHERE 1=1
$where
ORDER BY tanggal DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Laporan Obat Keluar</title>

<style>

body{
    font-family:Arial;
}

h2{
    text-align:center;
}

table{
    width:100%;
    border-collapse:collapse;
}

table,th,td{
    border:1px solid black;
}

th,td{
    padding:8px;
    text-align:left;
}

</style>

</head>

<body onload="window.print()">

<h2>LAPORAN OBAT KELUAR</h2>

<table>

<tr>
    <th>No</th>
    <th>Tanggal</th>
    <th>Kode</th>
    <th>Nama Obat</th>
    <th>Jumlah</th>
    <th>Poli</th>
    <th>Petugas</th>
    <th>Keterangan</th>
</tr>

<?php
$no=1;

while($d=mysqli_fetch_assoc($q)){
?>

<tr>
    <td><?= $no++ ?></td>
    <td><?= $d['tanggal'] ?></td>
    <td><?= $d['kode_brng'] ?></td>
    <td><?= $d['nama_brng'] ?></td>
    <td><?= $d['jumlah_keluar'] ?></td>
    <td><?= $d['tujuan'] ?></td>
    <td><?= $d['petugas'] ?></td>
    <td><?= $d['keterangan'] ?></td>
</tr>

<?php } ?>

</table>

</body>
</html>
