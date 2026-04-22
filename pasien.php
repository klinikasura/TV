<?php
require_once('conf/conf.php');

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
date_default_timezone_set("Asia/Bangkok");

$tanggal = mktime(date("m"), date("d"), date("Y"));
$jam     = date("H:i");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Aplikasi RS. Asura</title>
    <link href="http://10.10.20.250/dashboard/download.jpeg" rel="icon" type="image/png" />
    <meta http-equiv="refresh" content="2;url=pasien.php">
    <style>
        table {border-collapse:collapse;width:100%;font-size:0.9em;}
        th, td {border:2px solid #ff69b4;padding:6px 10px;text-align:left;}
        th {background:#000;color:#fff;}
        th:nth-child(1), td:nth-child(1) {width:5%;}
        th:nth-child(2), td:nth-child(2) {width:12%;}
        th:nth-child(3), td:nth-child(3) {width:20%;}
        th:nth-child(4), td:nth-child(4) {width:18%;}
        th:nth-child(5), td:nth-child(5) {width:15%;}
        th:nth-child(6), td:nth-child(6) {width:10%;}
        th:nth-child(7), td:nth-child(7) {width:10%;}
        th:nth-child(8), td:nth-child(8) {width:10%;}
    </style>


<style>
        .running-text {
            width: 100%;
            height: 40px;
            line-height: 40px;
            background: green;
            color: white;
            font-size: 20px;
            overflow: hidden;
            white-space: nowrap;
            padding: 0 10px;
            box-sizing: border-box;
        }
        .running-text span {
            display: inline-block;
        }
    </style>





<style>
    .running-text {
        width: 100%;
        height: 40px;
        line-height: 40px;
        background: blue;
        color: white;
        font-size: 20px;
        overflow: hidden;
        white-space: nowrap;
        padding: 0 10px;
        text-align: center;
        box-sizing: border-box;
        font-weight: bold;          /* ← tambahkan ini */
    }
    .running-text span {
        display: inline-block;
    }
</style>




<style>
.berkedip2 {
    animation: berkedip2 1s infinite;
    font-weight: bold;
}
@keyframes berkedip2 {
    0% { color: black; }
    50% { color: white; }
    100% { color: black; }
}
.info-harian{
    font-size:15px;      /* ukuran tulisan utama */
   
}

.info-harian b{
    font-size:15px;      /* angka dibuat sedikit lebih besar */
background-color: #add8e6; /* biru muda */
  padding: 10px;
  border-radius: 8px;

}

.berkedip2{
    font-size:18px;      /* angka kedip paling besar */
    color:black;
    animation:kedip2 1s infinite;
}

@keyframes kedip2{
    0%{opacity:1;}
    50%{opacity:0.3;}
    100%{opacity:1;}
}


/* teks info */
.info-harian{
    font-size:15px;
}

/* angka normal */
.info-harian b{
    font-size:15px;
background-color: #add8e6; /* biru muda */
  padding: 10px;
  border-radius: 8px;

}

/* ANGKA BERKEDIP SEPERTI DETIK JAM */
.berkedip2{
    font-size:18px;
    color:black;
    font-weight:bold;

    /* rahasia efek clock */
    animation:clockBlink 1s steps(1,end) infinite;

    /* glow biar kelihatan hidup */
    text-shadow:;
}

/* ON - OFF tiap 1 detik (bukan fade) */
@keyframes clockBlink{
    0%{opacity:1;}
    49%{opacity:1;}
    50%{opacity:0;}
    100%{opacity:0;}
}


.live-text {
    color: red;
    font-weight: bold;
    animation: blink 1s infinite;
}

@keyframes blink {
    0%   { opacity: 1; }
    50%  { opacity: 0; }
    100% { opacity: 1; }
}
.label-biru {
  color: #0b3d91; /* biru muda */
  font-weight: 600;
}

</style>

</head>
<body>






<!-- ★ JUMLAH PASIEN PER POLI ★ -->




<?php
$tgl = date("Y-m-d");

/* ================= DAFTAR HARI INI ================= */
$q1 = bukaquery("SELECT COUNT(no_rawat) AS jml FROM reg_periksa WHERE tgl_registrasi='$tgl'");
$d1 = mysqli_fetch_assoc($q1);
$daftar = $d1['jml'];

/* ================= SUDAH BAYAR ================= */
$q2 = bukaquery("
SELECT COUNT(nj.no_rawat) AS jml
FROM nota_jalan nj
INNER JOIN reg_periksa rp ON nj.no_rawat = rp.no_rawat
WHERE nj.tanggal='$tgl'
");
$d2 = mysqli_fetch_assoc($q2);
$bayar = $d2['jml'];

/* ================= FARMASI ================= */
$q3 = bukaquery("SELECT COUNT(no_rawat) AS jml FROM resep_obat WHERE tgl_perawatan='$tgl'");
$d3 = mysqli_fetch_assoc($q3);
$farmasi = $d3['jml'];

/* ================= LAB ================= */
$q4 = bukaquery("SELECT COUNT(no_rawat) AS jml FROM periksa_lab WHERE tgl_periksa='$tgl'");
$d4 = mysqli_fetch_assoc($q4);
$lab = $d4['jml'];
?>

<div class="info-harian">
  <span class="label-biru">Registrasi </span>
<b><span class="berkedip2"><?= $daftar ?></span></b>

  <span class="label-biru"> Billing Pasien Bayar</span>
<b><span class="berkedip2"><?= $bayar ?></span></b>

  <span class="label-biru"> Farmasi</span>
<b><span class="berkedip2"><?= $farmasi ?></span></b>

  <span class="label-biru"> Lab</span>
<b><span class="berkedip2"><?= $lab ?></span></b>

   <span class="live-text"> LIVE</span>
</div>




<!-- ★ JUMLAH TEXT BERJALAN ★ -->

<div class="running-text">
    <span id="text"></span>
</div>















<p>





<!-- ★ TABEL PASIEN PER POLI ★ -->

<?php 
// --------------------------------------------------
// Koneksi & query
$servername = "10.10.20.250";
$username = "root";
$password = "";
$dbname = "sikdraisyah";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Koneksi gagal: ".$conn->connect_error);
$tgl_hari_ini = date('Y-m-d');

// --- Data belum bayar (untuk running text) ---
$sql_belum = " SELECT rp.no_reg, rp.no_rawat, rp.tgl_registrasi, p.nm_pasien, d.nm_dokter, pl.nm_poli, pj.png_jawab, rp.status_bayar, rp.status_lanjut FROM reg_periksa rp JOIN pasien p ON rp.no_rkm_medis = p.no_rkm_medis JOIN dokter d ON rp.kd_dokter = d.kd_dokter JOIN poliklinik pl ON rp.kd_poli = pl.kd_poli JOIN penjab pj ON rp.kd_pj = pj.kd_pj WHERE DATE(rp.tgl_registrasi) = '$tgl_hari_ini' AND rp.status_bayar = 'Sudah Bayar' AND rp.status_lanjut = 'Ralan' ";
$result_belum = $conn->query($sql_belum);
$data_belum = [];
if ($result_belum->num_rows > 0) {
    while ($row = $result_belum->fetch_assoc()) $data_belum[] = $row;
} else {
    $data_belum[] = [ 'nm_pasien' => 'Belum Ada Pasien Bayar', 'nm_poli' => '', 'nm_dokter' => '' ];
}

// --- Jumlah sudah bayar ---
$sql_sudah = " SELECT COUNT(*) AS total FROM reg_periksa rp WHERE DATE(rp.tgl_registrasi) = '$tgl_hari_ini' AND rp.status_bayar = 'Sudah Bayar' AND rp.status_lanjut = 'Ralan' ";
$total_sudah = $conn->query($sql_sudah)->fetch_assoc()['total'];

// --------------------------------------------------
// Query utama (tabel)
$sql = " SELECT d.nm_dokter, pl.nm_poli, rp.status_lanjut, COUNT(*) AS total_pasien, SUM(CASE WHEN rp.status_bayar = 'Belum Bayar' THEN 1 ELSE 0 END) AS belum_bayar, SUM(CASE WHEN rp.status_bayar = 'Sudah Bayar' THEN 1 ELSE 0 END) AS sudah_bayar FROM reg_periksa rp INNER JOIN dokter d ON rp.kd_dokter = d.kd_dokter INNER JOIN poliklinik pl ON rp.kd_poli = pl.kd_poli WHERE DATE(rp.tgl_registrasi) = '$tgl_hari_ini' GROUP BY rp.kd_dokter, rp.kd_poli, rp.status_lanjut ";
$result = $conn->query($sql);
?>

<style>
.tabel-kecil {
    font-size: 0.80em;
    border-collapse: collapse;
    width: 100%;
    border: 2px solid #FF0000;
    table-layout: fixed;
}
.tabel-kecil th, .tabel-kecil td {
    border: 1px solid #FF0000;
    padding: 1px;
    word-wrap: break-word;
    overflow: hidden;
    text-overflow: ellipsis;
}
.tabel-kecil th {
    background: #f0f0f0;
    color: #000;
    border: 2px solid #000;
}
.berkedip {
    animation: berkedip 1s infinite;
}
@keyframes berkedip {
    0% { background-color: #FF0000; }
    50% { background-color: #FFFFFF; }
    100% { background-color: #FF0000; }
}



</style>





<style>
.tabel-kecil {
    font-size: 0.90em;
    border-collapse: collapse;
    width: 100%;
    border: 2px solid #FF0000;
    table-layout: fixed; /* pakai fixed supaya lebar kolom bisa diatur */
}
.tabel-kecil th, .tabel-kecil td {
    border: 1px solid #FF0000;
    padding: 1px;
    word-wrap: break-word;
    overflow: hidden;
    text-overflow: ellipsis;
}
.tabel-kecil th {
    background: linear-gradient(135deg, #3b82f6, #60a5fa); /* biru muda */
    color: white;      /* tulisan putih */
   border: 2px solid #93c5fd; /* tambahan */
letter-spacing: 0.5px;
    font-weight: bold;
}

/* Lebar khusus untuk kolom Dokter & Poliklinik */
.tabel-kecil th:nth-child(1), /* Dokter */
.tabel-kecil td:nth-child(1) {
    width: 25%;
    text-align: center;
}
.tabel-kecil th:nth-child(2), /* Poliklinik */
.tabel-kecil td:nth-child(2) {
    width: 25%;
    text-align: center;
}
/* Kolom angka kecil */
.tabel-kecil th:nth-child(4), /* Total Pasien */
.tabel-kecil td:nth-child(4),
.tabel-kecil th:nth-child(5), /* Belum Bayar */
.tabel-kecil td:nth-child(5),
.tabel-kecil th:nth-child(6), /* Sudah Bayar */
.tabel-kecil td:nth-child(6) {
    width: 10%;
    text-align: center;
}
.tabel-kecil td.angka {
    font-weight: bold;
    font-size: 1.2em;
}
/* Status Lanjut rata tengah */
.tabel-kecil th:nth-child(3),
.tabel-kecil td:nth-child(3) {
    text-align: center;
}
/* Baris TOTAL rata tengah */
.tabel-kecil tr:last-child td {
    text-align: center;
}

.tabel-kecil .belum-bayar {
    color: green;
 font-weight: bold;
}
.tabel-kecil .sudah-bayar {
    color: blue;
 font-weight: bold;
}
</style>







<?php
// --------------------------------------------------
// Tampilkan tabel
if ($result->num_rows > 0) {
    echo "<table class='tabel-kecil'>";
    echo "<tr>";
    echo "<th>Dokter</th>";
    echo "<th>Poliklinik</th>";
    echo "<th>Status Lanjut</th>";
    echo "<th>Total Pasien</th>";
    echo "<th>Belum Bayar</th>";
    echo "<th>Sudah Bayar</th>";
    echo "</tr>";
    $total_pasien = 0;
    $total_belum = 0;
    $total_sudah = 0;
    $ada_data = false;
    while ($row = $result->fetch_assoc()) {
        // Lewati baris bila belum_bayar = 0 ATAU status_lanjut != 'RAJAL'
        if ($row["belum_bayar"] == 0 || $row["status_lanjut"] != 'Ralan') continue;
        $ada_data = true;
        $class = ($row["belum_bayar"] == 1) ? 'berkedip' : '';
        echo "<tr class='$class'>";
        echo "<td>" . $row["nm_dokter"] . "</td>";
        echo "<td>" . $row["nm_poli"] . "</td>";
        echo "<td>" . $row["status_lanjut"]. "</td>";
        echo "<td class='angka'>" . $row["total_pasien"] . "</td>";
        echo "<td class='angka belum-bayar'>" . $row["belum_bayar"] . "</td>";
        echo "<td class='angka sudah-bayar'>" . $row["sudah_bayar"] . "</td>";
        echo "</tr>";
        $total_pasien += $row["total_pasien"];
        $total_belum += $row["belum_bayar"];
        $total_sudah += $row["sudah_bayar"];
    }
    if (!$ada_data) {
        echo "</table>";
        echo "<p style='text-align:center; font-weight:bold; color:#006600;'>Hore.... Pasien Hari Ini Sudah Periksa Semua</p>";
    } else {
        echo "<tr style='font-weight:bold; background:#f0f0f0;'>";
        echo "<td colspan='3'>TOTAL</td>";
        echo "<td class='angka'>" . $total_pasien . "</td>";
        echo "<td class='angka belum-bayar'>" . $total_belum . "</td>";
        echo "<td class='angka sudah-bayar'>" . $total_sudah . "</td>";
        echo "</tr>";
        echo "</table>";
    }
} else {
    echo "<p style='text-align:center; font-weight:bold; color:#006600;'>Hore.... Pasien Hari Ini Sudah Periksa Semua</p>";
}
$conn->close();
?>








<?php
// --------------------------------------------------
// Koneksi & query
$servername = "10.10.20.250";
$username   = "root";
$password   = "";
$dbname     = "sikdraisyah";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Koneksi gagal: ".$conn->connect_error);

$tgl_hari_ini = date('Y-m-d');

// --- Data belum bayar ---
$sql_belum = "
    SELECT rp.no_reg,
           rp.no_rawat,
           rp.tgl_registrasi,
           p.nm_pasien,
           d.nm_dokter,
           pl.nm_poli,
           pj.png_jawab,
           rp.status_bayar,
           rp.status_lanjut
    FROM reg_periksa rp
    JOIN pasien p  ON rp.no_rkm_medis = p.no_rkm_medis
    JOIN dokter d  ON rp.kd_dokter   = d.kd_dokter
    JOIN poliklinik pl ON rp.kd_poli = pl.kd_poli
    JOIN penjab pj ON rp.kd_pj = pj.kd_pj
    WHERE DATE(rp.tgl_registrasi) = '$tgl_hari_ini'
      AND rp.status_bayar = 'Sudah Bayar'
      AND rp.status_lanjut = 'Ralan'
";

$result_belum = $conn->query($sql_belum);
$data_belum = [];
if ($result_belum->num_rows > 0) {
    while ($row = $result_belum->fetch_assoc()) {
        $data_belum[] = $row;
    }
} else {
    $data_belum[] = [
        'nm_pasien' => 'Belum Ada Pasien Bayar',
        'nm_poli'   => '',
        'nm_dokter' => ''
    ];
}

// --- Jumlah sudah bayar ---
$sql_sudah = "
    SELECT COUNT(*) AS total
    FROM reg_periksa rp
    WHERE DATE(rp.tgl_registrasi) = '$tgl_hari_ini'
      AND rp.status_bayar = 'Sudah Bayar'
      AND rp.status_lanjut = 'Ralan'
";

$total_sudah = $conn->query($sql_sudah)->fetch_assoc()['total'];

$conn->close();
?>




<script>
    // Data belum bayar
    var dataBelum = <?= json_encode($data_belum); ?>;
    var i = 0;
    var text = document.getElementById('text');

    // Jumlah sudah bayar
    var totalSudah = <?= $total_sudah; ?>;

    function updateText() {
        var row = dataBelum[i];
        // Tampilkan data belum bayar
        var msg = row.nm_pasien +
                  (row.nm_poli ? ' (' + row.nm_poli + ' - ' + row.nm_dokter + ')' : '') +
                  '  ';// Tambahkan info Sudah Bayar ('')

        // Tambahkan info pasien sudah bayar (hanya sekali di akhir)
        if (i === dataBelum.length - 1) {
            msg += ' : ' + totalSudah;
        }

        text.innerHTML = msg;

        i++;
        if (i >= dataBelum.length) i = 0;
    }

    // Tampilkan pertama kali
    updateText();

    // Ganti tiap 1 detik
    setInterval(updateText, 2000);
</script>

</body>
</html>

