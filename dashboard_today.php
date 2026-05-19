```php
<?php

header('Content-Type: application/json');

$host     = "10.10.20.250";
$user     = "root";
$password = "";
$database = "sikdraisyah";

$conn = new mysqli($host,$user,$password,$database);

if($conn->connect_error){

    die(json_encode([
        "error" => $conn->connect_error
    ]));

}

date_default_timezone_set("Asia/Bangkok");

$tgl = date('Y-m-d');

$data = [];

/*
|--------------------------------------------------------------------------
| CARA BAYAR
|--------------------------------------------------------------------------
*/

$q1 = $conn->query("

SELECT
png_jawab AS label,
COUNT(*) AS jumlah

FROM reg_periksa

WHERE tgl_registrasi='$tgl'

GROUP BY png_jawab

ORDER BY jumlah DESC

");

$data['cara_bayar'] = [];

while($r = $q1->fetch_assoc()){

    $data['cara_bayar'][] = $r;

}

/*
|--------------------------------------------------------------------------
| STATUS BAYAR
|--------------------------------------------------------------------------
*/

$q2 = $conn->query("

SELECT
status_bayar AS label,
COUNT(*) AS jumlah

FROM reg_periksa

WHERE tgl_registrasi='$tgl'

GROUP BY status_bayar

");

$data['status_bayar'] = [];

while($r = $q2->fetch_assoc()){

    $data['status_bayar'][] = $r;

}

/*
|--------------------------------------------------------------------------
| POLIKLINIK
|--------------------------------------------------------------------------
*/

$q3 = $conn->query("

SELECT

pl.nm_poli AS label,
COUNT(*) AS jumlah

FROM reg_periksa rp

JOIN poliklinik pl
ON rp.kd_poli=pl.kd_poli

WHERE rp.tgl_registrasi='$tgl'

GROUP BY rp.kd_poli

ORDER BY jumlah DESC

LIMIT 10

");

$data['kd_poli'] = [];

while($r = $q3->fetch_assoc()){

    $data['kd_poli'][] = $r;

}

/*
|--------------------------------------------------------------------------
| DOKTER
|--------------------------------------------------------------------------
*/

$q4 = $conn->query("

SELECT

d.nm_dokter AS label,
COUNT(*) AS jumlah

FROM reg_periksa rp

JOIN dokter d
ON rp.kd_dokter=d.kd_dokter

WHERE rp.tgl_registrasi='$tgl'

GROUP BY rp.kd_dokter

ORDER BY jumlah DESC

LIMIT 10

");

$data['kd_dokter'] = [];

while($r = $q4->fetch_assoc()){

    $data['kd_dokter'][] = $r;

}


echo json_encode($data, JSON_PRETTY_PRINT);

?>
```

