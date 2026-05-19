<?php
require_once('conf/conf.php');

date_default_timezone_set("Asia/Bangkok");

/*
|--------------------------------------------------------------------------
| KONEKSI DATABASE
|--------------------------------------------------------------------------
*/

$host     = "10.10.20.250";
$user     = "root";
$password = "";
$database = "sikdraisyah";

$conn = new mysqli($host,$user,$password,$database);

if($conn->connect_error){
    die("Koneksi gagal : ".$conn->connect_error);
}

/*
|--------------------------------------------------------------------------
| TANGGAL
|--------------------------------------------------------------------------
*/

$tgl = date('Y-m-d');

/*
|--------------------------------------------------------------------------
| TOTAL CARD
|--------------------------------------------------------------------------
*/

$q1 = $conn->query("
SELECT COUNT(no_rawat) AS total
FROM reg_periksa
WHERE tgl_registrasi='$tgl'
");

$daftar = $q1->fetch_assoc()['total'];

$q2 = $conn->query("
SELECT COUNT(no_rawat) AS total
FROM reg_periksa
WHERE tgl_registrasi='$tgl'
AND status_bayar='Sudah Bayar'
");

$bayar = $q2->fetch_assoc()['total'];

$q3 = $conn->query("
SELECT COUNT(no_rawat) AS total
FROM resep_obat
WHERE tgl_perawatan='$tgl'
");

$farmasi = $q3->fetch_assoc()['total'];

$q4 = $conn->query("
SELECT COUNT(no_rawat) AS total
FROM periksa_lab
WHERE tgl_periksa='$tgl'
");

$lab = $q4->fetch_assoc()['total'];

$q5 = $conn->query("
SELECT COUNT(*) AS total
FROM kamar_inap
WHERE stts_pulang='-'
");

$ranap = $q5->fetch_assoc()['total'];

/*
|--------------------------------------------------------------------------
| TABEL RAWAT JALAN
|--------------------------------------------------------------------------
*/

$sql = "

SELECT

d.nm_dokter,
pl.nm_poli,
rp.status_lanjut,

COUNT(*) AS total_pasien,

SUM(
CASE
WHEN rp.status_bayar='Belum Bayar'
THEN 1 ELSE 0
END
) AS belum_bayar,

SUM(
CASE
WHEN rp.status_bayar='Sudah Bayar'
THEN 1 ELSE 0
END
) AS sudah_bayar

FROM reg_periksa rp

INNER JOIN dokter d
ON rp.kd_dokter=d.kd_dokter

INNER JOIN poliklinik pl
ON rp.kd_poli=pl.kd_poli

WHERE DATE(rp.tgl_registrasi)='$tgl'

GROUP BY rp.kd_dokter,rp.kd_poli,rp.status_lanjut

ORDER BY belum_bayar DESC

";

$result = $conn->query($sql);

/*
|--------------------------------------------------------------------------
| PASIEN TERBARU
|--------------------------------------------------------------------------
*/

$sql_pasien = "

SELECT

rp.no_rawat,
rp.jam_reg,

p.nm_pasien,
pl.nm_poli,
d.nm_dokter,

rp.status_bayar

FROM reg_periksa rp

INNER JOIN pasien p
ON rp.no_rkm_medis=p.no_rkm_medis

INNER JOIN dokter d
ON rp.kd_dokter=d.kd_dokter

INNER JOIN poliklinik pl
ON rp.kd_poli=pl.kd_poli

WHERE DATE(rp.tgl_registrasi)='$tgl'

AND rp.status_bayar='Sudah Bayar'

AND rp.status_lanjut='Ralan'

ORDER BY rp.jam_reg DESC

LIMIT 1

";

$pasienBaru = $conn->query($sql_pasien)->fetch_assoc();

/*
|--------------------------------------------------------------------------
| RAWAT INAP
|--------------------------------------------------------------------------
*/

$sql_ranap = "

SELECT 

ki.no_rawat,
b.nm_bangsal,
k.kelas,

rp.tgl_registrasi,

p.nm_pasien,

IFNULL(
ki.diagnosa_awal,
'-'
) as diagnosa

FROM kamar_inap ki

JOIN kamar k
ON ki.kd_kamar = k.kd_kamar

JOIN bangsal b
ON k.kd_bangsal = b.kd_bangsal

JOIN reg_periksa rp
ON ki.no_rawat = rp.no_rawat

JOIN pasien p
ON rp.no_rkm_medis = p.no_rkm_medis

WHERE k.status = 'ISI'
AND ki.stts_pulang = '-'

ORDER BY b.nm_bangsal ASC

";

$result_ranap = $conn->query($sql_ranap);

$dataRanap = [];

while($r = $result_ranap->fetch_assoc()){
    $dataRanap[] = $r;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>myROBOT-V80</title>

<meta http-equiv="refresh" content="60">

<link href="http://10.10.20.250/dashboard/APPS-ROBOT/BUILDING%20APLIKASI/@API-GITHUB-V80/ROBOT-GITHUB/ROBOTV80.png"
rel="icon"
type="image/png" />

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
}

body{

font-family:Arial,sans-serif;

background:
linear-gradient(
135deg,
#0f172a,
#1e293b,
#334155
);

color:white;

padding:15px;
}

/*
|--------------------------------------------------------------------------
| HEADER
|--------------------------------------------------------------------------
*/

.header{

text-align:center;

margin-bottom:20px;
}

.header h1{

font-size:40px;

color:#38bdf8;

margin-bottom:10px;
}

.header p{

color:#cbd5e1;
}

/*
|--------------------------------------------------------------------------
| CARD
|--------------------------------------------------------------------------
*/

.cards{

display:grid;

grid-template-columns:
repeat(auto-fit,minmax(180px,1fr));

gap:15px;

margin-bottom:20px;
}

.card{

background:
rgba(255,255,255,0.08);

backdrop-filter:blur(10px);

padding:20px;

border-radius:20px;

text-align:center;
}

.card h3{

margin-bottom:10px;
}

.card .angka{

font-size:40px;

font-weight:bold;

color:#38bdf8;
}

/*
|--------------------------------------------------------------------------
| RUNNING TEXT
|--------------------------------------------------------------------------
*/

.running-box{

background:#0f172a;

border-radius:15px;

overflow:hidden;

border:2px solid #38bdf8;

margin-bottom:20px;
}

.running-text{

padding:15px;

white-space:nowrap;

animation:jalan 20s linear infinite;

font-size:18px;

color:#facc15;

font-weight:bold;
}

@keyframes jalan{

0%{
transform:translateX(100%);
}

100%{
transform:translateX(-100%);
}

}

/*
|--------------------------------------------------------------------------
| CHART
|--------------------------------------------------------------------------
*/

.charts{

display:grid;

grid-template-columns:
repeat(auto-fit,minmax(280px,1fr));

gap:15px;

margin-bottom:20px;
}

.chart-box{

background:
rgba(255,255,255,0.08);

padding:15px;

border-radius:20px;

backdrop-filter:blur(10px);
}

.chart-box h3{

text-align:center;

margin-bottom:10px;

color:#38bdf8;
}

canvas{

width:100% !important;

height:280px !important;
}

/*
|--------------------------------------------------------------------------
| TABLE
|--------------------------------------------------------------------------
*/

.table-box{

background:
rgba(255,255,255,0.08);

border-radius:20px;

padding:20px;

overflow:auto;

margin-bottom:20px;
}

table{

width:100%;

border-collapse:collapse;
}

table th{

background:#38bdf8;

color:#0f172a;

padding:12px;
}

table td{

padding:10px;

text-align:center;

border-bottom:
1px solid rgba(255,255,255,0.1);
}

.badge-belum{

background:#ef4444;

padding:5px 10px;

border-radius:10px;

font-weight:bold;

animation:blink 1s infinite;
}

.badge-sudah{

background:#22c55e;

padding:5px 10px;

border-radius:10px;

font-weight:bold;
}

@keyframes blink{

0%{opacity:1;}
50%{opacity:0.4;}
100%{opacity:1;}

}

/*
|--------------------------------------------------------------------------
| RAWAT INAP
|--------------------------------------------------------------------------
*/

.ranap-box{

background:
rgba(255,255,255,0.08);

padding:20px;

border-radius:20px;
}

.ranap-title{

font-size:25px;

text-align:center;

margin-bottom:20px;

color:#38bdf8;
}

.ranap-item{

background:
rgba(255,255,255,0.05);

padding:15px;

border-radius:15px;

border-left:
5px solid #38bdf8;
}

/*
|--------------------------------------------------------------------------
| NOTIF
|--------------------------------------------------------------------------
*/

.notif{

position:fixed;

top:20px;

right:20px;

width:320px;

background:#22c55e;

padding:20px;

border-radius:20px;

display:none;

z-index:9999;
}

/*
|--------------------------------------------------------------------------
| FOOTER
|--------------------------------------------------------------------------
*/

.footer{

margin-top:20px;

text-align:center;

color:#cbd5e1;
}

/*
|--------------------------------------------------------------------------
| RESPONSIVE MOBILE
|--------------------------------------------------------------------------
*/

@media(max-width:768px){

.header h1{

font-size:28px;
}

.card .angka{

font-size:30px;
}

.running-text{

font-size:15px;
}

canvas{

height:220px !important;
}

.notif{

width:90%;

right:5%;
}

}

</style>

</head>

<body>

<!-- NOTIFIKASI -->

<div class="notif" id="notifBox">

<div style="font-size:20px;font-weight:bold;margin-bottom:10px;">
🔔 PASIEN SUDAH BAYAR
</div>

<div id="notifIsi"></div>

</div>

<!-- HEADER -->

<div class="header">

<h1>myROBOT-V80</h1>

<p>
Monitoring Pasien Rawat Jalan & Rawat Inap
</p>

</div>

<!-- CARDS -->

<div class="cards">

<div class="card">
<h3>Total Daftar</h3>
<div class="angka"><?= $daftar ?></div>
</div>

<div class="card">
<h3>Sudah Bayar</h3>
<div class="angka"><?= $bayar ?></div>
</div>

<div class="card">
<h3>Farmasi</h3>
<div class="angka"><?= $farmasi ?></div>
</div>

<div class="card">
<h3>Lab</h3>
<div class="angka"><?= $lab ?></div>
</div>

<div class="card">
<h3>Rawat Inap</h3>
<div class="angka"><?= $ranap ?></div>
</div>

</div>

<!-- RUNNING -->

<div class="running-box">

<div class="running-text">

🔥 LIVE —
DAFTAR : <?= $daftar ?> |
SUDAH BAYAR : <?= $bayar ?> |
FARMASI : <?= $farmasi ?> |
LAB : <?= $lab ?> |
RAWAT INAP : <?= $ranap ?>

</div>

</div>

<!-- CHART -->

<div class="charts">

<div class="chart-box">
<h3>Cara Bayar</h3>
<canvas id="chartCaraBayar"></canvas>
</div>

<div class="chart-box">
<h3>Status Bayar</h3>
<canvas id="chartBayar"></canvas>
</div>

<div class="chart-box">
<h3>Poliklinik</h3>
<canvas id="chartPoli"></canvas>
</div>

<div class="chart-box">
<h3>Dokter</h3>
<canvas id="chartDokter"></canvas>
</div>

<!-- TAMBAHAN BARU -->

<div class="chart-box">
<h3>Jenis Kelamin</h3>
<canvas id="chartJK"></canvas>
</div>


<div class="chart-box">
    <h3>Status Daftar</h3>
    <canvas id="chartDaftar"></canvas>
</div>


<div class="chart-box">
    <h3>Status Umur</h3>
    <canvas id="chartUmur"></canvas>
</div>

<div class="chart-box">
    <h3>Status Lanjut</h3>
    <canvas id="chartLanjut"></canvas>
</div>

</div>

<!-- TABLE -->

<div class="table-box">

<table>

<thead>

<tr>

<th>Dokter</th>
<th>Poli</th>
<th>Status</th>
<th>Total</th>
<th>Belum Bayar</th>
<th>Sudah Bayar</th>

</tr>

</thead>

<tbody>

<?php while($row = $result->fetch_assoc()){ ?>

<?php if($row['status_lanjut'] != 'Ralan'){ continue; } ?>

<tr>

<td><?= $row['nm_dokter'] ?></td>

<td><?= $row['nm_poli'] ?></td>

<td><?= $row['status_lanjut'] ?></td>

<td><?= $row['total_pasien'] ?></td>

<td>
<span class="badge-belum">
<?= $row['belum_bayar'] ?>
</span>
</td>

<td>
<span class="badge-sudah">
<?= $row['sudah_bayar'] ?>
</span>
</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

<!-- RAWAT INAP -->

<div class="ranap-box">

<div class="ranap-title">

🛏️ PASIEN RAWAT INAP

</div>

<div id="ranapText"></div>

</div>

<!-- FOOTER -->

<div class="footer">

Update :
<?= date('d-m-Y H:i:s') ?>

</div>

<script>

/*
|--------------------------------------------------------------------------
| PIE CHART
|--------------------------------------------------------------------------
*/

let charts = {};

function createChart(id, labels, data){

if(charts[id]){

charts[id].data.labels = labels;
charts[id].data.datasets[0].data = data;
charts[id].update();

return;

}

charts[id] = new Chart(

document.getElementById(id),

{

type:'pie',

data:{

labels:labels,

datasets:[{

data:data,

backgroundColor:[

'#6C5CE7',
'#00CEC9',
'#FF7675',
'#FDCB6E',
'#55EFC4',
'#FAB1A0',
'#81ECEC',
'#A29BFE'

]

}]

},

options:{

responsive:true,

maintainAspectRatio:false,

plugins:{

legend:{

labels:{

color:'white',

font:{
size:12
}

}

}

}

}

});

}

async function loadData(){

try{

const res = await fetch(
'MASTER/dashboard_today.php?t=' +
new Date().getTime()
);

const data = await res.json();

createChart(
'chartCaraBayar',
data.cara_bayar.map(x=>x.label),
data.cara_bayar.map(x=>x.jumlah)
);

createChart(
'chartBayar',
data.status_bayar.map(x=>x.label),
data.status_bayar.map(x=>x.jumlah)
);

createChart(
'chartPoli',
data.kd_poli.map(x=>x.label),
data.kd_poli.map(x=>x.jumlah)
);

createChart(
'chartDokter',
data.kd_dokter.map(x=>x.label),
data.kd_dokter.map(x=>x.jumlah)
);


createChart(
'chartJK',
data.jenis_kelamin.map(x=>x.label),
data.jenis_kelamin.map(x=>x.jumlah)
);

createChart(
    'chartDaftar',
    data.status_daftar.map(x => x.label),
    data.status_daftar.map(x => x.jumlah)
);


createChart(
    'chartUmur',
    data.status_umur.map(x => x.label),
    data.status_umur.map(x => x.jumlah)
);

createChart(
    'chartLanjut',
    data.status_lanjut.map(x => x.label),
    data.status_lanjut.map(x => x.jumlah)
);


}catch(err){

console.log(err);

}

}

loadData();

setInterval(loadData,5000);

/*
|--------------------------------------------------------------------------
| NOTIFIKASI SEKALI
|--------------------------------------------------------------------------
*/

let pasienBaru =
<?= json_encode($pasienBaru); ?>;

let notifBox =
document.getElementById('notifBox');

let notifIsi =
document.getElementById('notifIsi');

function suaraGoogle(teks){

let speech =
new SpeechSynthesisUtterance();

speech.lang='id-ID';

speech.text=teks;

window.speechSynthesis.speak(speech);

}

if(pasienBaru){

let notifID =

pasienBaru.no_rawat + '_' +
pasienBaru.jam_reg;

let sudahNotif =
localStorage.getItem('notif_pasien');

if(sudahNotif !== notifID){

notifBox.style.display='block';

notifIsi.innerHTML = `

👤 <b>${pasienBaru.nm_pasien}</b><br>

🏥 ${pasienBaru.nm_poli}<br>

👨‍⚕️ ${pasienBaru.nm_dokter}<br>

💳 ${pasienBaru.status_bayar}

`;

let suara = `

Pasien atas nama
${pasienBaru.nm_pasien},

telah melakukan pembayaran,

menuju poli
${pasienBaru.nm_poli}

`;

suaraGoogle(suara);

localStorage.setItem(
'notif_pasien',
notifID
);

setTimeout(()=>{

notifBox.style.display='none';

},10000);

}

}

/*
|--------------------------------------------------------------------------
| RAWAT INAP AUTO SLIDE
|--------------------------------------------------------------------------
*/

let dataRanap =
<?= json_encode($dataRanap); ?>;

let ranapText =
document.getElementById('ranapText');

let i = 0;

function tampilRanap(){

if(dataRanap.length==0){

ranapText.innerHTML=`

<div class="ranap-item">

Tidak Ada Pasien Rawat Inap

</div>

`;

return;

}

let p = dataRanap[i];

ranapText.innerHTML = `

<div class="ranap-item">

<div style="
font-size:20px;
font-weight:bold;
color:#22c55e;
margin-bottom:10px;
">

${p.nm_pasien}

</div>

No Rawat :
${p.no_rawat}<br><br>

Ruang :
${p.nm_bangsal} -
Kelas ${p.kelas}<br><br>

Tanggal :
${p.tgl_registrasi}

<div style="
color:#f87171;
margin-top:10px;
">

Diagnosa :
${p.diagnosa}

</div>

</div>

`;

i++;

if(i >= dataRanap.length){
i = 0;
}

}

tampilRanap();

setInterval(tampilRanap,5000);

</script>

</body>
</html>


