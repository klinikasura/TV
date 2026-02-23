<?php // -------------------------------------------------- // Konfigurasi dasar 
require_once('conf/conf.php'); 
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); 
date_default_timezone_set("Asia/Bangkok"); 
$tanggal = mktime(date("m"),date("d"),date("Y")); 
$jam = date("H:i"); 
?> 
<!DOCTYPE html> 
<html> 
<head> 
    <title>Aplikasi RS. Asura</title> 
    <link href="http://10.10.20.250/dashboard/download.jpeg" rel="icon" type="image/png" /> 
  <meta http-equiv="refresh" content="60;url=pasien-sudah-bayar.php">
    <?php // -------------------------------------------------- // Data pasien belum bayar (running text) 
    $servername = "10.10.20.250"; 
    $username = "root"; 
    $password = ""; 
    $dbname = "sikdraisyah"; 
    $conn = new mysqli($servername, $username, $password, $dbname); 
    if ($conn->connect_error) die("Koneksi gagal: ".$conn->connect_error); 
    $tgl_hari_ini = date('Y-m-d'); 
    $sql = " SELECT rp.tgl_registrasi, p.nm_pasien, d.nm_dokter, pl.nm_poli, rp.status_bayar, rp.status_lanjut FROM reg_periksa rp JOIN pasien p ON rp.no_rkm_medis = p.no_rkm_medis JOIN dokter d ON rp.kd_dokter = d.kd_dokter JOIN poliklinik pl ON rp.kd_poli = pl.kd_poli WHERE DATE(rp.tgl_registrasi) = '$tgl_hari_ini' AND rp.status_bayar = 'Sudah Bayar' AND rp.status_lanjut = 'Ralan' "; 
    $result = $conn->query($sql); 
    $data = []; 
    if ($result->num_rows > 0) { 
        while ($row = $result->fetch_assoc()) $data[] = $row; 
    } else { 
        $data[] = [ 'nm_pasien' => 'Belum Ada Pasien Bayar', 'nm_poli' => '', 'nm_dokter' => '' ]; 
    } 
    $conn->close(); 
    ?> 
    <style> 
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
    </style> 
</head> 
<body> 
    <div class="running-text"> 
        <span id="text"></span> 
    </div> 
    <script> 
        var data = <?= json_encode($data); ?>; 
        var i = 0; 
        var text = document.getElementById('text'); 
        function updateText() { 
            var row = data[i]; 
            text.innerHTML = row.nm_pasien + (row.nm_poli ? ' (' + row.nm_poli + ' - ' + row.nm_dokter + ')' : '') + ''; 
            i++; 
            if (i >= data.length) i = 0; 
        } 
        updateText(); 
        setInterval(updateText, 2000); 
    </script> 
</body> 
</html>

