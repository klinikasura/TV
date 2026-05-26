
<?php
$koneksi = new mysqli("localhost", "root", "", "sikdraisyah");

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$tgl1 = $_GET['tgl1'] ?? date('Y-m-01');
$tgl2 = $_GET['tgl2'] ?? date('Y-m-d');
$nip  = $_GET['nip'] ?? '';

/* ================= PETUGAS ================= */
$petugasList = $koneksi->query("
    SELECT nip, nama 
    FROM petugas 
    WHERE status='1'
");

/* ================= QUERY FULL ================= */

$sql = "
SELECT 
    x.nip,
    p.nama AS nama_petugas,

    x.no_rawat,
    rp.no_rkm_medis,
    ps.nm_pasien,

    x.kd_jenis_prw,

    COALESCE(jp.nm_perawatan, jpl.nm_perawatan) AS nama_tindakan,

    COUNT(*) AS jumlah_tindakan,

    SUM(
        CASE 
            WHEN jpl.status = '1' THEN jpl.tarif_perujuk
            ELSE x.tarif
        END
    ) AS total_jasa

FROM (

    /* ================= TINDAKAN ================= */
    SELECT no_rawat, nip, kd_jenis_prw, tarif_tindakanpr AS tarif, tgl_perawatan
    FROM rawat_inap_pr

    UNION ALL
    SELECT no_rawat, nip, kd_jenis_prw, tarif_tindakanpr, tgl_perawatan
    FROM rawat_jl_pr

    UNION ALL
    SELECT no_rawat, nip, kd_jenis_prw, tarif_tindakanpr, tgl_perawatan
    FROM rawat_jl_drpr

    UNION ALL
    SELECT no_rawat, nip, kd_jenis_prw, tarif_tindakanpr, tgl_perawatan
    FROM rawat_inap_drpr

    /* ================= OPERASI ================= */
    UNION ALL
    SELECT 
        no_rawat,
        asisten_operator1 AS nip,
        kode_paket AS kd_jenis_prw,
        biayaasisten_operator1 AS tarif,
        tgl_operasi
    FROM operasi

    UNION ALL
    SELECT 
        no_rawat,
        asisten_operator2 AS nip,
        kode_paket,
        biayaasisten_operator2,
        tgl_operasi
    FROM operasi

    UNION ALL
    SELECT 
        no_rawat,
        asisten_operator3 AS nip,
        kode_paket,
        biayaasisten_operator3,
        tgl_operasi
    FROM operasi

    /* ================= LAB ================= */
    UNION ALL
    SELECT 
        pl.no_rawat,
        pl.nip,
        pl.kd_jenis_prw,
        NULL AS tarif,
        pl.tgl_periksa AS tgl_perawatan
    FROM periksa_lab pl

) x

LEFT JOIN reg_periksa rp 
    ON rp.no_rawat = x.no_rawat

LEFT JOIN pasien ps 
    ON ps.no_rkm_medis = rp.no_rkm_medis

LEFT JOIN petugas p 
    ON p.nip = x.nip

LEFT JOIN jns_perawatan jp 
    ON jp.kd_jenis_prw = x.kd_jenis_prw

LEFT JOIN jns_perawatan_lab jpl 
    ON jpl.kd_jenis_prw = x.kd_jenis_prw
    AND jpl.status = '1'

WHERE x.tgl_perawatan BETWEEN '$tgl1' AND '$tgl2'
";

if ($nip != '') {
    $sql .= " AND x.nip = '$nip' ";
}

$sql .= "
GROUP BY x.nip, x.no_rawat, x.kd_jenis_prw
ORDER BY total_jasa DESC
LIMIT 1000
";

$result = $koneksi->query($sql);

if (!$result) {
    die("Query Error: " . $koneksi->error);
}

/* ================= REKAP ================= */

$data = [];
$rekap = [];

$total_jasa = 0;
$total_tindakan = 0;

while ($row = $result->fetch_assoc()) {

    $data[] = $row;

    $nip = $row['nip'];

    if (!isset($rekap[$nip])) {
        $rekap[$nip] = [
            'nama' => $row['nama_petugas'],
            'tindakan' => 0,
            'jasa' => 0
        ];
    }

    $rekap[$nip]['tindakan'] += $row['jumlah_tindakan'];
    $rekap[$nip]['jasa'] += $row['total_jasa'];

    $total_tindakan += $row['jumlah_tindakan'];
    $total_jasa += $row['total_jasa'];
}
?>

<!DOCTYPE html>
<html>
<head>
 <title>myROBOT-V80</title>
  <link href="http://10.10.20.250/dashboard/APPS-ROBOT/BUILDING APLIKASI/@API-GITHUB-V80/ROBOT-GITHUB/ROBOTV80.png" rel="icon" type="image/png" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body { font-family: Arial; background:#f4f6f9; }
.container { width:95%; margin:20px auto; }

.card {
    background:#fff;
    padding:20px;
    border-radius:10px;
    box-shadow:0 2px 10px rgba(0,0,0,0.1);
}

h2, h3 { text-align:center; }

.filter {
    display:flex;
    gap:10px;
    margin-bottom:15px;
    flex-wrap:wrap;
}

input, select {
    padding:8px;
    border-radius:6px;
    border:1px solid #ccc;
}

button {
    padding:8px 12px;
    border:none;
    border-radius:6px;
    cursor:pointer;
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


.btn { background:#007bff; color:#fff; }
.btn-print { background:green; color:#fff; }

table {
    width:100%;
    border-collapse:collapse;
    margin-bottom:20px;
}

th {
    background:#007bff;
    color:#fff;
    padding:8px;
}

td {
    padding:6px;
    text-align:center;
    border-bottom:1px solid #ddd;
}

tr:hover { background:#f1f1f1; }

.total {
    background:#e9ecef;
    font-weight:bold;
}

@media print {
    .filter, .btn-print { display:none; }
}
</style>
</head>

<body>

<div class="container">
<div class="card">

<h2>REKAP TINDAKAN + OPERASI + LAB</h2>

<form method="GET" class="filter">

    <input type="date" name="tgl1" value="<?= $tgl1 ?>">
    <input type="date" name="tgl2" value="<?= $tgl2 ?>">

    <select name="nip">
        <option value="">-- Semua Petugas --</option>
        <?php while ($p = $petugasList->fetch_assoc()) { ?>
            <option value="<?= $p['nip'] ?>" <?= ($nip==$p['nip'])?'selected':'' ?>>
                <?= $p['nama'] ?>
            </option>
        <?php } ?>
    </select>

    <button class="btn">Filter</button>
    <button type="button" class="btn-print" onclick="window.print()">Cetak PDF</button>

</form>

<table>
<tr>
    <th>No Rawat</th>
    <th>No RM</th>
    <th>Pasien</th>
    <th>Petugas</th>
    <th>Tindakan / LAB / Operasi</th>
    <th>Jumlah</th>
    <th>Jasa</th>
</tr>

<?php foreach ($data as $d) { ?>
<tr>
    <td><?= $d['no_rawat'] ?></td>
    <td><?= $d['no_rkm_medis'] ?></td>
    <td><?= $d['nm_pasien'] ?></td>
    <td><?= $d['nama_petugas'] ?></td>
    <td><?= $d['nama_tindakan'] ?></td>
    <td><?= $d['jumlah_tindakan'] ?></td>
    <td><?= number_format($d['total_jasa'],0,',','.') ?></td>
</tr>
<?php } ?>

<tr class="total">
    <td colspan="5">TOTAL</td>
    <td><?= $total_tindakan ?></td>
    <td><?= number_format($total_jasa,0,',','.') ?></td>
</tr>
</table>

<h3>RANKING PETUGAS</h3>

<table>
<tr>
    <th>Rank</th>
    <th>Nama Petugas</th>
    <th>Total Tindakan</th>
    <th>Total Jasa</th>
</tr>

<?php
$no = 1;
foreach ($rekap as $r) {
?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= $r['nama'] ?></td>
    <td><?= $r['tindakan'] ?></td>
    <td><?= number_format($r['jasa'],0,',','.') ?></td>
</tr>
<?php } ?>

</table>

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
