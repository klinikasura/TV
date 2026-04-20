<?php
session_start();
require_once('conf/conf.php');

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP/1.0

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
body {
    margin:0;
    font-family: 'Segoe UI', Tahoma, sans-serif;
    background: linear-gradient(135deg, #dbeafe, #eff6ff);
}

/* TABEL UTAMA */
.tabel-kecil {
    font-size: 0.9em;
    border-collapse: collapse;
    width: 98%;
    margin: 10px auto;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    background: #ffffff;
}

/* HEADER */
.tabel-kecil th {
    background: linear-gradient(135deg, #3b82f6, #60a5fa);
    color: #ffffff;
    padding: 8px;
    text-align: center;
    font-weight: bold;
    letter-spacing: 0.5px;
    border: 1px solid #93c5fd; /* tambahan */
}

/* CELL */
.tabel-kecil td {
    border: 1px solid #93c5fd; /* ubah jadi full border biru muda */
    padding: 6px;
    transition: 0.3s;
}

/* ZEBRA STRIPE */
.tabel-kecil tr:nth-child(even) {
    background: #f1f5f9;
}

/* HOVER */
.tabel-kecil tr:hover {
    background: #dbeafe;
    transform: scale(1.01);
}

/* LINK DOKTER */
.tabel-kecil a {
    color: #2563eb;
    text-decoration: none;
    font-weight: bold;
}

.tabel-kecil a:hover {
    color: #1d4ed8;
    text-decoration: underline;
}

/* TEXT STYLE */
.tabel-kecil td font {
    color: #111827 !important;
}

/* HEADER TABLE HITAM ATAS */
.head1 th {
    background: linear-gradient(135deg, #2563eb, #3b82f6);
}

/* RESPONSIVE TV */
@media screen and (min-width: 1200px) {
    .tabel-kecil {
        font-size: 1.1em;
    }
}

/* SMOOTH */
* {
    transition: all 0.2s ease-in-out;
}

</style>
</head>
<body>
<table width='100%' bgcolor='#000000' border='0' align='center' cellpadding='0' cellspacing='0'>
    <tr class='head5'>
        <td width='100%'><div align='center'></div></td>
    </tr>
</table>

<table class='tabel-kecil' bgcolor='#000000' border='0' align='center' cellpadding='0' cellspacing='0'>
    <tr class='head1'>
        <th width='30%'><div align='center'><font color='#FFFFFF' face='Tahoma'><b>NAMA DOKTER</b></font></div></th>
        <th width='30%'><div align='center'><font color='#FFFFFF' face='Tahoma'><b>POLIKLINIK</b></font></div></th>
        <th width='15%'><div align='center'><font color='#FFFFFF' face='Tahoma'><b>MULAI</b></font></div></th>
        <th width='15%'><div align='center'><font color='#FFFFFF' face='Tahoma'><b>SELESAI</b></font></div></th>
        <th width='10%'><div align='center'><font color='#FFFFFF' face='Tahoma'><b>PASIEN</b></font></div></th>
    </tr>
<?php
$hari = getOne("select DAYNAME(current_date())");
$namahari = "";
if ($hari == "Sunday") $namahari = "AKHAD";
elseif ($hari == "Monday") $namahari = "SENIN";
elseif ($hari == "Tuesday") $namahari = "SELASA";
elseif ($hari == "Wednesday") $namahari = "RABU";
elseif ($hari == "Thursday") $namahari = "KAMIS";
elseif ($hari == "Friday") $namahari = "JUMAT";
elseif ($hari == "Saturday") $namahari = "SABTU";

$_sql = "SELECT dokter.nm_dokter, poliklinik.nm_poli, jadwal.jam_mulai, jadwal.jam_selesai,
                poliklinik.kd_poli, dokter.kd_dokter
         FROM jadwal
         INNER JOIN dokter   ON dokter.kd_dokter = jadwal.kd_dokter
         INNER JOIN poliklinik ON poliklinik.kd_poli = jadwal.kd_poli
         WHERE jadwal.hari_kerja = '$namahari'";

$hasil = bukaquery($_sql);
while ($data = mysqli_fetch_array($hasil)) {
    echo "<tr class='isi7'>";
    echo "<td align='left'><font size='2' color='#BB00BB' face='Tahoma' style='font-weight:bold;'><a href='antrian-tv.php?iyem=" .
         encrypt_decrypt("{\"kd_poli\":\"".$data['kd_poli']."\",\"kd_dokter\":\"".$data['kd_dokter']."\"}","e") .
         "'>".$data['nm_dokter']."</a></font></td>";
    echo "<td align='center'><font size='2' color='#000000' face='Tahoma' style='font-weight:bold;'>".$data['nm_poli']."</font></td>";
    echo "<td align='center'><font size='2' color='#000000' face='Tahoma' style='font-weight:bold;'>".$data['jam_mulai']."</font></td>";
    echo "<td align='center'><font size='2' color='#000000' face='Tahoma' style='font-weight:bold;'>".$data['jam_selesai']."</font></td>";
    echo "<td align='center'><font size='2' color='#000000' face='Tahoma' style='font-weight:bold;'>".
         getOne("select count(*) from reg_periksa where kd_poli='".$data['kd_poli']."' and kd_dokter='".$data['kd_dokter']."' and tgl_registrasi='".date("Y-m-d", $tanggal)."'").
         "</font></td>";
    echo "</tr>";
}
?>
</table>

<table width='100%' bgcolor='FFFFFF' border='0' align='center' cellpadding='0' cellspacing='0'>
    <tr class='head5'>
        <td width='100%'><div align='center'></div></td>
    </tr>
</table>
</body>

