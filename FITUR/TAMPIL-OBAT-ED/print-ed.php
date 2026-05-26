<?php

/* =========================
   KONEKSI DATABASE
========================= */

$conn = mysqli_connect(
    "10.10.20.250",
    "root",
    "",
    "sikdraisyah"
);

if (!$conn) {
    die("Koneksi gagal : " . mysqli_connect_error());
}

date_default_timezone_set("Asia/Jakarta");

/* =========================
   AMBIL KODE OBAT
========================= */

$kode = isset($_GET['kode']) ? $_GET['kode'] : '';

if($kode == ''){
    die("Kode obat tidak ditemukan");
}

/* =========================
   QUERY DATA
========================= */

$sql = "

SELECT

d.kode_brng,
d.nama_brng,
d.expire,

d.karyawan,
d.ralan,

gb.stok,

b.nm_bangsal AS asal_stok

FROM databarang d

LEFT JOIN gudangbarang gb
ON d.kode_brng = gb.kode_brng

LEFT JOIN bangsal b
ON gb.kd_bangsal = b.kd_bangsal

WHERE

d.kode_brng = '$kode'

LIMIT 1

";

$query = mysqli_query($conn, $sql);

$data = mysqli_fetch_assoc($query);

if(!$data){
    die("Data obat tidak ditemukan");
}

?>

<!DOCTYPE html>
<html>
<head>

<meta charset="utf-8">
<meta name="viewport"
content="width=device-width, initial-scale=1.0">

 <title>myROBOT-V80</title>
  <link href="http://10.10.20.250/dashboard/APPS-ROBOT/BUILDING APLIKASI/@API-GITHUB-V80/ROBOT-GITHUB/ROBOTV80.png" rel="icon" type="image/png" />>

<style>

body{
    font-family:Arial,sans-serif;
    margin:20px;
    color:#222;
}

.header{
    text-align:center;
    margin-bottom:25px;
}

.header h2{
    margin:0;
    font-size:24px;
}

.header p{
    margin-top:5px;
    font-size:14px;
}

.box{
    border:2px solid #2196f3;
    border-radius:10px;
    padding:20px;
}

table{
    width:100%;
    border-collapse:collapse;
}

td{
    padding:10px;
    border-bottom:1px solid #ddd;
    vertical-align:top;
}

.label{
    width:220px;
    font-weight:bold;
    background:#f5f5f5;
}

.value{
    font-weight:bold;
}

.status{
    padding:6px 12px;
    border-radius:8px;
    display:inline-block;
    color:white;
    font-size:13px;
}

.aman{
    background:#43a047;
}

.warning{
    background:#fb8c00;
}

.danger{
    background:#e53935;
}

.footer{
    margin-top:40px;
    text-align:right;
    font-size:13px;
}

.print-btn{
    margin-top:20px;
    text-align:center;
}

button{
    padding:12px 18px;
    border:none;
    border-radius:10px;
    background:#2196f3;
    color:white;
    font-weight:bold;
    cursor:pointer;
}

button:hover{
    opacity:0.9;
}

@media print{

    .print-btn{
        display:none;
    }

    body{
        margin:0;
    }

}

@media(max-width:768px){

    body{
        margin:10px;
    }

    .header h2{
        font-size:20px;
    }

    table{
        font-size:13px;
    }

    td{
        padding:8px;
    }

    .label{
        width:140px;
    }

}

</style>

</head>

<body>

<?php

/* =========================
   STATUS OBAT
========================= */

$status = "AMAN";
$class  = "aman";

$today    = strtotime(date("Y-m-d"));
$expired  = strtotime($data['expire']);

$selisih = ($expired - $today) / (60*60*24);

if($selisih <= 30){

    $status = "HAMPIR EXPIRED";
    $class  = "warning";

}

if($selisih <= 0){

    $status = "EXPIRED";
    $class  = "danger";

}

?>

<!-- HEADER -->
<div class="header">

<h2>📋 DATA OBAT EXPIRED</h2>
<p>myROBOT-V80</p>

</div>

<!-- BOX -->
<div class="box">

<table>

<tr>

<td class="label">Kode Obat</td>

<td class="value">
<?php echo $data['kode_brng']; ?>
</td>

</tr>

<tr>

<td class="label">Nama Obat</td>

<td class="value">
<?php echo $data['nama_brng']; ?>
</td>

</tr>

<tr>

<td class="label">Tanggal Expired</td>

<td class="value">
<?php echo $data['expire']; ?>
</td>

</tr>

<tr>

<td class="label">Asal Stok</td>

<td class="value">
<?php echo $data['asal_stok']; ?>
</td>

</tr>

<tr>

<td class="label">Jumlah Stok</td>

<td class="value">
<?php echo number_format($data['stok']); ?>
</td>

</tr>

<tr>

<td class="label">Harga Karyawan</td>

<td class="value">

Rp.
<?php echo number_format($data['karyawan'],0,',','.'); ?>

</td>

</tr>

<tr>

<td class="label">Harga Ralan</td>

<td class="value">

Rp.
<?php echo number_format($data['ralan'],0,',','.'); ?>

</td>

</tr>

<tr>

<td class="label">Status</td>

<td>

<span class="status <?php echo $class; ?>">

<?php echo $status; ?>

</span>

</td>

</tr>

</table>

</div>

<!-- FOOTER -->
<div class="footer">

Dicetak :
<?php echo date("d-m-Y H:i:s"); ?>

</div>

<!-- BUTTON -->
<div class="print-btn">

<button onclick="window.print()">

🖨 Cetak Data

</button>

</div>

<script>

window.onload = function(){

    setTimeout(function(){

        window.print();

    },500);

}

</script>

</body>
</html>
