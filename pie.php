<?php

$host   = "10.10.20.250";
$user   = "root";
$pass   = "";
$dbname = "sikdraisyah";

$conn = mysqli_connect($host,$user,$pass,$dbname);

if(!$conn){
    die("Koneksi gagal");
}

/*
|--------------------------------------------------------------------------
| FILTER BULAN & TANGGAL
|--------------------------------------------------------------------------
*/

$bulan = $_GET['bulan'] ?? '';

$tgl1 = $_GET['tgl1'] ?? '';
$tgl2 = $_GET['tgl2'] ?? '';

/* Jika pilih bulan */
if($bulan != ''){

    $tgl1 = $bulan . '-01';
    $tgl2 = date('Y-m-t', strtotime($tgl1));

}

/* Default tanggal */
if($tgl1 == '' || $tgl2 == ''){

    $tgl1 = date('Y-m-01');
    $tgl2 = date('Y-m-d');

}

/*
|--------------------------------------------------------------------------
| QUERY DATA
|--------------------------------------------------------------------------
*/

$query = mysqli_query($conn, "

    SELECT 
        pasien.jk,
        COUNT(reg_periksa.no_rawat) AS jumlah

    FROM reg_periksa

    INNER JOIN pasien 
        ON pasien.no_rkm_medis = reg_periksa.no_rkm_medis

    WHERE reg_periksa.status_lanjut='Ralan'

    AND reg_periksa.tgl_registrasi 
        BETWEEN '$tgl1' AND '$tgl2'

    GROUP BY pasien.jk

");

$labels = [];
$data   = [];
$total  = 0;

while($row = mysqli_fetch_assoc($query)){

    if($row['jk'] == 'L'){
        $labels[] = 'Laki-Laki';
    }else{
        $labels[] = 'Perempuan';
    }

    $data[] = $row['jumlah'];

    $total += $row['jumlah'];
}

?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>myROBOT-V80</title>

<link href="http://10.10.20.250/dashboard/APPS-ROBOT/BUILDING APLIKASI/@API-GITHUB-V80/ROBOT-GITHUB/ROBOTV80.png" rel="icon" type="image/png" />

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:Arial, Helvetica, sans-serif;
    background:#f1f5f9;
    padding:40px;
}

.container{
    width:100%;
    max-width:1000px;
    margin:auto;
}

.card{
    background:white;
    border-radius:20px;
    padding:30px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

.title{
    font-size:30px;
    font-weight:bold;
    margin-bottom:10px;
    color:#1e293b;
    text-align:center;
}

.subtitle{
    text-align:center;
    color:#64748b;
    margin-bottom:30px;
}

.filter-box{
    display:flex;
    gap:20px;
    align-items:end;
    justify-content:center;
    margin-bottom:35px;
    flex-wrap:wrap;
}

.input-group{
    display:flex;
    flex-direction:column;
}

label{
    margin-bottom:8px;
    font-size:14px;
    color:#475569;
    font-weight:bold;
}

input[type=date],
input[type=month]{

    padding:12px;
    border:1px solid #cbd5e1;
    border-radius:10px;
    outline:none;
    min-width:220px;
    font-size:15px;

}

input[type=date]:focus,
input[type=month]:focus{
    border-color:#2563eb;
}

.button-group{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
}

button{
    padding:12px 25px;
    border:none;
    border-radius:10px;
    cursor:pointer;
    font-size:15px;
    transition:0.3s;
    color:white;
}

.btn-filter{
    background:#2563eb;
}

.btn-filter:hover{
    background:#1d4ed8;
}

.btn-print{
    background:#ef4444;
}

.btn-print:hover{
    background:#dc2626;
}

.chart-container{
    width:100%;
    max-width:500px;
    margin:20px auto;
}

.info-box{
    margin-top:35px;
    display:flex;
    justify-content:center;
    gap:20px;
    flex-wrap:wrap;
}

.info-item{
    background:#f8fafc;
    padding:20px;
    border-radius:15px;
    text-align:center;
    min-width:180px;
    box-shadow:0 2px 5px rgba(0,0,0,0.05);
}

.info-item h3{
    font-size:16px;
    margin-bottom:10px;
    color:#334155;
}

.info-item p{
    font-size:28px;
    font-weight:bold;
    color:#2563eb;
}

/* TOTAL DI SAMPING */

.total-side{
    background:#2563eb;
}

.total-side h3{
    color:white;
}

.total-side p{
    color:white;
}

.footer{
    text-align:center;
    margin-top:35px;
    color:#64748b;
    font-size:14px;
}
/* BOTTOM NAV */
.bottom-nav {
    position: fixed;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    max-width: 420px;
    width: 100%;
    background: #fff;
    display: flex;
    justify-content: space-around;
    padding: 10px 0;
    border-top-left-radius: 25px;
    border-top-right-radius: 25px;
    box-shadow: 0 -5px 20px rgba(0,0,0,0.1);
}
.nav-item {
    font-size: 12px;
    text-align: center;
}
.home-btn {
    background: #4e8cff;
    width: 55px;
    height: 55px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    margin-top: -30px;
    font-size: 22px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

/* RESPONSIVE */
@media (max-width: 360px) {
    .menu-item { width: 60px; height: 60px; }
    .send-item img { width: 50px; height: 50px; }
    .card { margin: -30px 10px 20px; padding: 15px; }
}



@media(max-width:600px){

    body{
        padding:15px;
    }

    .filter-box{
        flex-direction:column;
        align-items:stretch;
    }

    .button-group{
        width:100%;
    }

    button{
        width:100%;
    }

}

</style>

</head>

<body>

<div class="container">

    <div class="card" id="printArea">

        <div class="title">
            Grafik Pasien Rawat Jalan (RALAN)
        </div>

        <div class="subtitle">
            Berdasarkan Jenis Kelamin
        </div>

        <form method="GET">

            <div class="filter-box">

                <!-- FILTER BULAN -->

                <div class="input-group">

                    <label>Filter Bulan</label>

                    <input 
                        type="month" 
                        name="bulan"
                        value="<?= $bulan ?>"
                    >

                </div>

                <!-- FILTER TANGGAL -->

                <div class="input-group">

                    <label>Tanggal Awal</label>

                    <input 
                        type="date" 
                        name="tgl1"
                        value="<?= $tgl1 ?>"
                    >

                </div>

                <div class="input-group">

                    <label>Tanggal Akhir</label>

                    <input 
                        type="date" 
                        name="tgl2"
                        value="<?= $tgl2 ?>"
                    >

                </div>

                <!-- BUTTON -->

                <div class="button-group">

                    <button 
                        type="submit"
                        class="btn-filter"
                    >
                        Filter Data
                    </button>

                    <button 
                        type="button"
                        class="btn-print"
                        onclick="downloadPDF()"
                    >
                        CETAK PDF
                    </button>
<button onclick="window.open('pie-lapbul.php', '_blank')" class="btn-filter">
    LAPBUL
</button>   
<button onclick="window.open('pie-ranap.php', '')" class="btn-filter">
    RAWAT INAP
</button>            </div>

            </div>

        </form>



        <!-- CHART -->

        <div class="chart-container">
            <canvas id="pieChart"></canvas>
        </div>

        <!-- INFO BOX -->

        <div class="info-box">

            <?php foreach($labels as $i => $lbl){ ?>

            <div class="info-item">

                <h3><?= $lbl ?></h3>

                <p><?= $data[$i] ?></p>

            </div>

            <?php } ?>

            <!-- TOTAL -->

            <div class="info-item total-side">

                <h3>Total Pasien</h3>

                <p><?= $total ?></p>

            </div>

        </div>

        <div class="footer">

            Periode :
            <b><?= $tgl1 ?></b>
            s/d
            <b><?= $tgl2 ?></b>

        </div>

    </div>

</div>

<script>

const ctx = document.getElementById('pieChart');

new Chart(ctx, {

    type: 'pie',

    data: {

        labels: <?= json_encode($labels) ?>,

        datasets: [{

            label: 'Jumlah Pasien',

            data: <?= json_encode($data) ?>,

            backgroundColor: [
                '#3b82f6',
                '#ec4899'
            ],

            borderColor: [
                '#ffffff',
                '#ffffff'
            ],

            borderWidth: 3

        }]
    },

    options: {

        responsive:true,

        plugins: {

            legend: {

                position:'bottom',

                labels:{

                    padding:20,

                    font:{
                        size:14
                    }

                }

            }

        }

    }

});

/*
|--------------------------------------------------------------------------
| DOWNLOAD PDF
|--------------------------------------------------------------------------
*/

function downloadPDF(){

    const element = document.getElementById('printArea');

    const opt = {

        margin: 0.5,

        filename: 'laporan-ralan-<?= date("m-Y", strtotime($tgl1)) ?>.pdf',

        image: {
            type: 'jpeg',
            quality: 1
        },

        html2canvas: {
            scale: 2
        },

        jsPDF: {
            unit: 'in',
            format: 'a4',
            orientation: 'portrait'
        }

    };

    html2pdf().set(opt).from(element).save();
}

</script>




<p>   <p>&nbsp;</p>
<p>   <p>&nbsp;</p>
<p>   <p>&nbsp;</p>
<p>   <p>&nbsp;</p>

<p>   <p>&nbsp;</p>
<p>   <p>&nbsp;</p>


<!-- BOTTOM NAV -->
<div class="bottom-nav">
   <a href="http://10.10.20.250/dashboard/ROBOT-DASHBOARD/"  class="nav-item"><img src="http://10.10.20.250/dashboard/APPS-ROBOT/ANDROID-NEW/BERANDA2.png" alt="" class="profile-pic" height="70" width="50" ><br> BERANDA </a>

 <a href="http://10.10.20.250/dashboard/APPS-ROBOT/GITHUB/SOAP/ROBOT-V80/rawat_jalan/manage?t=d9d3d5af7281" id="vib1" class="nav-item">
  <img src="http://10.10.20.250/dashboard/APPS-ROBOT/GITHUB/SOAP.png"
       alt=""
       id="goyang"
       class="profile-pic"
       height="70"
       width="50">
  <br> SOAP
</a>

<a href="http://10.10.20.250/dashboard/APPS-ROBOT/GITHUB/index-dashboard2.php" class="home-btn">
    <img src="http://10.10.20.250/dashboard/APPS-ROBOT/ANDROID-NEW/APPS2.png"
         alt=""
         class="profile-pic bounce"
         height="100"
         width="100">
</a>

<img src="https://i.imgur.com/gYHDr9S.gif"
     alt=""
     class="profile-pic"
     height="18"
     width="18">

<style>
.bounce {
    animation: loncat 0.8s infinite;
    display: inline-block;
}

@keyframes loncat {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-20px);
    }
}
</style>




<a href="<?= $user['gaji']; ?>" class="nav-item" id="vib1">
  <img src="http://10.10.20.250/dashboard/APPS-ROBOT/ANDROID-NEW/GAJI.png"
       alt=""
       id="goyang"
       class="profile-pic"
       height="70"
       width="50">
  <br> SLIP GAJI
</a>

   


     <a href="http://10.10.20.250/dashboard/APPS-ROBOT/ANDROID-NEW/logout2.php" class="nav-item"><img src="http://10.10.20.250/dashboard/APPS-ROBOT/ANDROID-NEW/LOGOUT.png" alt="" class="profile-pic" height="70" width="50" ><br> LOGOUT</a>
</div>


</body>
</html>
