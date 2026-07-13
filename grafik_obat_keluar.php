<?php
include "koneksi2.php";

/* ==========================
   GRAFIK PER OBAT
========================== */
$q_obat = mysqli_query($koneksi,"
SELECT
    nama_brng,
    SUM(jumlah_keluar) AS total
FROM robotv80_obat_keluar
GROUP BY nama_brng
ORDER BY total DESC
LIMIT 10
");

$obat = [];
$total_obat = [];

while($d=mysqli_fetch_assoc($q_obat)){
    $obat[] = $d['nama_brng'];
    $total_obat[] = $d['total'];
}


/* ==========================
   GRAFIK PER POLI
========================== */
$q_poli = mysqli_query($koneksi,"
SELECT
    tujuan,
    SUM(jumlah_keluar) AS total
FROM robotv80_obat_keluar
GROUP BY tujuan
ORDER BY total DESC
");

$poli = [];
$total_poli = [];

while($d=mysqli_fetch_assoc($q_poli)){
    $poli[] = $d['tujuan'];
    $total_poli[] = $d['total'];
}


/* ==========================
   GRAFIK PER BULAN
========================== */
$q_bulan = mysqli_query($koneksi,"
SELECT
    DATE_FORMAT(tanggal,'%Y-%m') AS bulan,
    SUM(jumlah_keluar) AS total
FROM robotv80_obat_keluar
GROUP BY DATE_FORMAT(tanggal,'%Y-%m')
ORDER BY bulan
");

$bulan = [];
$total_bulan = [];

while($d=mysqli_fetch_assoc($q_bulan)){
    $bulan[] = $d['bulan'];
    $total_bulan[] = $d['total'];
}


/* ==========================
   CARD SUMMARY
========================== */

$total_transaksi = mysqli_fetch_assoc(mysqli_query($koneksi,"
SELECT COUNT(*) total
FROM robotv80_obat_keluar
"))['total'];

$total_item = mysqli_fetch_assoc(mysqli_query($koneksi,"
SELECT SUM(jumlah_keluar) total
FROM robotv80_obat_keluar
"))['total'];

?>

<!DOCTYPE html>
<html>
<head>

<title>myROBOT-V80</title>
  <link href="http://10.10.20.250/dashboard/APPS-ROBOT/BUILDING APLIKASI/@API-GITHUB-V80/ROBOT-GITHUB/ROBOTV80.png" rel="icon" type="image/png" />
<meta name="viewport" content="width=device-width, initial-scale=1">




<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>

body{
    margin:0;
    padding:20px;
    font-family:Arial;
    background:#f4f6f9;
}

.container{
    width:95%;
    margin:auto;
}

.card{
    background:white;
    border-radius:15px;
    padding:20px;
    margin-bottom:20px;
    box-shadow:0 0 10px rgba(0,0,0,.1);
}

.summary{
    display:flex;
    gap:20px;
    flex-wrap:wrap;
}

.box{
    flex:1;
    min-width:200px;
    background:linear-gradient(45deg,#0d6efd,#00c6ff);
    color:white;
    padding:20px;
    border-radius:15px;
    text-align:center;
}

.box h1{
    margin:0;
    font-size:40px;
}

.box p{
    margin:10px 0 0;
}

.btn{
    background:#198754;
    color:white;
    padding:12px 20px;
    border-radius:10px;
    text-decoration:none;
    display:inline-block;
    margin-bottom:20px;
}

.btn:hover{
    background:#157347;
}

canvas{
    width:100% !important;
    max-height:450px;
}

@media(max-width:768px){

    .summary{
        display:block;
    }

    .box{
        margin-bottom:15px;
    }

}

</style>

</head>

<body>

<div class="container">

<a href="cek-so.php" class="btn">
← Kembali ke Data Obat Keluar
</a>

<div class="summary">

<div class="box">
<h1><?= $total_transaksi ?></h1>
<p>Total Transaksi</p>
</div>

<div class="box">
<h1><?= $total_item ?: 0 ?></h1>
<p>Total Obat Keluar</p>
</div>

</div>


<div class="card">
<h2>Grafik 10 Obat Paling Banyak Keluar</h2>
<canvas id="grafikObat"></canvas>
</div>

<div class="card">
<h2>Grafik Obat Keluar Per Poli</h2>
<canvas id="grafikPoli"></canvas>
</div>

<div class="card">
<h2>Grafik Obat Keluar Per Bulan</h2>
<canvas id="grafikBulan"></canvas>
</div>

</div>


<script>

/* ==========================
   GRAFIK OBAT
========================== */
new Chart(
document.getElementById('grafikObat'),
{
    type:'bar',
    data:{
        labels:<?= json_encode($obat) ?>,
        datasets:[{
            label:'Jumlah Obat Keluar',
            data:<?= json_encode($total_obat) ?>,
            backgroundColor:[
                '#0d6efd',
                '#198754',
                '#ffc107',
                '#dc3545',
                '#6f42c1',
                '#20c997',
                '#fd7e14',
                '#6610f2',
                '#17a2b8',
                '#343a40'
            ]
        }]
    },
    options:{
        responsive:true,
        scales:{
            y:{
                beginAtZero:true
            }
        }
    }
});


/* ==========================
   GRAFIK POLI
========================== */
new Chart(
document.getElementById('grafikPoli'),
{
    type:'pie',
    data:{
        labels:<?= json_encode($poli) ?>,
        datasets:[{
            data:<?= json_encode($total_poli) ?>,
            backgroundColor:[
                '#0d6efd',
                '#198754',
                '#ffc107',
                '#dc3545',
                '#6f42c1',
                '#20c997',
                '#fd7e14',
                '#6610f2',
                '#17a2b8',
                '#343a40'
            ]
        }]
    },
    options:{
        responsive:true
    }
});


/* ==========================
   GRAFIK BULAN
========================== */
new Chart(
document.getElementById('grafikBulan'),
{
    type:'line',
    data:{
        labels:<?= json_encode($bulan) ?>,
        datasets:[{
            label:'Jumlah Obat Keluar',
            data:<?= json_encode($total_bulan) ?>,
            fill:false,
            borderColor:'#0d6efd',
            tension:0.3
        }]
    },
    options:{
        responsive:true,
        scales:{
            y:{
                beginAtZero:true
            }
        }
    }
});

</script>

</body>
</html>
