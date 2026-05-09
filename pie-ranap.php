<?php

$host   = "10.10.20.250";
$user   = "root";
$pass   = "";
$dbname = "sikdraisyah";

$conn = mysqli_connect($host,$user,$pass,$dbname);

if(!$conn){
    die("Koneksi Database Gagal");
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
    WHERE reg_periksa.status_lanjut='Ranap'
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
    background:#eef2ff;
    padding:40px;
}

.container{
    width:100%;
    max-width:950px;
    margin:auto;
}

.card{
    background:white;
    border-radius:20px;
    padding:30px;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
}

.title{
    font-size:30px;
    font-weight:bold;
    text-align:center;
    margin-bottom:30px;
    color:#1e293b;
}

.filter-area{
    display:flex;
    justify-content:center;
    align-items:end;
    gap:20px;
    flex-wrap:wrap;
    margin-bottom:35px;
}

.form-group{
    display:flex;
    flex-direction:column;
}

.form-group label{
    margin-bottom:8px;
    font-size:14px;
    color:#475569;
}

.form-group input{
    padding:12px;
    border:1px solid #cbd5e1;
    border-radius:10px;
    min-width:200px;
    outline:none;
}

.form-group input:focus{
    border-color:#4f46e5;
}

button{
    padding:13px 28px;
    border:none;
    border-radius:10px;
    background:#4f46e5;
    color:white;
    cursor:pointer;
    font-size:15px;
    transition:0.3s;
}

button:hover{
    background:#4338ca;
}

.chart-box{
    width:100%;
    max-width:500px;
    margin:auto;
}

.statistik{
    display:flex;
    justify-content:center;
    gap:20px;
    margin-top:35px;
    flex-wrap:wrap;
}

.box{
    background:#f8fafc;
    border-radius:12px;
    padding:20px;
    min-width:180px;
    text-align:center;
    box-shadow:0 2px 8px rgba(0,0,0,0.05);
}

.box h4{
    color:#475569;
    margin-bottom:10px;
}

.box p{
    font-size:28px;
    font-weight:bold;
    color:#4f46e5;
}

.footer{
    margin-top:35px;
    text-align:center;
    color:#64748b;
    font-size:14px;
}
.button-group{
    display:flex;
    gap:10px;
    align-items:center;
    flex-wrap:wrap;
}

.btn-link{
    padding:13px 28px;
    border-radius:10px;
    background:#10b981;
    color:white;
    text-decoration:none;
    font-size:15px;
    transition:0.3s;
    display:inline-block;
    text-align:center;
}

.btn-link:hover{
    background:#059669;
}

@media(max-width:600px){

    .filter-area{
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
            Grafik Pasien Rawat Inap (Ranap)
        </div>

        <form method="GET">

            <div class="filter-area">

                <div class="form-group">
                    <label>Tanggal Awal</label>
                    <input 
                        type="date"
                        name="tgl1"
                        value="<?= $tgl1 ?>"
                    >
                </div>

                <div class="form-group">
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

        <div class="chart-box">
            <canvas id="chartRanap"></canvas>
        </div>

        <div class="statistik">

            <?php foreach($labels as $i => $label){ ?>

            <div class="box">
                <h4><?= $label ?></h4>
                <p><?= $data[$i] ?></p>
            </div>

            <?php } ?>

        </div>

        <div class="footer">
            Statistik Pasien Rawat Inap Berdasarkan Jenis Kelamin
        </div>

    </div>

</div>

<script>

const ctx = document.getElementById('chartRanap');

new Chart(ctx, {

    type:'pie',

    data:{

        labels: <?= json_encode($labels) ?>,

        datasets:[{

            data: <?= json_encode($data) ?>,

            backgroundColor:[
                '#6366f1',
                '#f43f5e'
            ],

            borderColor:[
                '#ffffff',
                '#ffffff'
            ],

            borderWidth:3

        }]

    },

    options:{

        responsive:true,

        plugins:{

            legend:{
                position:'bottom',
                labels:{
                    font:{
                        size:14
                    },
                    padding:20
                }
            }

        }

    }

});

</script>

</body>
</html>
