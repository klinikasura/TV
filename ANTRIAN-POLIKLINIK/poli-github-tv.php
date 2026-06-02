<?php
session_start();
require_once('conf/conf.php');

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$tanggal = mktime(date("m"), date("d"), date("Y"));
date_default_timezone_set('Asia/Jakarta');
$jam = date("H:i");
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href="http://10.10.20.250/dashboard/download.jpeg" rel="icon" type="image/png" />
<script type="text/javascript" src="conf/validator.js"></script>

<meta http-equiv="refresh" content="2;url=poli-github-tv.php">

<title>Aplikasi RS. Asura</title>

<script src="Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
<script src="Scripts/AC_ActiveX.js" type="text/javascript"></script>

<style type="text/css">

body{
    margin:0;
    font-family:'Segoe UI',Tahoma,sans-serif;
    background:linear-gradient(135deg,#dbeafe,#eff6ff);
}

/* TABEL */
.tabel-kecil{
    font-size:0.9em;
    border-collapse:collapse;
    width:98%;
    margin:10px auto;
    border-radius:10px;
    overflow:hidden;
    box-shadow:0 5px 20px rgba(0,0,0,0.1);
    background:#ffffff;
}
/* KEDIP MERAH */
.blink-time{
    animation: blinkRed 1s infinite;
    font-weight:bold;
}

@keyframes blinkRed{
    0%,100%{
        background:#ffffff;
        color:#000000;
    }
    50%{
        background:#ff0000;
        color:#ffffff;
    }
}

/* STATUS */
.status-buka{
    background:#16a34a;
    color:#fff;
    padding:6px 12px;
    border-radius:5px;
    font-weight:bold;
}

.status-tutup{
    background:#dc2626;
    color:#fff;
    padding:6px 12px;
    border-radius:5px;
    font-weight:bold;
}

.status-belum{
    background:#eab308;
    color:#000;
    padding:6px 12px;
    border-radius:5px;
    font-weight:bold;
}

/* HEADER */
.tabel-kecil th{
    background:linear-gradient(135deg,#3b82f6,#60a5fa);
    color:#ffffff;
    padding:8px;
    text-align:center;
    font-weight:bold;
    letter-spacing:0.5px;
    border:1px solid #93c5fd;
}

/* CELL */
.tabel-kecil td{
    border:1px solid #93c5fd;
    padding:6px;
    transition:0.3s;
}

/* ZEBRA */
.tabel-kecil tr:nth-child(even){
    background:#f1f5f9;
}

/* HOVER */
.tabel-kecil tr:hover{
    background:#dbeafe;
}

/* LINK DOKTER */
.tabel-kecil a{
    color:#2563eb;
    text-decoration:none;
    font-weight:bold;
}

.tabel-kecil a:hover{
    color:#1d4ed8;
    text-decoration:underline;
}

/* HEADER ATAS */
.head1 th{
    background:linear-gradient(135deg,#2563eb,#3b82f6);
}

/* RESPONSIVE */
@media screen and (min-width:1200px){
    .tabel-kecil{
        font-size:1.1em;
    }
}

/* ===================================== */
/* KEDIP MERAH UNTUK JAM MULAI & SELESAI */
/* ===================================== */

.blink-time{
    animation:blinkRed 1s infinite;
    font-weight:bold;
}

@keyframes blinkRed{
    0%,100%{
        background:#ffffff;
        color:#000000;
    }
    50%{
        background:#ff0000;
        color:#ffffff;
    }
}

</style>
</head>

<body>

<table width="100%" bgcolor="#000000" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr class="head5">
        <td width="100%">
            <div align="center"></div>
        </td>
    </tr>
</table>

<table class='tabel-kecil' bgcolor='#000000' border='0' align='center' cellpadding='0' cellspacing='0'>
<tr class='head1'>
    <th width='28%'>NAMA DOKTER</th>
    <th width='25%'>POLIKLINIK</th>
    <th width='12%'>MULAI</th>
    <th width='12%'>SELESAI</th>
    <th width='13%'>STATUS</th>
    <th width='10%'>PASIEN</th>
</tr>

<?php

$hari = getOne("select DAYNAME(current_date())");

switch($hari){
    case "Sunday":
        $namahari="AKHAD";
        break;
    case "Monday":
        $namahari="SENIN";
        break;
    case "Tuesday":
        $namahari="SELASA";
        break;
    case "Wednesday":
        $namahari="RABU";
        break;
    case "Thursday":
        $namahari="KAMIS";
        break;
    case "Friday":
        $namahari="JUMAT";
        break;
    case "Saturday":
        $namahari="SABTU";
        break;
}

$_sql="
SELECT
    dokter.nm_dokter,
    poliklinik.nm_poli,
    jadwal.jam_mulai,
    jadwal.jam_selesai,
    poliklinik.kd_poli,
    dokter.kd_dokter
FROM jadwal
INNER JOIN dokter
    ON dokter.kd_dokter=jadwal.kd_dokter
INNER JOIN poliklinik
    ON poliklinik.kd_poli=jadwal.kd_poli
WHERE jadwal.hari_kerja='$namahari'
ORDER BY jadwal.jam_mulai
";

$hasil = bukaquery($_sql);

while($data=mysqli_fetch_array($hasil)){

    $jam_sekarang = date("H:i:s");

    $mulai   = strtotime($data['jam_mulai']);
    $selesai = strtotime($data['jam_selesai']);
    $now     = strtotime($jam_sekarang);

    if($now < $mulai){
        $status = "<span class='status-belum'>BELUM</span>";
    }elseif($now >= $mulai && $now <= $selesai){
        $status = "<span class='status-buka'>BUKA</span>";
    }else{
        $status = "<span class='status-tutup'>TUTUP</span>";
    }

    $pasien = getOne("
        SELECT COUNT(*)
        FROM reg_periksa
        WHERE kd_poli='".$data['kd_poli']."'
        AND kd_dokter='".$data['kd_dokter']."'
        AND tgl_registrasi='".date('Y-m-d')."'
    ");

    echo "
    <tr class='isi7'>

        <td align='left'>
            <a href='antrian-tv.php?iyem=".
            encrypt_decrypt(
            "{\"kd_poli\":\"".$data['kd_poli']."\",\"kd_dokter\":\"".$data['kd_dokter']."\"}",
            "e"
            ).
            "'>".$data['nm_dokter']."</a>
        </td>

        <td align='center'>
            ".$data['nm_poli']."
        </td>

        <td align='center' class='blink-time'>
            ".$data['jam_mulai']."
        </td>

        <td align='center' class='blink-time'>
            ".$data['jam_selesai']."
        </td>

        <td align='center'>
            ".$status."
        </td>

        <td align='center'>
            ".$pasien."
        </td>

    </tr>";
}
?>

</table>

<table width="100%" bgcolor="#FFFFFF" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr class="head5">
        <td width="100%">
            <div align="center"></div>
        </td>
    </tr>
</table>

</body>
</html>
