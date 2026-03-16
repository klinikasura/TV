<?php
require_once('conf/conf.php');

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
date_default_timezone_set("Asia/Bangkok");

/* ================= KONEKSI DATABASE ================= */
$servername = "10.10.20.250";
$username   = "root";
$password   = "";
$dbname     = "sikdraisyah";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Koneksi gagal: ".$conn->connect_error);

$tgl_hari_ini = date('Y-m-d');

/* ================= AMBIL DATA PASIEN SUDAH BAYAR ================= */
$sql = "
SELECT rp.tgl_registrasi, p.nm_pasien, d.nm_dokter, pl.nm_poli
FROM reg_periksa rp
JOIN pasien p ON rp.no_rkm_medis = p.no_rkm_medis
JOIN dokter d ON rp.kd_dokter = d.kd_dokter
JOIN poliklinik pl ON rp.kd_poli = pl.kd_poli
WHERE DATE(rp.tgl_registrasi) = '$tgl_hari_ini'
AND rp.status_bayar = 'Sudah Bayar'
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
        'nm_pasien' => 'Belum Ada Pasien Bayar',
        'nm_poli'   => '',
        'nm_dokter' => ''
    ];
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
<title>Aplikasi RS. Asura</title>
<meta http-equiv="refresh" content="60;url=pasien-sudah-bayar.php">

<style>
body{
    margin:0;
    background:#ffffff; /* Background putih */
    font-family:Tahoma, Arial, sans-serif;
    overflow:hidden;
}

.running-text { 
            width:100%; 
            font-size:15px; 
color:blue;
            overflow:hidden; 
            white-space:nowrap; 
            padding:0; 
            box-sizing:border-box; 
            font-weight:bold; 
            text-align:center; 
            position: absolute; 
            top: 0; 
            left: 0; 
        } 
        .running-text span { 
            display:inline-block; 
        } 

/* Cursor Blink */
.cursor{
    display:inline-block;
    margin-left:3px;
    animation: blink 1s infinite;
    color:#0056b3; /* Cursor biru */
}

@keyframes blink{
    0%{opacity:1;}
    50%{opacity:0;}
    100%{opacity:1;}
}
</style>
</head>

<body>

<div class="running-text">
    <span id="text"></span><span class="cursor">|</span>
</div>

<script>
var data = <?= json_encode($data); ?>;
var i = 0;

var textElement = document.getElementById('text');

var typingSpeed = 40;
var deletingSpeed = 25;
var delayAfterTyping = 1500;

function typeEffect(text, callback){
    let index = 0;
    textElement.innerHTML = "";

    function type(){
        if(index < text.length){
            textElement.innerHTML += text.charAt(index);
            index++;
            setTimeout(type, typingSpeed);
        }else{
            setTimeout(callback, delayAfterTyping);
        }
    }
    type();
}

function deleteEffect(callback){
    let text = textElement.innerHTML;

    function deleteChar(){
        if(text.length > 0){
            text = text.slice(0,-1);
            textElement.innerHTML = text;
            setTimeout(deleteChar, deletingSpeed);
        }else{
            callback();
        }
    }
    deleteChar();
}

function tampilkanPasien(){
    var row = data[i];
    var teksLengkap = row.nm_pasien +
        (row.nm_poli ? ' (' + row.nm_poli + ' - ' + row.nm_dokter + ')' : '');

    typeEffect(teksLengkap, function(){
        deleteEffect(function(){
            i++;
            if(i >= data.length) i = 0;
            tampilkanPasien();
        });
    });
}

tampilkanPasien();
</script>

</body>
</html>
