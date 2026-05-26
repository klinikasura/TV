<?php
$conn = new mysqli("10.10.20.250", "root", "", "sikdraisyah");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// FILTER
$tgl1 = $_GET['tgl1'] ?? '';
$tgl2 = $_GET['tgl2'] ?? '';
$useFilter = (!empty($tgl1) && !empty($tgl2));

// PAGINATION
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$start = ($page - 1) * $limit;

// DEFAULT
$data = null;
$total = 0;
$totalPasien = 0;
$label = $dataChart = $labelTop = $dataTop = [];

if ($useFilter) {

    $tgl1 = $conn->real_escape_string($tgl1);
    $tgl2 = $conn->real_escape_string($tgl2);

    // =========================
    // TOTAL PASIEN (BADGE)
    // =========================
    $pasienQ = $conn->query("
        SELECT COUNT(DISTINCT r.no_rawat) as total
        FROM reg_periksa r
        JOIN diagnosa_pasien d ON r.no_rawat = d.no_rawat
        WHERE r.tgl_registrasi BETWEEN '$tgl1' AND '$tgl2'
    ");
    $totalPasien = $pasienQ->fetch_assoc()['total'];

    // =========================
    // TABLE DATA
    // =========================
    $data = $conn->query("
        SELECT 
            r.no_rawat,
            r.tgl_registrasi,
            d.kd_penyakit,
            d.status,
            d.prioritas
        FROM reg_periksa r
        JOIN diagnosa_pasien d ON r.no_rawat = d.no_rawat
        WHERE r.tgl_registrasi BETWEEN '$tgl1' AND '$tgl2'
        ORDER BY r.tgl_registrasi DESC
        LIMIT $start, $limit
    ");

    // COUNT
    $count = $conn->query("
        SELECT COUNT(*) as total
        FROM reg_periksa r
        JOIN diagnosa_pasien d ON r.no_rawat = d.no_rawat
        WHERE r.tgl_registrasi BETWEEN '$tgl1' AND '$tgl2'
    ");
    $total = $count->fetch_assoc()['total'];

    // =========================
    // GRAFIK STATUS
    // =========================
    $chart = $conn->query("
        SELECT d.status, COUNT(*) as jml
        FROM reg_periksa r
        JOIN diagnosa_pasien d ON r.no_rawat = d.no_rawat
        WHERE r.tgl_registrasi BETWEEN '$tgl1' AND '$tgl2'
        GROUP BY d.status
    ");

    while ($c = $chart->fetch_assoc()) {
        $label[] = $c['status'];
        $dataChart[] = $c['jml'];
    }

    // =========================
    // TOP DIAGNOSA
    // =========================
    $top = $conn->query("
        SELECT d.kd_penyakit, COUNT(*) as total
        FROM reg_periksa r
        JOIN diagnosa_pasien d ON r.no_rawat = d.no_rawat
        WHERE r.tgl_registrasi BETWEEN '$tgl1' AND '$tgl2'
        GROUP BY d.kd_penyakit
        ORDER BY total DESC
        LIMIT 10
    ");

    while ($t = $top->fetch_assoc()) {
        $labelTop[] = $t['kd_penyakit'];
        $dataTop[] = $t['total'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
 <title>myROBOT-V80</title>
  <link href="http://10.10.20.250/dashboard/APPS-ROBOT/BUILDING APLIKASI/@API-GITHUB-V80/ROBOT-GITHUB/ROBOTV80.png" rel="icon" type="image/png" />
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body{
    background:#f4f6f9;
    font-family: "Segoe UI", sans-serif;
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






.card{
    border-radius:12px;
    box-shadow:0 2px 10px rgba(0,0,0,0.08);
    border:none;
}

.small-box{
    border-radius:12px;
    padding:15px;
    color:white;
}

.bg-blue{background:#007bff;}
.bg-green{background:#28a745;}
.bg-orange{background:#fd7e14;}

.table thead{
    background:#343a40;
    color:white;
}

h3{
    font-weight:600;
}
</style>
</head>

<body>

<div class="container mt-4">

<!-- HEADER -->
<div class="card p-4 mb-3">
    <h3>E-Diagnosa</h3>

    <form method="GET" class="row g-2 mt-2">
        <div class="col-md-4">
            <input type="date" name="tgl1" class="form-control" value="<?= htmlspecialchars($tgl1) ?>">
        </div>

        <div class="col-md-4">
            <input type="date" name="tgl2" class="form-control" value="<?= htmlspecialchars($tgl2) ?>">
        </div>

        <div class="col-md-4">
            <button class="btn btn-primary w-100">Filter</button>
        </div>
    </form>
</div>

<?php if ($useFilter): ?>

<!-- BADGE INFO -->
<div class="row mb-3">

    <div class="col-md-4">
        <div class="small-box bg-blue">
            <h5>Total Pasien</h5>
            <h2><?= $totalPasien ?></h2>
        </div>
    </div>

    <div class="col-md-4">
        <div class="small-box bg-green">
            <h5>Total Data Diagnosa</h5>
            <h2><?= $total ?></h2>
        </div>
    </div>

    <div class="col-md-4">
        <div class="small-box bg-orange">
            <h5>Periode</h5>
            <h6><?= $tgl1 ?> s/d <?= $tgl2 ?></h6>
        </div>
    </div>

</div>

<!-- TABLE -->
<div class="card p-3 mb-3">

<table class="table table-hover">
<thead>
<tr>
    <th>No Rawat</th>
    <th>Tanggal</th>
    <th>Kode Penyakit</th>
    <th>Status</th>
    <th>Prioritas</th>
</tr>
</thead>

<tbody>
<?php while ($row = $data->fetch_assoc()): ?>
<tr>
    <td><?= $row['no_rawat'] ?></td>
    <td><?= $row['tgl_registrasi'] ?></td>
    <td><?= $row['kd_penyakit'] ?></td>
    <td><?= $row['status'] ?></td>
    <td><?= $row['prioritas'] ?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

</div>

<!-- GRAFIK -->
<div class="row">

<div class="col-md-6">
    <div class="card p-3">
        <h5>Grafik Status Diagnosa</h5>
        <canvas id="chartStatus"></canvas>
    </div>
</div>

<div class="col-md-6">
    <div class="card p-3">
        <h5>Top 10 Diagnosa</h5>
        <canvas id="chartTop"></canvas>
    </div>
</div>

</div>

<script>
new Chart(document.getElementById('chartStatus'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($label) ?>,
        datasets: [{
            label: 'Status',
            data: <?= json_encode($dataChart) ?>
        }]
    }
});

new Chart(document.getElementById('chartTop'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($labelTop) ?>,
        datasets: [{
            label: 'Jumlah',
            data: <?= json_encode($dataTop) ?>
        }]
    }
});
</script>

<!-- PAGINATION -->
<?php
$totalPage = ceil($total / $limit);
?>

<nav class="mt-3">
<ul class="pagination">

<?php if ($page > 1): ?>
<li class="page-item">
<a class="page-link" href="?tgl1=<?= $tgl1 ?>&tgl2=<?= $tgl2 ?>&page=<?= $page-1 ?>">Prev</a>
</li>
<?php endif; ?>

<?php if ($page < $totalPage): ?>
<li class="page-item">
<a class="page-link" href="?tgl1=<?= $tgl1 ?>&tgl2=<?= $tgl2 ?>&page=<?= $page+1 ?>">Next</a>
</li>
<?php endif; ?>

</ul>
</nav>

<?php endif; ?>

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
