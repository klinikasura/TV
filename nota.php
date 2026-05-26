<?php
include 'koneksi.php';

$nama = isset($_GET['nama']) ? $_GET['nama'] : '';
$tgl1 = isset($_GET['tgl1']) ? $_GET['tgl1'] : '';
$tgl2 = isset($_GET['tgl2']) ? $_GET['tgl2'] : '';

$query = "";
$data  = null;

if($nama != '' || ($tgl1 != '' && $tgl2 != '')){

    $query = "
    SELECT 
        b.no_rawat,
        p.nm_pasien,
        b.tgl_byr,
        SUM(b.biaya) as total_biaya
    FROM billing b
    INNER JOIN reg_periksa r ON b.no_rawat = r.no_rawat
    INNER JOIN pasien p ON r.no_rkm_medis = p.no_rkm_medis
    WHERE 1=1
    ";

    if($nama != ''){
        $query .= " AND p.nm_pasien LIKE '%$nama%'";
    }

    if($tgl1 != '' && $tgl2 != ''){
        $query .= " AND b.tgl_byr BETWEEN '$tgl1' AND '$tgl2'";
    }

    $query .= "
    GROUP BY b.no_rawat
    ORDER BY b.tgl_byr DESC
    LIMIT 100
    ";

    $data = mysqli_query($koneksi,$query);

    if(!$data){
        die(mysqli_error($koneksi));
    }
}
?>

<!DOCTYPE html>
<html>
<head>

<title>myROBOT-V80</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="http://10.10.20.250/dashboard/APPS-ROBOT/BUILDING%20APLIKASI/@API-GITHUB-V80/ROBOT-GITHUB/ROBOTV80.png" rel="icon" type="image/png"/>

<style>

*{
    box-sizing:border-box;
}

body{
    font-family:Arial;
    margin:0;
    padding:10px;
    background:#f1f5f9;
}

.container{
    background:white;
    padding:15px;
    border-radius:10px;
    box-shadow:0 2px 10px rgba(0,0,0,0.1);
}

h2{
    margin-top:0;
    font-size:22px;
    text-align:center;
    color:#0f172a;
}

.form-group{
    margin-bottom:12px;
}

label{
    display:block;
    margin-bottom:5px;
    font-size:14px;
    font-weight:bold;
}

input{
    width:100%;
    padding:10px;
    border:1px solid #ccc;
    border-radius:8px;
    font-size:14px;
}

button{
    width:100%;
    padding:12px;
    background:#2563eb;
    color:white;
    border:none;
    border-radius:8px;
    font-size:16px;
    cursor:pointer;
}

button:hover{
    background:#1d4ed8;
}

.table-responsive{
    overflow-x:auto;
    margin-top:15px;
}

table{
    width:100%;
    border-collapse:collapse;
    min-width:700px;
}

table th{
    background:#2563eb;
    color:white;
    padding:10px;
    font-size:14px;
}

table td{
    border:1px solid #ddd;
    padding:10px;
    font-size:13px;
}

table tr:nth-child(even){
    background:#f8fafc;
}

.kanan{
    text-align:right;
}

.btn-cetak{
    display:inline-block;
    padding:8px 10px;
    background:#16a34a;
    color:white;
    text-decoration:none;
    border-radius:6px;
    font-size:12px;
}

.btn-cetak:hover{
    background:#15803d;
}

.kosong{
    margin-top:20px;
    text-align:center;
    color:#64748b;
}

@media(max-width:600px){

    h2{
        font-size:18px;
    }

    table th,
    table td{
        font-size:12px;
        padding:8px;
    }

    .btn-cetak{
        font-size:11px;
        padding:6px 8px;
    }

}

</style>
<style>
.btn-link {
    display: inline-block;
    padding: 10px 15px;
    background: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 5px;
}
/* BOTTOM NAV */
.bottom-nav {
    position: fixed;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    max-width: 420px;
    width: 100%;
    background: #fff;
    display: flex;
    justify-content: space-around;
    padding: 10px 0;
    border-top-left-radius: 25px;
    border-top-right-radius: 25px;
    box-shadow: 0 -5px 20px rgba(0,0,0,0.1);
}
.nav-item {
    font-size: 12px;
    text-align: center;
}
.home-btn {
    background: #4e8cff;
    width: 55px;
    height: 55px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    margin-top: -30px;
    font-size: 22px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

/* RESPONSIVE */
@media (max-width: 360px) {
    .menu-item { width: 60px; height: 60px; }
    .send-item img { width: 50px; height: 50px; }
    .card { margin: -30px 10px 20px; padding: 15px; }
}





.btn-link:hover {
    background: #0056b3;
}
</style>



</head>

<body>

<div class="container">

<h2>E-Billing</h2>

<form method="GET">

    <div class="form-group">
        <label>Nama Pasien</label>
        <input type="text" name="nama" value="<?php echo $nama; ?>" placeholder="Cari nama pasien">
    </div>

    <div class="form-group">
        <label>Tanggal Awal</label>
        <input type="date" name="tgl1" value="<?php echo $tgl1; ?>">
    </div>

    <div class="form-group">
        <label>Tanggal Akhir</label>
        <input type="date" name="tgl2" value="<?php echo $tgl2; ?>">
    </div>

    <button type="submit">Cari Billing Pasien</button>
<p>

<button type="button" onclick="window.open('http://10.10.20.250/dashboard/APPS-ROBOT/TV/lab.php', '_blank')">
  E-LAB (Cari Hasil LAB)
</button>


</form>





<?php if($data != null){ ?>

<div class="table-responsive">

<table>

<tr>
    <th>No</th>
    <th>No Rawat</th>
    <th>Nama Pasien</th>
    <th>Tanggal</th>
    <th>Total Billing</th>
    <th>Aksi</th>
</tr>

<?php
$no=1;

while($d=mysqli_fetch_array($data)){
?>

<tr>

<td><?php echo $no++; ?></td>

<td><?php echo $d['no_rawat']; ?></td>

<td><?php echo $d['nm_pasien']; ?></td>

<td><?php echo $d['tgl_byr']; ?></td>

<td class="kanan">
    Rp <?php echo number_format($d['total_biaya']); ?>
</td>

<td>
    <a class="btn-cetak"
    href="nota_billing.php?no_rawat=<?php echo $d['no_rawat']; ?>"
    target="_blank">
    Cetak
    </a>
</td>

</tr>

<?php } ?>

</table>

</div>

<?php } ?>

<?php
if($data == null){
?>

<div class="kosong">
    Silakan cari data pasien terlebih dahulu
</div>

<?php } ?>

</div>

<p>   <p>&nbsp;</p>
<p>   <p>&nbsp;</p>
<p>   <p>&nbsp;</p>
<p>   <p>&nbsp;</p>

<p>   <p>&nbsp;</p>
<p>   <p>&nbsp;</p>



<!-- BOTTOM NAV -->
<div class="bottom-nav">
   <a href="http://10.10.20.250/dashboard/ROBOT-DASHBOARD/"  class="nav-item"><img src="http://10.10.20.250/dashboard/APPS-ROBOT/ANDROID-NEW/BERANDA2.png" alt="" class="profile-pic" height="70" width="50" ><br> BERANDA </a>

 <a href="http://10.10.20.250/dashboard/APPS-ROBOT/GITHUB/SOAP/ROBOT-V80/rawat_jalan/manage?t=d9d3d5af7281" id="vib1" class="nav-item">
  <img src="http://10.10.20.250/dashboard/APPS-ROBOT/GITHUB/SOAP.png"
       alt=""
       id="goyang"
       class="profile-pic"
       height="70"
       width="50">
  <br> SOAP
</a>

<a href="http://10.10.20.250/dashboard/APPS-ROBOT/GITHUB/index-dashboard2.php" class="home-btn">
    <img src="http://10.10.20.250/dashboard/APPS-ROBOT/ANDROID-NEW/APPS2.png"
         alt=""
         class="profile-pic bounce"
         height="100"
         width="100">
</a>

<img src="https://i.imgur.com/gYHDr9S.gif"
     alt=""
     class="profile-pic"
     height="18"
     width="18">

<style>
.bounce {
    animation: loncat 0.8s infinite;
    display: inline-block;
}

@keyframes loncat {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-20px);
    }
}
</style>




<a href="<?= $user['gaji']; ?>" class="nav-item" id="vib1">
  <img src="http://10.10.20.250/dashboard/APPS-ROBOT/ANDROID-NEW/GAJI.png"
       alt=""
       id="goyang"
       class="profile-pic"
       height="70"
       width="50">
  <br> SLIP GAJI
</a>

   


     <a href="http://10.10.20.250/dashboard/APPS-ROBOT/ANDROID-NEW/logout2.php" class="nav-item"><img src="http://10.10.20.250/dashboard/APPS-ROBOT/ANDROID-NEW/LOGOUT.png" alt="" class="profile-pic" height="70" width="50" ><br> LOGOUT</a>
</div>


</body>
</html>
