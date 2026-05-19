<?php
include 'koneksi.php';

$no_rawat = $_GET['no_rawat'];

$sql = "
SELECT 
    p.nm_pasien,
    b.no_rawat,
    b.tgl_byr,
    b.nm_perawatan,
    b.biaya
FROM billing b
INNER JOIN reg_periksa r 
    ON b.no_rawat = r.no_rawat
INNER JOIN pasien p 
    ON r.no_rkm_medis = p.no_rkm_medis
WHERE b.no_rawat = '$no_rawat'
";

$query = mysqli_query($koneksi,$sql);

if(!$query){
    die('Query Error : '.mysqli_error($koneksi));
}

$pasien = mysqli_fetch_array($query);

if(!$pasien){
    die('Data billing tidak ditemukan');
}

mysqli_data_seek($query,0);

$total = 0;
?>

<!DOCTYPE html>
<html>
<head>
 <title>myROBOT-V80</title>
  <link href="http://10.10.20.250/dashboard/APPS-ROBOT/BUILDING APLIKASI/@API-GITHUB-V80/ROBOT-GITHUB/ROBOTV80.png" rel="icon" type="image/png" />
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>

body{
    font-family: Courier New;
    width: 300px;
    font-size:12px;
}

table{
    width:100%;
    border-collapse:collapse;
}

td{
    padding:4px;
}

.judul{
    text-align:center;
    font-size:18px;
    font-weight:bold;
}

.garis{
    border-top:1px dashed black;
    margin:5px 0;
}

</style>

</head>

<body onload="window.print()">

<div class="judul">
    NOTA BILLING
</div>

<div class="garis"></div>

<table>

<tr>
    <td>No Rawat</td>
    <td>:</td>
    <td><?php echo $pasien['no_rawat']; ?></td>
</tr>

<tr>
    <td>Pasien</td>
    <td>:</td>
    <td><?php echo $pasien['nm_pasien']; ?></td>
</tr>

<tr>
    <td>Tanggal</td>
    <td>:</td>
    <td><?php echo $pasien['tgl_byr']; ?></td>
</tr>

</table>

<div class="garis"></div>

<table>

<?php
while($d = mysqli_fetch_array($query)){

$total += $d['biaya'];
?>

<tr>
    <td><?php echo $d['nm_perawatan']; ?></td>
    <td align="right">
        <?php echo number_format($d['biaya']); ?>
    </td>
</tr>

<?php
}
?>

</table>

<div class="garis"></div>

<table>

<tr>
    <td><b>TOTAL</b></td>
    <td align="right">
        <b>Rp <?php echo number_format($total); ?></b>
    </td>
</tr>

</table>

<div class="garis"></div>

<center>
Terima Kasih
</center>

</body>
</html>
