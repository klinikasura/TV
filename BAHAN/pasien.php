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
    <meta http-equiv="refresh" content="60;url=pasien.php">
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
        font-size: 14px;
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





</head>
<body>

<font color="black">Daftar Hari Ini :
<font color="black"><b>(<?php
$tgl = date("Y-m-d ");
$tes = "select count(no_rawat) as h from reg_periksa where tgl_registrasi='$tgl'";
$hasil = bukaquery($tes);
while ($data = mysqli_fetch_array($hasil)) {
    $jml = $data['h'];
}
print_r($jml);
?>
)</b></font> | Billing Pasien Bayar
<font color="black"><b>(<?php
$tgl = date("Y-m-d ");
$bayar = 0;
$nama_pasien = [];

$tes = "SELECT p.nm_pasien, pl.nm_poli
        FROM nota_jalan nj
        INNER JOIN reg_periksa rp ON nj.no_rawat = rp.no_rawat
        INNER JOIN pasien p ON rp.no_rkm_medis = p.no_rkm_medis
        INNER JOIN poliklinik pl ON rp.kd_poli = pl.kd_poli
        WHERE nj.tanggal = '$tgl'";
$hasil = bukaquery($tes);
while ($data = mysqli_fetch_array($hasil)) {
    $bayar++;
    $nama_pasien[] = $data['nm_pasien'] . " (" . $data['nm_poli'] . ")";
}
print_r($bayar);
?>)</b></font> | Farmasi
<font color="black"><b>(<?php
$tgl = date("Y-m-d ");
$tes = "select count(no_rawat) as h from resep_obat where tgl_perawatan='$tgl'";
$hasil = bukaquery($tes);
while ($data = mysqli_fetch_array($hasil)) {
    $jml = $data['h'];
}
print_r($jml);
?>)</b></font> | Lab
<font color="black"><b>(<?php
$tgl = date("Y-m-d ");
$tes = "select count(no_rawat) as h from periksa_lab where tgl_periksa='$tgl'";
$hasil = bukaquery($tes);
while ($data = mysqli_fetch_array($hasil)) {
    $jml = $data['h'];
}
print_r($jml);
?>)</b></font>



<div class="running-text">
    <span id="text"></span>
</div>



<?php
// Koneksi ke database
$servername = "10.10.20.250";
$username   = "root";
$password   = "";
$dbname     = "sikdraisyah";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$tgl_hari_ini = date('Y-m-d');

// Query tambahan: hitung total pasien, belum bayar, sudah bayar, status lanjut
$sql = "
    SELECT 
        d.nm_dokter,
        pl.nm_poli,
        rp.status_lanjut,
        COUNT(*) AS total_pasien,
        SUM(CASE WHEN rp.status_bayar = 'Belum Bayar' THEN 1 ELSE 0 END) AS belum_bayar,
        SUM(CASE WHEN rp.status_bayar = 'Sudah Bayar' THEN 1 ELSE 0 END) AS sudah_bayar
    FROM reg_periksa rp
    INNER JOIN dokter d   ON rp.kd_dokter = d.kd_dokter
    INNER JOIN poliklinik pl ON rp.kd_poli = pl.kd_poli
    WHERE DATE(rp.tgl_registrasi) = '$tgl_hari_ini'
    GROUP BY rp.kd_dokter, rp.kd_poli, rp.status_lanjut
";

$result = $conn->query($sql);
?>

<style>
    .tabel-kecil {
        font-size: 0.90em;
        border-collapse: collapse;
        width: 100%;
        border: 2px solid #FF0000;   /* tabel border merah */
    }
    .tabel-kecil th,
    .tabel-kecil td {
        border: 1px solid #FF0000;   /* sel border merah */
        padding: 3px;
    }
    .tabel-kecil th {
        background: #f0f0f0;
        color: #000;
        border: 1px solid #000;      /* header border hitam */
    }
    /* Angka di dalam sel menjadi tebal & sedikit lebih besar */
    .tabel-kecil td.angka {
        font-weight: bold;
        font-size: 1.4em;            /* sesuaikan bila ingin lebih besar */
    }
</style>

<?php
// Tampilkan data
if ($result->num_rows > 0) {
    echo "<table class='tabel-kecil'>";
    echo "<tr>";
    echo "<th>Dokter</th>";
    echo "<th>Poliklinik</th>";
    echo "<th>Status Lanjut</th>";
    echo "<th>Total Pasien</th>";
    echo "<th>Belum Periksa</th>";
    echo "<th>Sudah Periksa</th>";
    echo "</tr>";

    $total_pasien = 0;
    $total_belum  = 0;
    $total_sudah  = 0;
    $ada_data     = false;   // flag untuk mengecek apakah ada baris yang ditampilkan

    while ($row = $result->fetch_assoc()) {
        // Lewati baris bila belum_bayar = 0
        if ($row["belum_bayar"] == 0) continue;

        $ada_data = true;   // set flag kalau ada baris yang ditampilkan

        echo "<tr>";
        echo "<td>" . $row["nm_dokter"]   . "</td>";
        echo "<td>" . $row["nm_poli"]     . "</td>";
        echo "<td>" . $row["status_lanjut"]. "</td>";
        echo "<td class='angka'>" . $row["total_pasien"] . "</td>";
        echo "<td class='angka'>" . $row["belum_bayar"] . "</td>";
        echo "<td class='angka'>" . $row["sudah_bayar"] . "</td>";
        echo "</tr>";

        $total_pasien += $row["total_pasien"];
        $total_belum  += $row["belum_bayar"];
        $total_sudah  += $row["sudah_bayar"];
    }

    // Jika tidak ada baris yang ditampilkan, tampilkan pesan khusus
    if (!$ada_data) {
        echo "</table>";
        echo "<p style='text-align:center; font-weight:bold; color:#006600;'>Hore.... Pasien Hari Ini Sudah Periksa Semu</p>";
    } else {
        // Baris total (colspan disesuaikan karena kolom tanggal dihapus)
        echo "<tr style='font-weight:bold; background:#f0f0f0;'>";
        echo "<td colspan='3'>TOTAL</td>";
        echo "<td class='angka'>" . $total_pasien . "</td>";
        echo "<td class='angka'>" . $total_belum  . "</td>";
        echo "<td class='angka'>" . $total_sudah  . "</td>";
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
                  ' (Sudah Bayar) ';

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
    setInterval(updateText, 1000);
</script>

</body>
</html>

