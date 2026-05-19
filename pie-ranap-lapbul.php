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
| FILTER TAHUN
|--------------------------------------------------------------------------
*/

$tahun = $_GET['tahun'] ?? date('Y');

/*
|--------------------------------------------------------------------------
| QUERY LAPORAN BULANAN
|--------------------------------------------------------------------------
*/

$query = mysqli_query($conn, "

    SELECT
        MONTH(reg_periksa.tgl_registrasi) AS bulan,

        SUM(
            CASE 
                WHEN pasien.jk='L' THEN 1 
                ELSE 0 
            END
        ) AS laki,

        SUM(
            CASE 
                WHEN pasien.jk='P' THEN 1 
                ELSE 0 
            END
        ) AS perempuan,

        COUNT(*) AS total

    FROM reg_periksa

    INNER JOIN pasien
        ON pasien.no_rkm_medis = reg_periksa.no_rkm_medis

    WHERE reg_periksa.status_lanjut='Ranap'

    AND YEAR(reg_periksa.tgl_registrasi)='$tahun'

    GROUP BY MONTH(reg_periksa.tgl_registrasi)

    ORDER BY MONTH(reg_periksa.tgl_registrasi)

");

$dataBulanan = [];

for($i=1; $i<=12; $i++){

    $dataBulanan[$i] = [
        'laki'       => 0,
        'perempuan'  => 0,
        'total'      => 0
    ];

}

while($row = mysqli_fetch_assoc($query)){

    $bln = $row['bulan'];

    $dataBulanan[$bln] = [
        'laki'       => $row['laki'],
        'perempuan'  => $row['perempuan'],
        'total'      => $row['total']
    ];

}

$namaBulan = [
    1 => 'Januari',
    2 => 'Februari',
    3 => 'Maret',
    4 => 'April',
    5 => 'Mei',
    6 => 'Juni',
    7 => 'Juli',
    8 => 'Agustus',
    9 => 'September',
    10 => 'Oktober',
    11 => 'November',
    12 => 'Desember'
];

?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>myROBOT-V80</title>
<link href="http://10.10.20.250/dashboard/APPS-ROBOT/BUILDING APLIKASI/@API-GITHUB-V80/ROBOT-GITHUB/ROBOTV80.png" rel="icon" type="image/png" />

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
    padding:30px;
}

.container{
    max-width:1200px;
    margin:auto;
}

.card{
    background:white;
    border-radius:20px;
    padding:30px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

.title{
    text-align:center;
    font-size:30px;
    font-weight:bold;
    margin-bottom:10px;
    color:#1e293b;
}

.subtitle{
    text-align:center;
    margin-bottom:30px;
    color:#64748b;
}

.filter-box{
    display:flex;
    justify-content:center;
    gap:15px;
    flex-wrap:wrap;
    margin-bottom:30px;
}

.input-group{
    display:flex;
    flex-direction:column;
}

label{
    margin-bottom:8px;
    font-weight:bold;
    color:#334155;
}

select{
    padding:12px;
    border-radius:10px;
    border:1px solid #cbd5e1;
    min-width:200px;
    font-size:15px;
}

.button-group{
    display:flex;
    gap:10px;
    align-items:end;
}

button{
    padding:12px 20px;
    border:none;
    border-radius:10px;
    cursor:pointer;
    color:white;
    font-size:15px;
}

.btn-filter{
    background:#2563eb;
}

.btn-print{
    background:#dc2626;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}

table thead{
    background:#2563eb;
    color:white;
}

table th,
table td{
    padding:15px;
    border:1px solid #e2e8f0;
    text-align:center;
}

table tbody tr:nth-child(even){
    background:#f8fafc;
}

.total-row{
    background:#1e293b !important;
    color:white;
    font-weight:bold;
}

.footer{
    margin-top:25px;
    text-align:center;
    color:#64748b;
}

@media(max-width:700px){

    .filter-box{
        flex-direction:column;
    }

    .button-group{
        width:100%;
        flex-direction:column;
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
            LAPORAN RAWAT INAP (RANAP)
        </div>

        <div class="subtitle">
            Rekap Perbulan Berdasarkan Jenis Kelamin
        </div>

        <form method="GET">

            <div class="filter-box">

                <div class="input-group">

                    <label>Pilih Tahun</label>

                    <select name="tahun">

                        <?php
                        for($t=date('Y'); $t>=2020; $t--){
                        ?>

                        <option 
                            value="<?= $t ?>"
                            <?= ($tahun==$t) ? 'selected' : '' ?>
                        >
                            <?= $t ?>
                        </option>

                        <?php } ?>

                    </select>

                </div>

                <div class="button-group">

                    <button type="submit" class="btn-filter">
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

        <table>

            <thead>

                <tr>
                    <th>No</th>
                    <th>Bulan</th>
                    <th>Laki-Laki</th>
                    <th>Perempuan</th>
                    <th>Total</th>
                </tr>

            </thead>

            <tbody>

                <?php

                $grandL = 0;
                $grandP = 0;
                $grandT = 0;

                for($i=1; $i<=12; $i++){

                    $grandL += $dataBulanan[$i]['laki'];
                    $grandP += $dataBulanan[$i]['perempuan'];
                    $grandT += $dataBulanan[$i]['total'];

                ?>

                <tr>

                    <td><?= $i ?></td>

                    <td><?= $namaBulan[$i] ?></td>

                    <td><?= $dataBulanan[$i]['laki'] ?></td>

                    <td><?= $dataBulanan[$i]['perempuan'] ?></td>

                    <td><?= $dataBulanan[$i]['total'] ?></td>

                </tr>

                <?php } ?>

                <tr class="total-row">

                    <td colspan="2">TOTAL</td>

                    <td><?= $grandL ?></td>

                    <td><?= $grandP ?></td>

                    <td><?= $grandT ?></td>

                </tr>

            </tbody>

        </table>

        <div class="footer">

            Tahun Laporan :
            <b><?= $tahun ?></b>

        </div>

    </div>

</div>

<script>

/*
|--------------------------------------------------------------------------
| CETAK PDF
|--------------------------------------------------------------------------
*/

function downloadPDF(){

    const element = document.getElementById('printArea');

    const opt = {

        margin: 0.3,

        filename: 'laporan-ranap-tahun-<?= $tahun ?>.pdf',

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
            orientation: 'landscape'
        }

    };

    html2pdf().set(opt).from(element).save();

}

</script>

</body>
</html>
