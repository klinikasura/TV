<?php
$conn = new mysqli("10.10.20.250", "root", "", "sikdraisyah");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

function getData($conn, $field) {
    $query = "SELECT $field as label, COUNT(*) as jumlah 
              FROM reg_periksa 
              WHERE tgl_registrasi = CURDATE()
              GROUP BY $field";

    $result = $conn->query($query);
    $data = [];

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    return $data;
}

// status
$stts = getData($conn, "stts");
$status_bayar = getData($conn, "status_bayar");

// poli
$poli = [];
$q1 = $conn->query("
SELECT pl.nm_poli as label, COUNT(*) as jumlah
FROM reg_periksa rp
JOIN poliklinik pl ON rp.kd_poli = pl.kd_poli
WHERE rp.tgl_registrasi = CURDATE()
GROUP BY pl.nm_poli
");

if ($q1) {
    while ($row = $q1->fetch_assoc()) {
        $poli[] = $row;
    }
}

// dokter
$dokter = [];
$q2 = $conn->query("
SELECT d.nm_dokter as label, COUNT(*) as jumlah
FROM reg_periksa rp
JOIN dokter d ON rp.kd_dokter = d.kd_dokter
WHERE rp.tgl_registrasi = CURDATE()
GROUP BY d.nm_dokter
");

if ($q2) {
    while ($row = $q2->fetch_assoc()) {
        $dokter[] = $row;
    }
}

// 🔥 cara bayar (FIX UTAMA)
$cara_bayar = [];
$q3 = $conn->query("
SELECT pj.png_jawab as label, COUNT(*) as jumlah
FROM reg_periksa rp
JOIN penjab pj ON rp.kd_pj = pj.kd_pj
WHERE rp.tgl_registrasi = CURDATE()
GROUP BY pj.png_jawab
");

if ($q3) {
    while ($row = $q3->fetch_assoc()) {
        $cara_bayar[] = $row;
    }
}

// response
echo json_encode([
    "stts" => $stts,
    "status_bayar" => $status_bayar,
    "cara_bayar" => $cara_bayar,
    "kd_poli" => $poli,
    "kd_dokter" => $dokter
]);
?>
