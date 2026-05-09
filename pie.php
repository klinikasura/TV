<?php

$host   = "10.10.20.250";
$user   = "root";
$pass   = "";
$dbname = "sikdraisyah";

$conn = mysqli_connect($host,$user,$pass,$dbname);

if(!$conn){
    die("Koneksi gagal");
}

$tgl1 = $_GET['tgl1'] ?? date('Y-m-01');
$tgl2 = $_GET['tgl2'] ?? date('Y-m-d');

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

while($row = mysqli_fetch_assoc($query)){

    if($row['jk'] == 'L'){
        $labels[] = 'Laki-Laki';
    }else{
        $labels[] = 'Perempuan';
    }

    $data[] = $row['jumlah'];
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
    max-width:900px;
    margin:auto;
}

.card{
    background:white;
    border-radius:15px;
    padding:30px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

.title{
    font-size:28px;
    font-weight:bold;
    margin-bottom:25px;
    color:#1e293b;
    text-align:center;
}

.filter-box{
    display:flex;
    gap:15px;
    align-items:center;
    justify-content:center;
    margin-bottom:30px;
    flex-wrap:wrap;
}

.input-group{
    display:flex;
    flex-direction:column;
}

label{
    margin-bottom:5px;
    font-size:14px;
    color:#475569;
}

input[type=date]{
    padding:10px;
    border:1px solid #cbd5e1;
    border-radius:8px;
    outline:none;
    min-width:180px;
}

input[type=date]:focus{
    border-color:#2563eb;
}

button{
    padding:12px 25px;
    border:none;
    background:#2563eb;
    color:white;
    border-radius:10px;
    cursor:pointer;
    font-size:15px;
    transition:0.3s;
    margin-top:20px;
}

button:hover{
    background:#1d4ed8;
}

.chart-container{
    width:100%;
    max-width:500px;
    margin:auto;
}

.info-box{
    margin-top:30px;
    display:flex;
    justify-content:center;
    gap:20px;
    flex-wrap:wrap;
}

.info-item{
    background:#f8fafc;
    padding:15px 25px;
    border-radius:10px;
    text-align:center;
    min-width:150px;
    box-shadow:0 2px 5px rgba(0,0,0,0.05);
}

.info-item h3{
    font-size:16px;
    margin-bottom:8px;
    color:#334155;
}

.info-item p{
    font-size:22px;
    font-weight:bold;
    color:#2563eb;
}

.footer{
    text-align:center;
    margin-top:30px;
    color:#64748b;
    font-size:14px;
}
.btn-link{
    padding:12px 25px;
    background:#10b981;
    color:white;
    text-decoration:none;
    border-radius:10px;
    font-size:15px;
    transition:0.3s;
    display:inline-block;
    margin-top:20px;
}

.btn-link:hover{
    background:#059669;
}

@media(max-width:600px){

    .filter-box{
        flex-direction:column;
        align-items:stretch;
    }

    button{
        width:100%;
    }

}


</style>

</head>
<body>

<div class="container">

    <div class="card">

        <div class="title">
            Grafik Pasien Rawat Jalan (Ralan)
        </div>

        <form method="GET">

            <div class="filter-box">

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

               <div style="display:flex; gap:10px; align-items:center;">

    <button type="submit">
        Filter Data
    </button>

     <button class="btn-print" onclick="window.print()">
        PRINTF
    </button>
</div>

        </form>

        <div class="chart-container">
            <canvas id="pieChart"></canvas>
        </div>

        <div class="info-box">

            <?php
            
            foreach($labels as $i => $lbl){

            ?>

            <div class="info-item">
                <h3><?= $lbl ?></h3>
                <p><?= $data[$i] ?></p>
            </div>

            <?php } ?>

        </div>

        <div class="footer">
            Data Pasien Rawat Jalan Berdasarkan Jenis Kelamin
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

</script>

</body>
</html>
