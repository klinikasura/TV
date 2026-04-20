<!DOCTYPE html>
<html>
<head>
    <title>Aplikasi RS. Asura</title>
    <link href="http://10.10.20.250/dashboard/download.jpeg" rel="icon" type="image/png" />
    <meta http-equiv="refresh" content="2;url=hanya-pasien.php">
<style>
body {
    margin:0;
    font-family: 'Segoe UI', Tahoma, sans-serif;
    background: linear-gradient(135deg, #e0f2fe, #f0f9ff);
}

/* TABEL */
table {
    border-collapse: collapse;
    width: 98%;
    margin: 20px auto;
    background: #f8fbff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

/* HEADER */
th {
    background: linear-gradient(135deg, #60a5fa, #93c5fd);
    color: white;
    padding: 12px;
    text-align: center;
    font-size: 16px;
    letter-spacing: 0.5px;
}

/* ISI */
td {
    border: 1px solid #bfdbfe;
    padding: 10px;
    text-align: center;
    font-size: 18px;
    color: #1e3a8a;
    font-weight: 500;
}

/* ZEBRA */
tr:nth-child(even) {
    background: #f1f5ff;
}

/* HOVER */
tr:hover {
    background: #dbeafe;
    transition: 0.2s;
}

/* TOTAL ROW */
tr:last-child {
    background: #bfdbfe !important;
    font-weight: bold;
    color: #1e40af;
}

/* KHUSUS KOLOM */
td:nth-child(2) {
    text-align: left; /* nama dokter rata kiri */
    font-weight: 600;
    color: #1d4ed8;
}

/* RESPONSIVE TV */
@media screen and (min-width: 1200px) {
    th, td {
        font-size: 18px;
    }
}

/* ANIMASI HALUS */
* {
    transition: all 0.2s ease-in-out;
}
</style>
</head>
<body>
<?php
// Koneksi ke database
$servername = "10.10.20.250";
$username   = "root";
$password   = "";
$dbname     = "sikdraisyah";
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$tgl_hari_ini = date('Y-m-d');

// Query tambahan: hitung total pasien, belum bayar, sudah bayar, status lanjut
$sql = "
    SELECT 
        d.nm_dokter,
        pl.nm_poli,
        DATE(rp.tgl_registrasi) AS tgl_reg,
        rp.status_lanjut,
        COUNT(*) AS total_pasien,
        SUM(CASE WHEN rp.status_bayar = 'Belum Bayar' THEN 1 ELSE 0 END) AS belum_bayar,
        SUM(CASE WHEN rp.status_bayar = 'Sudah Bayar' THEN 1 ELSE 0 END) AS sudah_bayar
    FROM reg_periksa rp
    INNER JOIN dokter d   ON rp.kd_dokter = d.kd_dokter
    INNER JOIN poliklinik pl ON rp.kd_poli = pl.kd_poli
    WHERE DATE(rp.tgl_registrasi) = '$tgl_hari_ini'
    GROUP BY rp.kd_dokter, rp.kd_poli, DATE(rp.tgl_registrasi), rp.status_lanjut
";

$result = $conn->query($sql);

// Tampilkan data
if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr>";
    echo "<th>Tanggal</th>";
    echo "<th>Dokter</th>";
    echo "<th>Poliklinik</th>";
    echo "<th>Status Lanjut</th>";
    echo "<th>Total Pasien</th>";
    echo "<th>Belum Bayar</th>";
    echo "<th>Sudah Bayar</th>";
    echo "</tr>";

    $total_pasien   = 0;
    $total_belum    = 0;
    $total_sudah    = 0;

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["tgl_reg"]      . "</td>";
        echo "<td>" . $row["nm_dokter"]   . "</td>";
        echo "<td>" . $row["nm_poli"]     . "</td>";
        echo "<td>" . $row["status_lanjut"]. "</td>";
        echo "<td>" . $row["total_pasien"]. "</td>";
        echo "<td>" . $row["belum_bayar"] . "</td>";
        echo "<td>" . $row["sudah_bayar"] . "</td>";
        echo "</tr>";

        $total_pasien += $row["total_pasien"];
        $total_belum  += $row["belum_bayar"];
        $total_sudah  += $row["sudah_bayar"];
    }

    // Baris total
    echo "<tr style='font-weight:bold; background:#f0f0f0;'>";
    echo "<td colspan='4'>TOTAL</td>";
    echo "<td>" . $total_pasien . "</td>";
    echo "<td>" . $total_belum  . "</td>";
    echo "<td>" . $total_sudah  . "</td>";
    echo "</tr>";

    echo "</table>";
} else {
    echo "Tidak ada data";
}

$conn->close();
?>




</body>
</html>
