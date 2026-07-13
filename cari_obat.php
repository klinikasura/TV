<?php
include "koneksi2.php";

$term = $_GET['term'];

$data = [];

$query = mysqli_query($koneksi,"
SELECT
    d.kode_brng,
    d.nama_brng,

    GROUP_CONCAT(
        CONCAT(
            b.nm_bangsal,
            ' : ',
            gb.stok
        )
        SEPARATOR ' | '
    ) AS detail_stok,

    SUM(gb.stok) AS total_stok

FROM databarang d

LEFT JOIN gudangbarang gb
ON d.kode_brng = gb.kode_brng

LEFT JOIN bangsal b
ON gb.kd_bangsal = b.kd_bangsal

WHERE d.nama_brng LIKE '%$term%'
AND d.status='1'

GROUP BY d.kode_brng,d.nama_brng

ORDER BY d.nama_brng
LIMIT 20
");

while($d=mysqli_fetch_assoc($query)){

    $data[] = array(
        "label" =>
            $d['nama_brng'].
            " | Total : ".$d['total_stok'].
            " | ".$d['detail_stok'],

        "value" => $d['nama_brng'],
        "kode" => $d['kode_brng'],
        "stok" => $d['total_stok'],
        "detail_stok" => $d['detail_stok']
    );
}

echo json_encode($data);
?>
