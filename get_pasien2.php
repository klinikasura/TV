<?php
header('Content-Type: application/json');

$conn = new mysqli("10.10.20.250", "root", "", "sikdraisyah");

if ($conn->connect_error) {
    die(json_encode([]));
}

$sql = "
SELECT 
    rp.no_rawat,
    rp.tgl_registrasi,
    rp.jam_reg,
    p.nm_pasien,
    pl.nm_poli,
    pj.png_jawab AS cara_bayar,
    rp.status_bayar
FROM reg_periksa rp
JOIN pasien p ON rp.no_rkm_medis = p.no_rkm_medis
JOIN poliklinik pl ON rp.kd_poli = pl.kd_poli
JOIN penjab pj ON rp.kd_pj = pj.kd_pj
WHERE DATE(rp.tgl_registrasi) = CURDATE()
ORDER BY rp.jam_reg ASC
";

$result = $conn->query($sql);

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);

$conn->close();
?>
