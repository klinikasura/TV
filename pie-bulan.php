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
| FILTER BULAN
|--------------------------------------------------------------------------
*/

$bulan = $_GET['bulan'] ?? date('Y-m');

$tgl1 = $bulan . "-01";
$tgl2 = date("Y-m-t", strtotime($tgl1));

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

input[type=month]{
    padding:12px;
    border:1px solid #cbd5e1;
    border-radius:10px;
    outline:none;
    min-width:220px;
    font-size:15px;
}

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

.total-box{
    margin-top:25px;
    text-align:center;
    background:#2563eb;
    color:white;
    padding:20px;
    border-radius:15px;
}

.total-box h2{
    font-size:18px;
    margin-bottom:10px;
}

.total-box p{
    font-size:35px;
    font-weight:bold;
}

.footer{
    text-align:center;
    margin-top:35px;
    color:#64748b;
    font-size:14px;
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

                <div class="input-group">

                    <label>Pilih Bulan</label>

                    <input 
                        type="month" 
                        name="bulan"
                        value="<?= $bulan ?>"
                    >

                </div>

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

                </div>

            </div>

        </form>

        <div class="chart-container">
            <canvas id="pieChart"></canvas>
        </div>

        <div class="info-box">

            <?php foreach($labels as $i => $lbl){ ?>

            <div class="info-item">

                <h3><?= $lbl ?></h3>

                <p><?= $data[$i] ?></p>

            </div>

            <?php } ?>

        </div>

        <div class="total-box">

            <h2>Total Pasien Ralan</h2>

            <p><?= $total ?></p>

        </div>

        <div class="footer">

            Periode :
            <b><?= date('F Y', strtotime($tgl1)) ?></b>

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

        filename: 'laporan-ralan-<?= $bulan ?>.pdf',

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

</body>
</html>
