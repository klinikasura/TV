<?php
session_start();
require_once 'config.php';
require_once 'includes.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: http://10.10.20.250/dashboard/APPS-ROBOT/GITHUB/LOG/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$query_user = "SELECT * FROM robot80_data_anggota WHERE id='$user_id'";
$result_user = $mysqli->query($query_user);
$user = $result_user->fetch_assoc();

update_user_activity($user_id);

/* =========================
   KONEKSI DATABASE
========================= */

$conn = mysqli_connect("10.10.20.250", "root", "", "sikdraisyah");

if (!$conn) {
    die("Koneksi gagal : " . mysqli_connect_error());
}

date_default_timezone_set("Asia/Jakarta");

$sekarang = date("Y-m-d");

/* =========================
   FILTER TANGGAL
========================= */

$dari   = isset($_GET['dari']) ? $_GET['dari'] : '';
$sampai = isset($_GET['sampai']) ? $_GET['sampai'] : '';

$filterTanggal = "";

if($dari != "" && $sampai != ""){

    $filterTanggal = "
    AND d.expire BETWEEN '$dari' AND '$sampai'
    ";

}

?>

<!DOCTYPE html>
<html>
<head>

<meta charset="utf-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

 <title>myROBOT-V80</title>
  <link href="http://10.10.20.250/dashboard/APPS-ROBOT/BUILDING APLIKASI/@API-GITHUB-V80/ROBOT-GITHUB/ROBOTV80.png" rel="icon" type="image/png" />

<!-- BOOTSTRAP -->
<link href="http://10.10.20.250/dashboard/APPS-ROBOT/GITHUB/DATA-CYBER-2/FARMASI/TAMPIL-OBAT-ED/css/bootstrap.min.css" rel="stylesheet">

<!-- JQUERY -->
<script src="http://10.10.20.250/dashboard/APPS-ROBOT/GITHUB/DATA-CYBER-2/FARMASI/TAMPIL-OBAT-ED/js/jquery.min.js"></script>

<!-- DATATABLE -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<style>

/* =========================
   BODY
========================= */

body{
    font-family:'Segoe UI',sans-serif;
    background:linear-gradient(135deg,#dff6ff,#b3e5fc);
    margin:0;
    color:#0d2b45;
    padding-bottom:100px;
}

/* =========================
   HEADER
========================= */

.header-box{
    background:linear-gradient(45deg,#4fc3f7,#0288d1);
    color:white;
    padding:20px;
    text-align:center;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

.header-box h2{
    margin:0;
    font-size:28px;
}

.header-box p{
    margin-top:5px;
}

/* =========================
   FILTER
========================= */

.search-box{
    width:95%;
    margin:auto;
    margin-top:15px;
    background:white;
    padding:15px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,0.08);
}

.filter-row{
    display:flex;
    flex-wrap:wrap;
    gap:10px;
    align-items:center;
    justify-content:center;
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



input{
    padding:12px;
    border-radius:10px;
    border:2px solid #81d4fa;
    outline:none;
    min-width:180px;
}

/* =========================
   BUTTON
========================= */

.btn{
    padding:12px 18px;
    border:none;
    border-radius:10px;
    cursor:pointer;
    color:white;
    font-weight:bold;
    transition:0.3s;
    text-decoration:none;
}

.btn:hover{
    transform:scale(1.03);
    opacity:0.9;
}

.btn-cari{
    background:linear-gradient(45deg,#29b6f6,#0288d1);
}

.btn-refresh{
    background:linear-gradient(45deg,#66bb6a,#2e7d32);
}

.btn-pdf{
    background:linear-gradient(45deg,#ef5350,#c62828);
}

.btn-print{
    padding:8px 14px;
    border:none;
    border-radius:8px;
    background:#03a9f4;
    color:white;
    font-size:12px;
    font-weight:bold;
}
/* =========================
   TABLE
========================= */

.table-box{
    width:95%;
    margin:auto;
    margin-top:20px;
    overflow-x:auto;
    -webkit-overflow-scrolling:touch;
}

table{
    width:100%;
    min-width:1000px;
    border-collapse:collapse;
    background:white;
    border-radius:15px;
    overflow:hidden;
    box-shadow:0 10px 20px rgba(0,0,0,0.08);
}


thead{
    background:linear-gradient(45deg,#4fc3f7,#03a9f4);
    color:white;
}

th,td{
    padding:10px;
    text-align:center;
    white-space:nowrap;
}

/* =========================
   KOLOM NAMA OBAT
========================= */

td:nth-child(3),
th:nth-child(3){

    width:220px;
    max-width:220px;

    white-space:normal !important;
    word-break:break-word;

    text-align:left;

}

/* =========================
   KOLOM HARGA
========================= */

td:nth-child(7),
td:nth-child(8),

th:nth-child(7),
th:nth-child(8){

    width:120px;
}

/* =========================
   KOLOM STATUS
========================= */

td:nth-child(9),
th:nth-child(9){

    width:100px;
}

/* =========================
   KOLOM CETAK
========================= */

td:nth-child(10),
th:nth-child(10){

    width:90px;
}

/* =========================
   ROW TABLE
========================= */

tr:nth-child(even){
    background:#f1faff;
}

tr:hover{
    background:#e3f2fd;
}

/* =========================
   STATUS
========================= */

.igd{
    background:#ffebee !important;
    color:#c62828;
    font-weight:bold;
}

.stok-tipis{
    background:#fff8e1 !important;
    color:#ff6f00;
    font-weight:bold;
}

.expired-soon{
    background:#fce4ec !important;
    color:#ad1457;
    font-weight:bold;
}

/* =========================
   HARGA
========================= */

.harga{
    font-weight:bold;
    color:#1565c0;
    font-size:13px;
}

/* =========================
   DATATABLE
========================= */

.dataTables_wrapper{
    background:white;
    padding:15px;
    border-radius:15px;
}

.dataTables_filter input{
    border:2px solid #81d4fa !important;
    border-radius:10px !important;
}

.dataTables_length select{
    border-radius:8px;
    padding:5px;
}


/* =========================
   MOBILE RESPONSIVE
========================= */

@media screen and (max-width:768px){

    body{
        padding-bottom:120px;
    }

    .header-box{
        padding:15px;
    }

    .header-box h2{
        font-size:20px;
    }

    .header-box p{
        font-size:12px;
    }

    .search-box{
        width:95%;
        padding:12px;
    }

    .filter-row{
        flex-direction:column;
        align-items:stretch;
    }

    input{
        width:100%;
        min-width:100%;
        font-size:14px;
    }

    .btn{
        width:100%;
        font-size:14px;
        text-align:center;
    }

    .table-box{
        width:100%;
        padding:5px;
    }

    table{
        min-width:1000px;
        font-size:11px;
    }

    th{
        font-size:11px;
        padding:8px 5px;
    }

    td{
        font-size:11px;
        padding:7px 5px;
    }

    td:nth-child(3),
    th:nth-child(3){

        width:180px;
        max-width:180px;

    }

    .btn-print{
        padding:6px 10px;
        font-size:10px;
    }

    .dataTables_filter{
        text-align:left !important;
    }

    .dataTables_filter input{
        width:100% !important;
        margin-top:5px;
    }

    .dataTables_info{
        font-size:11px;
    }

    .dataTables_paginate{
        font-size:11px;
        overflow-x:auto;
    }

}

/* =========================
   TABLE
========================= */

.table-box{
    width:95%;
    margin:auto;
    margin-top:20px;
    overflow-x:auto;
    -webkit-overflow-scrolling:touch;
}

table{
    width:100%;
    min-width:1300px;
    border-collapse:collapse;
    background:white;
    border-radius:15px;
    overflow:hidden;
    box-shadow:0 10px 20px rgba(0,0,0,0.08);
}

thead{
    background:linear-gradient(45deg,#4fc3f7,#03a9f4);
    color:white;
}

th,td{
    padding:12px;
    text-align:center;
    white-space:nowrap;
}

tr:nth-child(even){
    background:#f1faff;
}

tr:hover{
    background:#e3f2fd;
}

/* =========================
   STATUS
========================= */

.igd{
    background:#ffebee !important;
    color:#c62828;
    font-weight:bold;
}

.stok-tipis{
    background:#fff8e1 !important;
    color:#ff6f00;
    font-weight:bold;
}

.expired-soon{
    background:#fce4ec !important;
    color:#ad1457;
    font-weight:bold;
}

/* =========================
   HARGA
========================= */

.harga{
    font-weight:bold;
    color:#1565c0;
}

/* =========================
   DATATABLE
========================= */

.dataTables_wrapper{
    background:white;
    padding:15px;
    border-radius:15px;
}

.dataTables_filter input{
    border:2px solid #81d4fa !important;
    border-radius:10px !important;
}

.dataTables_length select{
    border-radius:8px;
    padding:5px;
}


/* =========================
   MOBILE RESPONSIVE
========================= */

@media screen and (max-width:768px){

    body{
        padding-bottom:120px;
    }

    .header-box{
        padding:15px;
    }

    .header-box h2{
        font-size:20px;
    }

    .header-box p{
        font-size:12px;
    }

    .search-box{
        width:95%;
        padding:12px;
    }

    .filter-row{
        flex-direction:column;
        align-items:stretch;
    }

    input{
        width:100%;
        min-width:100%;
        font-size:14px;
    }

    .btn{
        width:100%;
        font-size:14px;
        text-align:center;
    }

    .table-box{
        width:100%;
        padding:5px;
    }

    table{
        min-width:1300px;
        font-size:11px;
    }

    th{
        font-size:11px;
        padding:10px 6px;
    }

    td{
        font-size:11px;
        padding:8px 6px;
    }

    .btn-print{
        padding:6px 10px;
        font-size:10px;
    }

    .dataTables_filter{
        text-align:left !important;
    }

    .dataTables_filter input{
        width:100% !important;
        margin-top:5px;
    }

    .dataTables_info{
        font-size:11px;
    }

    .dataTables_paginate{
        font-size:11px;
        overflow-x:auto;
    }

}

</style>

</head>

<body>

<!-- HEADER -->
<div class="header-box">

<h2>📋 DATA OBAT EXPIRED, HARGA & STOK</h2>
<p>myROBOT-V80</p>

</div>

<!-- FILTER -->
<div class="search-box">

<form method="GET">

<div class="filter-row">

<input type="date" name="dari"
value="<?php echo $dari; ?>">

<input type="date" name="sampai"
value="<?php echo $sampai; ?>">

<button type="submit" class="btn btn-cari">
🔍 Cari Data
</button>

<a href="?" class="btn btn-refresh">
🔄 Refresh
</a>

<button type="button" class="btn btn-kalkulator" onclick="openCalculator()">
🧮 Kalkulator
</button>

<a href="cetak-pdf.php?dari=<?php echo $dari; ?>&sampai=<?php echo $sampai; ?>" 
target="_blank"
class="btn btn-pdf">

📄 Cetak Rekapan

</a>

</div>

</form>

</div>

<!-- TABLE -->
<div class="table-box">

<table id="dataObat">

<thead>

<tr>

<th>No</th>
<th>Kode</th>
<th>Nama Obat</th>
<th>Expired</th>
<th>Asal Stok</th>
<th>Jumlah</th>

<th>Harga Karyawan</th>
<th>Harga Ralan</th>

<th>Status</th>
<th>Cetak</th>

</tr>

</thead>

<tbody>

<?php

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

d.status='1'

AND d.expire != '0000-00-00'
AND d.expire != ''
AND d.expire IS NOT NULL

/* DEFAULT TAMPIL YANG BELUM KADALUARSA */

$filterTanggal

ORDER BY

CASE
    WHEN d.expire >= CURDATE() THEN 0
    ELSE 1
END,

d.expire ASC

";
$query = mysqli_query($conn, $sql);

$no = 1;

while($row = mysqli_fetch_assoc($query)){

$class = "";
$status = "AMAN";

$today = strtotime(date("Y-m-d"));
$expired = strtotime($row['expire']);

$selisih = ($expired - $today) / (60*60*24);

/* STATUS */

if(
stripos($row['asal_stok'],'IGD') !== false ||
stripos($row['asal_stok'],'FARMASI') !== false
){
$class = "igd";
$status = "PRIORITAS";
}

if($row['stok'] <= 10){
$class = "stok-tipis";
$status = "STOK TIPIS";
}

if($selisih <= 0){
$class = "expired-soon";
$status = "SUDAH KADALUARSA";
}

?>


<tr class="<?php echo $class; ?>">

<td>
<?php echo $no++; ?>
</td>

<td>
<?php echo $row['kode_brng']; ?>
</td>

<td style="text-align:left;">
<?php echo $row['nama_brng']; ?>
</td>

<td>
<?php echo $row['expire']; ?>
</td>

<td>
<?php echo $row['asal_stok']; ?>
</td>

<td>

<b>
<?php echo number_format($row['stok']); ?>
</b>

</td>

<td class="harga">

Rp.
<?php echo number_format($row['karyawan'],0,',','.'); ?>

</td>

<td class="harga">

Rp.
<?php echo number_format($row['ralan'],0,',','.'); ?>

</td>

<td>

<?php echo $status; ?>

</td>

<td>

<a href="print-ed.php?kode=<?php echo $row['kode_brng']; ?>" 
target="_blank">

<button class="btn-print">

🖨 Print

</button>

</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

<!-- DATATABLE -->
<script>

$(document).ready(function(){

$('#dataObat').DataTable({

    responsive:true,
    scrollX:true,

    "pageLength":25,

    "order":[[3,"asc"]],

    "language":{

        "search":"🔍 Cari : ",

        "lengthMenu":"Tampilkan _MENU_ data",

        "zeroRecords":"Data tidak ditemukan",

        "info":"Menampilkan _START_ - _END_ dari _TOTAL_ data",

        "paginate":{
            "next":"➡",
            "previous":"⬅"
        }

    }

});

});

</script>

<!-- =========================
     TOMBOL KALKULATOR
========================= -->





</button>

<!-- =========================
     CSS KALKULATOR
========================= -->

<style>

.btn-kalkulator{
    background:linear-gradient(45deg,#7e57c2,#5e35b1);
    color:white;
}

/* POPUP */

.calculator-popup{

    display:none;

    position:fixed;

    top:0;
    left:0;

    width:100%;
    height:100%;

    background:rgba(0,0,0,0.5);

    z-index:9999;

    justify-content:center;
    align-items:center;

}

/* BOX */

.calculator-box{

    background:white;

    width:320px;

    border-radius:20px;

    padding:20px;

    box-shadow:0 10px 25px rgba(0,0,0,0.2);

}

/* DISPLAY */

.calc-display{

    width:100%;

    height:60px;

    border:none;

    background:#f1faff;

    border-radius:12px;

    margin-bottom:15px;

    text-align:right;

    font-size:24px;

    padding:10px;

    font-weight:bold;

}

/* BUTTON GRID */

.calc-buttons{

    display:grid;

    grid-template-columns:repeat(4,1fr);

    gap:10px;

}

.calc-buttons button{

    padding:15px;

    border:none;

    border-radius:12px;

    background:#03a9f4;

    color:white;

    font-size:18px;

    font-weight:bold;

    cursor:pointer;

}

.calc-buttons button:hover{

    opacity:0.9;

}

/* WARNA */

.btn-equal{

    background:#43a047 !important;

}

.btn-clear{

    background:#e53935 !important;

}

/* TUTUP */

.close-popup{

    width:100%;

    margin-top:15px;

    padding:12px;

    border:none;

    border-radius:12px;

    background:#616161;

    color:white;

    font-weight:bold;

    cursor:pointer;

}

/* MOBILE */

@media(max-width:768px){

    .calculator-box{

        width:95%;

    }

}

</style>

<!-- =========================
     POPUP KALKULATOR
========================= -->

<div class="calculator-popup"
id="calculatorPopup">

<div class="calculator-box">

<input type="text"
id="calcDisplay"
class="calc-display"
readonly>

<div class="calc-buttons">

<button onclick="appendCalc('7')">7</button>
<button onclick="appendCalc('8')">8</button>
<button onclick="appendCalc('9')">9</button>
<button onclick="appendCalc('/')">÷</button>

<button onclick="appendCalc('4')">4</button>
<button onclick="appendCalc('5')">5</button>
<button onclick="appendCalc('6')">6</button>
<button onclick="appendCalc('*')">×</button>

<button onclick="appendCalc('1')">1</button>
<button onclick="appendCalc('2')">2</button>
<button onclick="appendCalc('3')">3</button>
<button onclick="appendCalc('-')">−</button>

<button onclick="appendCalc('0')">0</button>
<button onclick="appendCalc('.')">.</button>

<button class="btn-equal"
onclick="calculateResult()">

=

</button>

<button onclick="appendCalc('+')">+</button>

<button class="btn-clear"
onclick="clearCalc()">

C

</button>

</div>

<button class="close-popup"
onclick="closeCalculator()">

❌ Tutup

</button>

</div>

</div>

<!-- =========================
     JAVASCRIPT
========================= -->

<script>

function openCalculator(){

    document.getElementById(
        'calculatorPopup'
    ).style.display='flex';

}

function closeCalculator(){

    document.getElementById(
        'calculatorPopup'
    ).style.display='none';

}

function appendCalc(value){

    document.getElementById(
        'calcDisplay'
    ).value += value;

}

function clearCalc(){

    document.getElementById(
        'calcDisplay'
    ).value = '';

}

function calculateResult(){

    try{

        document.getElementById(
            'calcDisplay'
        ).value = eval(

            document.getElementById(
                'calcDisplay'
            ).value

        );

    }catch(e){

        alert('Perhitungan salah');

    }

}

/* KLIK LUAR POPUP */

window.onclick = function(event){

    let popup = document.getElementById(
        'calculatorPopup'
    );

    if(event.target == popup){

        popup.style.display='none';

    }

}

</script>
<!-- =========================
     JAVASCRIPT KALKULATOR
     SUPPORT KEYBOARD PC
========================= -->

<script>

function openCalculator(){

    document.getElementById(
        'calculatorPopup'
    ).style.display='flex';

}

function closeCalculator(){

    document.getElementById(
        'calculatorPopup'
    ).style.display='none';

}

function appendCalc(value){

    document.getElementById(
        'calcDisplay'
    ).value += value;

}

function clearCalc(){

    document.getElementById(
        'calcDisplay'
    ).value = '';

}

function calculateResult(){

    try{

        document.getElementById(
            'calcDisplay'
        ).value = eval(

            document.getElementById(
                'calcDisplay'
            ).value

        );

    }catch(e){

        alert('Perhitungan salah');

    }

}

/* =========================
   SUPPORT KEYBOARD
========================= */

document.addEventListener(
'keydown',
function(event){

    let key = event.key;

    let popup = document.getElementById(
        'calculatorPopup'
    );

    /* JIKA POPUP TIDAK AKTIF */

    if(
        popup.style.display != 'flex'
    ){
        return;
    }

    /* ANGKA */

    if(
        !isNaN(key)
    ){

        appendCalc(key);

    }

    /* OPERATOR */

    if(
        key == '+' ||
        key == '-' ||
        key == '*' ||
        key == '/' ||
        key == '.'
    ){

        appendCalc(key);

    }

    /* ENTER = HITUNG */

    if(key == 'Enter'){

        calculateResult();

    }

    /* BACKSPACE */

    if(key == 'Backspace'){

        let display = document.getElementById(
            'calcDisplay'
        );

        display.value = display.value.slice(0,-1);

    }

    /* ESC = TUTUP */

    if(key == 'Escape'){

        closeCalculator();

    }

    /* DELETE = CLEAR */

    if(key == 'Delete'){

        clearCalc();

    }

});

/* =========================
   KLIK LUAR POPUP
========================= */

window.onclick = function(event){

    let popup = document.getElementById(
        'calculatorPopup'
    );

    if(event.target == popup){

        popup.style.display='none';

    }

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
