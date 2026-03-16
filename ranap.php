<!DOCTYPE html>
<html>
<head>
<title>Aplikasi RS. Asura</title>
<link href="http://10.10.20.250/dashboard/download.jpeg" rel="icon" type="image/png">
<meta charset="UTF-8">
</head>

<?php
require_once('conf/conf.php');
date_default_timezone_set("Asia/Bangkok");

// ================== KONEKSI DATABASE ==================
$servername = "10.10.20.250";
$username   = "root";
$password   = "";
$dbname     = "sikdraisyah";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// ================== QUERY PASIEN RAWAT INAP ==================
$sql = "SELECT 
ki.no_rawat,
b.nm_bangsal,
k.kelas,
rp.tgl_registrasi,
p.nm_pasien,
IFNULL(ki.diagnosa_awal, '-') as diagnosa

FROM kamar_inap ki
JOIN kamar k ON ki.kd_kamar = k.kd_kamar
JOIN bangsal b ON k.kd_bangsal = b.kd_bangsal
JOIN reg_periksa rp ON ki.no_rawat = rp.no_rawat
JOIN pasien p ON rp.no_rkm_medis = p.no_rkm_medis

WHERE k.status = 'ISI'
AND ki.stts_pulang = '-'

ORDER BY b.nm_bangsal ASC";

$result = $conn->query($sql);
$data   = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

$jumlah_pasien = count($data);
$conn->close();
?>

<style>
body{
    font-family: Arial, Helvetica, sans-serif;
    background:white;
}

.judul{
    font-size:18px;
    padding:8px;
    color:red;
    text-align:center;
}

#marquee{
    font-size:14px;
    color:green;
    overflow:hidden;
    white-space:nowrap;
}

#text{
    display:inline-block;
    padding:8px;
}

.nama{
    font-weight:bold;
    font-size:16px;
    color:#008000;
}

.diagnosa{
    color:#b30000;
    font-size:14px;
    margin-top:5px;
}

.kosong{
    font-size:18px;
    color:red;
    font-weight:bold;
    margin-top:8px;
    animation: kedip 1s infinite;
}

@keyframes kedip{
    0%{opacity:1;}
    50%{opacity:0.2;}
    100%{opacity:1;}
}
</style>

<body>

<!-- JUMLAH PASIEN -->
<div class="judul">
(<?= $jumlah_pasien ?>) PASIEN RAWAT INAP
</div>

<div id="marquee">
    <div id="text"></div>
</div>

<script>
var data   = <?= json_encode($data); ?>;
var jumlah = <?= $jumlah_pasien ?>;
var text   = document.getElementById('text');

// =================== JIKA TIDAK ADA PASIEN ===================
if(jumlah === 0){

    text.innerHTML =
    `<div class="kosong">
     KOSONG
     </div>`;

}
// =================== JIKA ADA PASIEN ===================
else{

    var i = 0;

    function tampil(){
        var pasien = data[i];

        text.innerHTML =
        `<div>
            <div class="nama">${pasien.nm_pasien}</div>
            No Rawat : ${pasien.no_rawat}<br>
            Ruang    : ${pasien.nm_bangsal} - Kelas ${pasien.kelas}<br>
            Tanggal Masuk : ${pasien.tgl_registrasi}<br>
            <div class="diagnosa">
            Diagnosa : ${pasien.diagnosa}
            </div>
        </div>`;

        i++;
        if(i >= data.length) i = 0;
    }

    tampil();
    setInterval(tampil, 5000);
}
</script>

</body>
</html>
