<?php
include 'koneksi.php';

/* ======================================================
   DETAIL MODE
====================================================== */
$detail_id = isset($_GET['id']) ? $_GET['id'] : "";

if ($detail_id != "") {

    $data = mysqli_fetch_assoc(mysqli_query($koneksi,"
        SELECT * FROM temporary_permintaan_lab WHERE no='$detail_id'
    "));
?>
<!DOCTYPE html>
<html>
<head>
<title>Detail Lab</title>

<style>
body{
    font-family: Arial;
    background:#f4f6f9;
    padding:20px;
}

.paper{
    background:white;
    padding:20px;
    border-radius:10px;
    width:900px;
    margin:auto;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
}

.header{
    text-align:center;
    border-bottom:2px solid #000;
    margin-bottom:15px;
}

table{
    width:100%;
    border-collapse:collapse;
}

td{
    padding:8px;
    border:1px solid #eee;
    font-size:13px;
}

.label{
    background:#f8fafc;
    font-weight:bold;
    width:200px;
}

.btn{
    padding:10px 15px;
    border:none;
    border-radius:8px;
    cursor:pointer;
    margin-bottom:10px;
}

.back{background:#e5e7eb;}
.print{background:#16a34a; color:white;}

@media print{
    .btn{display:none;}
    body{background:white;}
    .paper{box-shadow:none;}
}
</style>

</head>

<body>

<button class="btn back" onclick="window.location='lab.php'">⬅ Kembali</button>
<button class="btn print" onclick="window.print()">🖨 Print</button>

<div class="paper">

<div class="header">
    <h2>HASIL LABORATORIUM</h2>
    <p>No: <?= $data['no']; ?> | <?= $data['temp1']; ?></p>
    <p>Tanggal: <?= $data['temp2']; ?></p>
</div>

<table>

<?php
for ($i=1;$i<=37;$i++){
    echo "<tr>
        <td class='label'>TEMP$i</td>
        <td>".$data["temp$i"]."</td>
    </tr>";
}
?>

</table>

</div>

</body>
</html>

<?php
exit;
}

/* ======================================================
   LIST MODE
====================================================== */

// PAGINATION
$limit = 20;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$start = ($page - 1) * $limit;

// SEARCH SMART
$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : "";

// WHERE
$where = "WHERE 1=1";

if ($search != "") {

    if (is_numeric($search)) {

        // kalau 4 digit → tahun
        if (strlen($search) == 4) {
            $where .= " AND YEAR(temp2) = '$search'";
        } else {
            $where .= " AND (temp1 LIKE '%$search%' OR temp3 LIKE '%$search%')";
        }

    } else {
        $where .= " AND (temp1 LIKE '%$search%' OR temp3 LIKE '%$search%')";
    }
}

// TOTAL
$total_query = mysqli_query($koneksi,"
    SELECT COUNT(*) as total 
    FROM temporary_permintaan_lab 
    $where
");

$total_data = mysqli_fetch_assoc($total_query)['total'];
$total_page = ceil($total_data / $limit);

// DATA (TERBARU DI ATAS)
$query = "
    SELECT * FROM temporary_permintaan_lab
    $where
    ORDER BY no DESC
    LIMIT $start, $limit
";

$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html>
<head>
 <title>myROBOT-V80</title>
  <link href="http://10.10.20.250/dashboard/APPS-ROBOT/BUILDING APLIKASI/@API-GITHUB-V80/ROBOT-GITHUB/ROBOTV80.png" rel="icon" type="image/png" />
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
body{
    font-family: Arial;
    background:#eef2f7;
    padding:20px;
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




.container{
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#4f46e5;
    color:white;
    padding:10px;
}

td{
    padding:10px;
    border-bottom:1px solid #eee;
}

tr:hover{
    background:#f1f5ff;
    cursor:pointer;
}

input{
    padding:10px;
    border:1px solid #ddd;
    border-radius:8px;
    width:250px;
}

button{
    padding:10px 15px;
    background:#4f46e5;
    color:white;
    border:none;
    border-radius:8px;
    cursor:pointer;
}

/* pagination */
.pagination a{
    padding:8px 12px;
    margin:2px;
    background:#e5e7eb;
    text-decoration:none;
    border-radius:6px;
}

.pagination a.active{
    background:#4f46e5;
    color:white;
}
</style>

</head>

<body>

<div class="container">

<h2>E-LAB</h2>

<!-- SEARCH -->
<form method="GET">
    <input type="text" name="search"
           placeholder="Cari (Nama / Tahun 2026 / dll)"
           value="<?= $search ?>">

    <button>Cari</button>
</form>

<br>

<table>
<tr>
    <th>No</th>
    <th>Kode </th>
    <th>No. Rawat</th>
    <th>Tindakan</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>
<tr onclick="window.location='lab.php?id=<?= $row['no']; ?>'">
    <td><?= $row['no']; ?></td>
    <td><?= $row['temp1']; ?></td>
    <td><?= $row['temp2']; ?></td>
    <td><?= $row['temp3']; ?></td>
</tr>
<?php } ?>

</table>

<!-- PAGINATION -->
<div class="pagination">

<?php
$q = $search ? "&search=$search" : "";

// prev
if ($page > 1) {
    echo "<a href='?page=".($page-1).$q."'>Prev</a>";
}

// number
for ($i=1;$i<=$total_page;$i++){
    $active = ($i==$page) ? "active" : "";
    echo "<a class='$active' href='?page=$i$q'>$i</a>";
}

// next
if ($page < $total_page) {
    echo "<a href='?page=".($page+1).$q."'>Next</a>";
}
?>

</div>

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
