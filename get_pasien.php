    <meta http-equiv="refresh" content="0;url=get_pasien.php">

<?php
$servername = "10.10.20.250";
$username   = "root";
$password   = "";
$dbname     = "sikdraisyah";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Koneksi gagal: ".$conn->connect_error);

$tgl_hari_ini = date('Y-m-d');
$sql = "
    SELECT rp.tgl_registrasi,
           p.nm_pasien,
           d.nm_dokter,
           pl.nm_poli,
           rp.status_bayar,
           rp.status_lanjut
    FROM reg_periksa rp
    JOIN pasien p  ON rp.no_rkm_medis = p.no_rkm_medis
    JOIN dokter d  ON rp.kd_dokter   = d.kd_dokter
    JOIN poliklinik pl ON rp.kd_poli = pl.kd_poli
    WHERE DATE(rp.tgl_registrasi) = '$tgl_hari_ini'
      AND rp.status_bayar = 'Belum Bayar'
      AND rp.status_lanjut = 'Ralan'
";

$result = $conn->query($sql);
$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else {
    $data[] = [
        'nm_pasien' => 'Hore.... Pasien Hari Ini Sudah Periksa Semua',
        'nm_poli'   => '',
        'nm_dokter' => ''
    ];
}
$conn->close();

header('Content-Type: application/json');
echo json_encode($data);
?>
