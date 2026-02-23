<?php 
date_default_timezone_set("Asia/Jakarta"); 
/* KOORDINAT LEMPUING OKI */
$latitude = -3.56; 
$longitude = 105.12; 
/* tanggal hari ini */
$tanggal = date("d-m-Y"); 
/* ambil jadwal sholat berdasarkan koordinat */
$url = "http://api.aladhan.com/v1/timingsByCity?city=Oki&country=Indonesia&method=2"; 
$response = @file_get_contents($url); 
$imsak = "--:--"; 
$maghrib = "--:--"; 
if($response !== FALSE){ 
    $data = json_decode($response, true); 
    if(isset($data['data']['timings'])){ 
        $imsak = substr($data['data']['timings']['Imsak'],0,5); 
        $maghrib = substr($data['data']['timings']['Maghrib'],0,5); 
    } 
} 
?> 
<!DOCTYPE html> 
<html lang="id"> 
<head> 
    <meta charset="UTF-8"> 
    <title>Aplikasi RS. Asura</title> 
    <link href="http://10.10.20.250/dashboard/download.jpeg" rel="icon" type="image/png" /> 
    <style> 
    body{ 
        margin:0; 
        background:#0b3d91; 
        overflow:hidden; 
        font-family:Tahoma, Arial, sans-serif; 
    } 
    /* banner */ 
    .banner{ 
        width:100%; 
        height:60px; 
        position:fixed; 
        top:0; 
        left:0; 
        overflow:hidden; 
        background:#0b3d91; 
        border-top:3px solid white; 
        border-bottom:3px solid white; 
    } 
    /* jalur teks */ 
    .track{ 
        display:flex; 
        width:max-content; 
        animation:scroll 40s linear infinite; 
    } 
    /* tulisan */ 
    .text{ 
        white-space:nowrap; 
        font-size:20px; 
        font-weight:bold; 
        letter-spacing:2px; 
        color:white; 
        padding-right:140px; 
    } 
    /* animasi tanpa henti */ 
    @keyframes scroll{ 
        0%{ 
            transform:translateX(0); 
        } 
        100%{ 
            transform:translateX(-50%); 
        } 
    } 
    </style> 
</head> 
<body> 
    <div class="banner"> 
        <div class="track"> 
            <div class="text"> 
                RAMADHAN TAHUN 1447 H / 2026 M • Jadwal Imsakiyah, Hari Ini Tanggal : <?= $tanggal; ?> • Imsak (<?= $imsak; ?> WIB) • Maghrib (<?= $maghrib; ?> WIB) • Selamat Menunaikan Ibadah Puasa • 
            </div> 
            <div class="text"> 
                RAMADHAN TAHUN 1447 H / 2026 M • Jadwal Imsakiyah, Hari Ini Tanggal : <?= $tanggal; ?> • Imsak (<?= $imsak; ?> WIB) • Maghrib (<?= $maghrib; ?> WIB) • Selamat Menunaikan Ibadah Puasa • 
            </div> 
        </div> 
    </div> 
</body> 
</html>

