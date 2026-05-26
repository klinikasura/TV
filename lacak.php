<?php
include 'koneksi.php';

// ambil parameter filter
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date   = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// pagination
$limit = 10;
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// status tampil data
$showData = false;
$where = "WHERE 1=1";

// filter tanggal
if ($start_date != '' && $end_date != '') {
    $showData = true;
    $where .= " AND tgl_jurnal BETWEEN '$start_date' AND '$end_date'";
}

// init default
$totalRow = 0;
$totalPage = 0;
$query = null;

// kalau sudah filter baru ambil data
if ($showData) {

    // hitung total data
    $totalData = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM jurnal $where");
    $totalRow = mysqli_fetch_assoc($totalData)['total'];
    $totalPage = ceil($totalRow / $limit);

    // ambil data
    $query = mysqli_query($koneksi, "
        SELECT * FROM jurnal 
        $where 
        ORDER BY tgl_jurnal DESC 
        LIMIT $limit OFFSET $offset
    ");
}
?>

<!DOCTYPE html>
<html>
<head>
 <title>myROBOT-V80</title>
  <link href="http://10.10.20.250/dashboard/APPS-ROBOT/BUILDING APLIKASI/@API-GITHUB-V80/ROBOT-GITHUB/ROBOTV80.png" rel="icon" type="image/png" />
<meta name="viewport" content="width=device-width, initial-scale=1">


<style>
body{
    font-family: 'Segoe UI', sans-serif;
    background: #f4f6f9;
    margin: 20px;
}

h2{
    color: #333;
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


/* FORM */
form{
    background: white;
    padding: 15px;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    display: inline-block;
    margin-bottom: 20px;
}

input[type="date"]{
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 8px;
    margin-right: 10px;
}

button{
    padding: 8px 15px;
    background: #4f46e5;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}

button:hover{
    background: #3730a3;
}

/* TABLE */
table{
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

th{
    background: #4f46e5;
    color: white;
    padding: 12px;
}

td{
    padding: 10px;
    border-bottom: 1px solid #eee;
}

tr:hover{
    background: #f0f4ff;
}

/* EMPTY STATE */
.empty{
    text-align: center;
    color: #888;
    padding: 20px;
}

/* PAGINATION */
.pagination a{
    display: inline-block;
    padding: 6px 12px;
    margin: 5px;
    background: white;
    border-radius: 8px;
    text-decoration: none;
    color: #333;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.pagination a:hover{
    background: #4f46e5;
    color: white;
}
</style>

</head>
<body>

<h2>E-Lacak</h2>

<!-- FILTER -->
<form method="GET">
    Dari Tanggal:
    <input type="date" name="start_date" value="<?= $start_date ?>">

    Sampai:
    <input type="date" name="end_date" value="<?= $end_date ?>">

    <button type="submit">Filter</button>
</form>

<br>

<!-- TABLE -->
<table>
    <tr>
        <th>No Jurnal</th>
        <th>No Bukti</th>
        <th>Tanggal</th>
        <th>Jam</th>
        <th>Jenis</th>
        <th>Keterangan</th>
    </tr>

    <?php if ($showData && $totalRow > 0) { ?>
        <?php while ($row = mysqli_fetch_assoc($query)) { ?>
        <tr>
            <td><?= $row['no_jurnal'] ?></td>
            <td><?= $row['no_bukti'] ?></td>
            <td><?= $row['tgl_jurnal'] ?></td>
            <td><?= $row['jam_jurnal'] ?></td>
            <td><?= $row['jenis'] ?></td>
            <td><?= $row['keterangan'] ?></td>
        </tr>
        <?php } ?>
    <?php } else { ?>
        <tr>
            <td colspan="6" class="empty">
                Silakan pilih tanggal terlebih dahulu untuk menampilkan data
            </td>
        </tr>
    <?php } ?>

</table>

<br>

<!-- PAGINATION -->
<div class="pagination">
<?php if ($showData) { ?>

    <?php if ($page > 1) { ?>
        <a href="?page=<?= $page - 1 ?>&start_date=<?= $start_date ?>&end_date=<?= $end_date ?>">⬅ Prev</a>
    <?php } ?>

    <?php for ($i = 1; $i <= $totalPage; $i++) { ?>
        <a href="?page=<?= $i ?>&start_date=<?= $start_date ?>&end_date=<?= $end_date ?>">
            <?= $i ?>
        </a>
    <?php } ?>

    <?php if ($page < $totalPage) { ?>
        <a href="?page=<?= $page + 1 ?>&start_date=<?= $start_date ?>&end_date=<?= $end_date ?>">Next ➡</a>
    <?php } ?>

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
