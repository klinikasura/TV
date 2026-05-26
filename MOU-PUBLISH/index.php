<?php
$conn = mysqli_connect("10.10.20.250", "root", "", "sikdraisyah");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

/* =========================
   PAGINATION SETTING
========================= */

$batas = 10;

$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
if ($halaman < 1) $halaman = 1;

$halaman_awal = ($halaman - 1) * $batas;

/* =========================
   SEARCH
========================= */

$where = "";

if (isset($_POST['cari'])) {
    $nama_pt = $_POST['nama_pt'];
    $where = "WHERE nama_pt LIKE '%$nama_pt%'";
}

/* =========================
   TOTAL DATA
========================= */

$total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM robot80_mou $where");
$total_data = mysqli_fetch_assoc($total_query)['total'];
$total_halaman = ceil($total_data / $batas);

/* =========================
   DATA QUERY
========================= */

$query = "SELECT * FROM robot80_mou
          $where
          ORDER BY id DESC
          LIMIT $halaman_awal, $batas";

$result = mysqli_query($conn, $query);

/* =========================
   DELETE
========================= */

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    $query = "DELETE FROM robot80_mou WHERE id='$id'";

    if (mysqli_query($conn, $query)) {
        echo "<script>
        alert('Data berhasil dihapus');
        window.location.href='index.php';
        </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

 <title>myROBOT-V80</title>
  <link href="http://10.10.20.250/dashboard/APPS-ROBOT/BUILDING APLIKASI/@API-GITHUB-V80/ROBOT-GITHUB/ROBOTV80.png" rel="icon" type="image/png" />
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

/* RESET */
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins', sans-serif;
}

/* BODY */
body{
    background: linear-gradient(to right, #eef2f7, #dbe9ff);
    padding:20px;
    color:#333;
}

/* CONTAINER */
.container{
    max-width:1400px;
    margin:auto;
    background:#fff;
    padding:25px;
    border-radius:20px;
    box-shadow:0 10px 30px rgba(0,0,0,0.1);
}

/* TITLE */
h1, h2{
    text-align:center;
    color:#0d6efd;
    margin-bottom:20px;
}

/* FORM */
form{
    margin-bottom:25px;
}

form table{
    width:100%;
}

form table td{
    padding:8px;
}

/* INPUT */
input, textarea{
    width:100%;
    padding:10px;
    border-radius:10px;
    border:1px solid #ccc;
    transition:0.3s;
}

input:focus, textarea:focus{
    border-color:#0d6efd;
    box-shadow:0 0 6px rgba(13,110,253,0.3);
    outline:none;
}

/* BUTTON */
input[type="submit"]{
    background:#0d6efd;
    color:white;
    border:none;
    cursor:pointer;
    font-weight:600;
}

input[type="submit"]:hover{
    background:#0b5ed7;
}

/* TABLE */
.data-table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
    border-radius:10px;
    overflow:hidden;
}

.data-table th{
    background:#0d6efd;
    color:white;
    padding:12px;
    text-align:left;
}

.data-table td{
    padding:10px;
    border-bottom:1px solid #eee;
}

.data-table tr:nth-child(even){
    background:#f7faff;
}

.data-table tr:hover{
    background:#eaf2ff;
}

/* LINK */
a{
    text-decoration:none;
    font-weight:600;
}

a[href*="edit"]{ color:green; }
a[href*="hapus"]{ color:red; }

/* PAGINATION */
.pagination{
    margin-top:20px;
    display:flex;
    justify-content:center;
    flex-wrap:wrap;
    gap:8px;
}

.pagination a{
    padding:8px 14px;
    background:#f1f1f1;
    border-radius:8px;
    color:#333;
    transition:0.3s;
}

.pagination a:hover{
    background:#0d6efd;
    color:white;
}

.pagination .active{
    background:#0d6efd;
    color:white;
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






/* RESPONSIVE */
@media(max-width:768px){
    .container{
        padding:15px;
    }

    .data-table th,
    .data-table td{
        font-size:12px;
    }
}

</style>

</head>

<body>

<div class="container">


<h2>E-MOU</h2>

<form method="post">
<table>
<tr>
<td>Nama PT</td>
<td><input type="text" name="nama_pt"></td>
</tr>
<tr>
<td></td>
<td><input type="submit" name="cari" value="Cari"></td>
</tr>
</table>
</form>

<table class="data-table">

<tr>
<th>No</th>
<th>ID</th>
<th>Nama PT</th>
<th>Alamat</th>
<th>Mulai</th>
<th>Habis</th>
<th>No MOU</th>
<th>PJ Klinik</th>
<th>PJ PT</th>
<th>Klinik</th>
<th>Aksi</th>
</tr>

<?php $no = $halaman_awal + 1; while($row = mysqli_fetch_assoc($result)) { ?>

<tr>
<td><?= $no++; ?></td>
<td><?= $row['id']; ?></td>
<td><?= $row['nama_pt']; ?></td>
<td><?= $row['alamat_pt']; ?></td>
<td><?= $row['mulai_mou']; ?></td>
<td><?= $row['habis_mou']; ?></td>
<td><?= $row['no_mou']; ?></td>
<td><?= $row['pj_klinik']; ?></td>
<td><?= $row['pj_pt']; ?></td>
<td><?= $row['nama_klinik']; ?></td>
<td>

</td>
</tr>

<?php } ?>

</table>

<!-- PAGINATION -->
<div class="pagination">

<?php if($halaman > 1){ ?>
<a href="?halaman=<?= $halaman-1; ?>">Prev</a>
<?php } ?>

<?php for($x=1; $x <= $total_halaman; $x++){ ?>
<a class="<?= ($x==$halaman)?'active':''; ?>" href="?halaman=<?= $x; ?>">
<?= $x; ?>
</a>
<?php } ?>

<?php if($halaman < $total_halaman){ ?>
<a href="?halaman=<?= $halaman+1; ?>">Next</a>
<?php } ?>

</div>

</div>


<p>   <p>&nbsp;</p>
<p>   <p>&nbsp;</p>
<p>   <p>&nbsp;</p>
<p>   <p>&nbsp;</p>

<p>   <p>&nbsp;</p>
<p>   <p>&nbsp;</p>
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
