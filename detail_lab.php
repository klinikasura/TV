<?php
include 'koneksi.php';

$id = $_GET['id'];

$data = mysqli_fetch_assoc(mysqli_query($koneksi,"
    SELECT * FROM temporary_permintaan_lab WHERE no='$id'
"));
?>

<!DOCTYPE html>
<html>
<head>
<title>Hasil Laboratorium</title>

<style>
body{
    font-family: Arial;
    background:#f4f6f9;
    padding:20px;
}

.paper{
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
}

.header{
    text-align:center;
    border-bottom:2px solid #000;
    margin-bottom:15px;
}

h2{margin:0;}

.section-title{
    background:#4f46e5;
    color:white;
    padding:8px;
    margin-top:20px;
    font-weight:bold;
    border-radius:6px;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:5px;
}

th{
    background:#eee;
    padding:8px;
    border:1px solid #ccc;
}

td{
    padding:6px;
    border:1px solid #eee;
    font-size:13px;
}

.btn{
    padding:10px 15px;
    border:none;
    border-radius:8px;
    cursor:pointer;
    margin-bottom:10px;
}

.print{background:#16a34a; color:white;}
.back{background:#e5e7eb;}

@media print{
    .btn{display:none;}
    body{background:white;}
    .paper{box-shadow:none;}
}
</style>

</head>

<body>

<button class="btn back" onclick="history.back()">⬅ Kembali</button>
<button class="btn print" onclick="window.print()">🖨 Cetak Hasil Lab</button>

<div class="paper">

<!-- HEADER -->
<div class="header">
    <h2>HASIL PEMERIKSAAN LABORATORIUM</h2>
    <p>No : <?= $data['no']; ?> | Nama : <?= $data['temp1']; ?></p>
    <p>Tanggal : <?= $data['temp2']; ?></p>
</div>

<!-- ================= HEMATOLOGI ================= -->
<div class="section-title">HEMATOLOGI</div>

<table>
<tr>
    <th>Pemeriksaan</th>
    <th>Hasil</th>
    <th>Satuan</th>
    <th>Nilai Rujukan</th>
</tr>

<?php
$hematologi = [
    ["Leukosit","temp10","10^9/L","4,0 - 10,0"],
    ["Lymph#","temp11","10^9/L","0,8 - 4,0"],
    ["Mid#","temp12","10^9/L","0,1 - 0,9"],
    ["Gran#","temp13","10^9/L","2,0 - 7,0"],
    ["Hemoglobin","temp14","g/dL","11,0 - 16,0"],
    ["Eritrosit","temp15","10^12/L","3,50 - 5,50"],
    ["Hematokrit","temp16","%","37,0 - 50,0"],
    ["MCV","temp17","fL","82,0 - 95,0"],
    ["MCH","temp18","pg","27,0 - 31,0"],
    ["MCHC","temp19","g/dL","32,0 - 36,0"],
    ["Trombosit","temp20","10^9/L","150 - 450"],
];

foreach ($hematologi as $h){
    echo "<tr>
        <td>{$h[0]}</td>
        <td>{$data[$h[1]]}</td>
        <td>{$h[2]}</td>
        <td>{$h[3]}</td>
    </tr>";
}
?>

</table>

<!-- ================= SEROLOGI ================= -->
<div class="section-title">SEROLOGI</div>

<table>
<tr>
    <th>Pemeriksaan</th>
    <th>Hasil</th>
    <th>Nilai Rujukan</th>
</tr>

<tr>
    <td>WIDAL</td>
    <td><?= $data['temp21']; ?></td>
    <td>Negatif</td>
</tr>

</table>

</div>

</body>
</html>
