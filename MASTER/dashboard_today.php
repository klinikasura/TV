<?php

header('Content-Type: application/json');

/*
|--------------------------------------------------------------------------
| KONEKSI DATABASE
|--------------------------------------------------------------------------
*/

$conn = new mysqli(
    "10.10.20.250",
    "root",
    "",
    "sikdraisyah"
);

if ($conn->connect_error) {

    die(json_encode([
        "error" => $conn->connect_error
    ]));

}

date_default_timezone_set("Asia/Bangkok");

/*
|--------------------------------------------------------------------------
| TANGGAL HARI INI
|--------------------------------------------------------------------------
*/

$tgl = date('Y-m-d');

/*
|--------------------------------------------------------------------------
| FUNCTION AMBIL DATA
|--------------------------------------------------------------------------
*/

function getData($conn, $field){

    global $tgl;

    $query = "

    SELECT

    $field as label,
    COUNT(*) as jumlah

    FROM reg_periksa

    WHERE tgl_registrasi='$tgl'

    GROUP BY $field

    ";

    $result = $conn->query($query);

    $data = [];

    if($result){

        while($row = $result->fetch_assoc()){

            $data[] = $row;

        }

    }

    return $data;

}

/*
|--------------------------------------------------------------------------
| STATUS PASIEN
|--------------------------------------------------------------------------
*/

$stts = getData($conn, "stts");

/*
|--------------------------------------------------------------------------
| STATUS BAYAR
|--------------------------------------------------------------------------
*/

$status_bayar = getData($conn, "status_bayar");

/*
|--------------------------------------------------------------------------
| POLIKLINIK
|--------------------------------------------------------------------------
*/

$poli = [];

$q1 = $conn->query("

SELECT

pl.nm_poli as label,
COUNT(*) as jumlah

FROM reg_periksa rp

JOIN poliklinik pl
ON rp.kd_poli = pl.kd_poli

WHERE rp.tgl_registrasi='$tgl'

GROUP BY pl.nm_poli

ORDER BY jumlah DESC

LIMIT 10

");

if($q1){

    while($row = $q1->fetch_assoc()){

        $poli[] = $row;

    }

}

/*
|--------------------------------------------------------------------------
| DOKTER
|--------------------------------------------------------------------------
*/

$dokter = [];

$q2 = $conn->query("

SELECT

d.nm_dokter as label,
COUNT(*) as jumlah

FROM reg_periksa rp

JOIN dokter d
ON rp.kd_dokter = d.kd_dokter

WHERE rp.tgl_registrasi='$tgl'

GROUP BY d.nm_dokter

ORDER BY jumlah DESC

LIMIT 10

");

if($q2){

    while($row = $q2->fetch_assoc()){

        $dokter[] = $row;

    }

}

/*
|--------------------------------------------------------------------------
| CARA BAYAR
|--------------------------------------------------------------------------
*/

$cara_bayar = [];

$q3 = $conn->query("

SELECT

pj.png_jawab as label,
COUNT(*) as jumlah

FROM reg_periksa rp

JOIN penjab pj
ON rp.kd_pj = pj.kd_pj

WHERE rp.tgl_registrasi='$tgl'

GROUP BY pj.png_jawab

ORDER BY jumlah DESC

");

if($q3){

    while($row = $q3->fetch_assoc()){

        $cara_bayar[] = $row;

    }

}

/*
|--------------------------------------------------------------------------
| JENIS KELAMIN
|--------------------------------------------------------------------------
*/

$jenis_kelamin = [];

$q4 = $conn->query("

SELECT

IF(
    p.jk='L',
    'Laki-Laki',
    'Perempuan'
) as label,

COUNT(*) as jumlah

FROM reg_periksa rp

JOIN pasien p
ON rp.no_rkm_medis = p.no_rkm_medis

WHERE rp.tgl_registrasi='$tgl'

GROUP BY p.jk

");

if($q4){

    while($row = $q4->fetch_assoc()){

        $jenis_kelamin[] = $row;

    }

}

/*
|--------------------------------------------------------------------------
| STATUS DAFTAR
|--------------------------------------------------------------------------
*/

$status_daftar = [];

$q5 = $conn->query("

SELECT

stts_daftar as label,
COUNT(*) as jumlah

FROM reg_periksa

WHERE tgl_registrasi='$tgl'

GROUP BY stts_daftar

");

if($q5){

    while($row = $q5->fetch_assoc()){

        $status_daftar[] = $row;

    }

}


/*
|--------------------------------------------------------------------------
| STATUS UMUR
|--------------------------------------------------------------------------
*/

$status_umur = [];

$q6 = $conn->query("

SELECT

CASE

WHEN umurdaftar < 17
THEN 'Anak'

WHEN umurdaftar >= 17
AND umurdaftar <= 45
THEN 'Dewasa'

ELSE 'Lansia'

END as label,

COUNT(*) as jumlah

FROM reg_periksa

WHERE tgl_registrasi='$tgl'

GROUP BY label

");

if($q6){

    while($row = $q6->fetch_assoc()){

        $status_umur[] = $row;

    }

}
/*
|--------------------------------------------------------------------------
| STATUS LANJUT
|--------------------------------------------------------------------------
*/

$status_lanjut = [];

$q7 = $conn->query("

SELECT

status_lanjut as label,
COUNT(*) as jumlah

FROM reg_periksa

WHERE tgl_registrasi='$tgl'

GROUP BY status_lanjut

");

if($q7){

    while($row = $q7->fetch_assoc()){

        $status_lanjut[] = $row;

    }

}

/*
|--------------------------------------------------------------------------
| OUTPUT JSON
|--------------------------------------------------------------------------
*/

echo json_encode([

    "stts" => $stts,

    "status_bayar" => $status_bayar,

    "cara_bayar" => $cara_bayar,

    "kd_poli" => $poli,

    "kd_dokter" => $dokter,

    "jenis_kelamin" => $jenis_kelamin,

    "status_daftar" => $status_daftar,

    "status_umur" => $status_umur,

    "status_lanjut" => $status_lanjut

], JSON_PRETTY_PRINT);

?>
