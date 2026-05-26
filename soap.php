<?php

// ======================================
// KONEKSI DATABASE
// ======================================

$host = "10.10.20.250";
$user = "root";
$pass = "";
$db   = "sikdraisyah";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal : " . mysqli_connect_error());
}

// ======================================
// FILTER
// ======================================

$where = [];

// FILTER TANGGAL

if (!empty($_GET['tgl_awal']) && !empty($_GET['tgl_akhir'])) {

    $tgl_awal  = mysqli_real_escape_string($koneksi, $_GET['tgl_awal']);
    $tgl_akhir = mysqli_real_escape_string($koneksi, $_GET['tgl_akhir']);

    $where[] = "
        pemeriksaan_ralan.tgl_perawatan
        BETWEEN '$tgl_awal' AND '$tgl_akhir'
    ";
}

// FILTER PETUGAS

if (!empty($_GET['nip'])) {

    $nip = mysqli_real_escape_string($koneksi, $_GET['nip']);

    $where[] = "
        pemeriksaan_ralan.nip = '$nip'
    ";
}

// FILTER DOKTER

if (!empty($_GET['kd_dokter'])) {

    $kd_dokter = mysqli_real_escape_string(
        $koneksi,
        $_GET['kd_dokter']
    );

    $where[] = "
        reg_periksa.kd_dokter = '$kd_dokter'
    ";
}

$where_sql = "";

if(count($where) > 0){
    $where_sql = "WHERE " . implode(" AND ", $where);
}

// ======================================
// QUERY DATA
// ======================================

$query = mysqli_query($koneksi, "

    SELECT

        pemeriksaan_ralan.*,

        petugas.nama AS nama_petugas,

        dokter.nm_dokter,

        poliklinik.nm_poli,

        pasien.no_rkm_medis,

        pasien.nm_pasien

    FROM pemeriksaan_ralan

    LEFT JOIN petugas
        ON petugas.nip = pemeriksaan_ralan.nip

    LEFT JOIN reg_periksa
        ON reg_periksa.no_rawat =
           pemeriksaan_ralan.no_rawat

    LEFT JOIN dokter
        ON dokter.kd_dokter =
           reg_periksa.kd_dokter

    LEFT JOIN poliklinik
        ON poliklinik.kd_poli =
           reg_periksa.kd_poli

    LEFT JOIN pasien
        ON pasien.no_rkm_medis =
           reg_periksa.no_rkm_medis

    $where_sql

    ORDER BY
        pemeriksaan_ralan.tgl_perawatan DESC,
        pemeriksaan_ralan.jam_rawat DESC

    LIMIT 20

");

if(!$query){
    die(mysqli_error($koneksi));
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

 <title>myROBOT-V80</title>
  <link href="http://10.10.20.250/dashboard/APPS-ROBOT/BUILDING APLIKASI/@API-GITHUB-V80/ROBOT-GITHUB/ROBOTV80.png" rel="icon" type="image/png" />

<!-- DATATABLE -->

<link rel="stylesheet"
href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

<link rel="stylesheet"
href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<link rel="stylesheet"
href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

<!-- JQUERY -->

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DATATABLE -->

<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<!-- EXPORT -->

<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, Helvetica, sans-serif;
}

body{
    background:#f1f5f9;
    padding:20px;
}

.container{
    width:100%;
    background:white;
    padding:20px;
    border-radius:16px;
    box-shadow:0 4px 20px rgba(0,0,0,0.08);
}

h2{
    margin-bottom:20px;
    color:#0f172a;
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





/* FILTER */

.filter-box{
    background:#f8fafc;
    padding:20px;
    border-radius:12px;
    border:1px solid #dbe3ec;
    margin-bottom:20px;
}

.filter-group{
    display:flex;
    flex-wrap:wrap;
    gap:12px;
    align-items:end;
}

.form-group{
    display:flex;
    flex-direction:column;
}

label{
    margin-bottom:5px;
    font-size:13px;
    font-weight:bold;
}

input[type=date],
select{
    padding:10px;
    border:1px solid #cbd5e1;
    border-radius:10px;
    min-width:220px;
}

/* BUTTON */

.btn{
    padding:10px 18px;
    border:none;
    border-radius:10px;
    color:white;
    cursor:pointer;
    text-decoration:none;
    font-weight:bold;
}

.btn-filter{
    background:#2563eb;
}

.btn-reset{
    background:#ef4444;
}

/* BADGE */

.badge{
    background:#16a34a;
    color:white;
    padding:5px 10px;
    border-radius:30px;
    font-size:12px;
    display:inline-block;
}

/* TABLE */

.table-wrapper{
    width:100%;
    overflow-x:auto;
    overflow-y:hidden;
    border:1px solid #dbe3ec;
    border-radius:12px;
}

.table-wrapper::-webkit-scrollbar{
    height:10px;
}

.table-wrapper::-webkit-scrollbar-thumb{
    background:#94a3b8;
    border-radius:20px;
}

table.dataTable{
    border-collapse:collapse !important;
    width:100% !important;
}

table.dataTable thead th{

    background:#2563eb !important;
    color:white !important;

    border:1px solid #dbeafe !important;

    padding:10px !important;

    font-size:13px;

    white-space:nowrap;
}

table.dataTable tbody td{

    border:1px solid #dbe3ec !important;

    padding:8px !important;

    font-size:13px;

    vertical-align:top;
}

table.dataTable tbody tr:nth-child(even){
    background:#f8fafc;
}

table.dataTable tbody tr:hover{
    background:#e0f2fe !important;
}
/* ======================================
SCROLL HORIZONTAL
====================================== */

.scroll-box{

    width:100%;

    overflow-x:auto;

    overflow-y:hidden;

    -webkit-overflow-scrolling:touch;

    border-radius:12px;
}

.scroll-box::-webkit-scrollbar{
    height:10px;
}

.scroll-box::-webkit-scrollbar-track{
    background:#e2e8f0;
    border-radius:20px;
}

.scroll-box::-webkit-scrollbar-thumb{
    background:#94a3b8;
    border-radius:20px;
}

.scroll-box::-webkit-scrollbar-thumb:hover{
    background:#64748b;
}

/* EXPORT */

.dt-buttons{
    margin-bottom:15px;
}

.dt-buttons .dt-button{

    background:#2563eb !important;

    color:white !important;

    border:none !important;

    border-radius:10px !important;
}
/* ======================================
HEADER
====================================== */

.header-box{

    display:flex;

    justify-content:space-between;

    align-items:center;

    gap:10px;

    margin-bottom:20px;

    flex-wrap:wrap;
}

.header-button{

    display:flex;

    gap:10px;

    flex-wrap:wrap;
}

.btn-dashboard{
    background:#0f172a;
}

.btn-refresh{
    background:#16a34a;
}

/* MOBILE */

@media(max-width:768px){

    .header-box{
        flex-direction:column;
        align-items:flex-start;
    }

    .header-button{
        width:100%;
    }

    .header-button .btn{
        flex:1;
        text-align:center;
    }

}

/* MOBILE */

@media(max-width:768px){

    .filter-group{
        flex-direction:column;
    }

    input[type=date],
    select,
    .btn{
        width:100%;
    }

}

</style>

</head>

<body>

<div class="container">

<div class="header-box">

    <h2>DATA SOAP PASIEN RAWAT JALAN</h2>

    <div class="header-button">

        <a href="soap-ranap.php" class="btn btn-dashboard">
            SOAP RANAP
        </a>

        <a href="soap.php" class="btn btn-refresh">
            Refresh
        </a>


        <a href="http://10.10.20.250/dashboard/APPS-ROBOT/GITHUB/SOAP/ROBOT-V80/rawat_jalan/manage?t=d9d3d5af7281" class="btn btn-refresh">
            Tambah SOAP
        </a>

    </div>

</div>

<!-- FILTER -->

<div class="filter-box">

<form method="GET">

<div class="filter-group">

<!-- TANGGAL AWAL -->

<div class="form-group">

<label>Tanggal Awal</label>

<input
type="date"
name="tgl_awal"
value="<?= $_GET['tgl_awal'] ?? '' ?>"
>

</div>

<!-- TANGGAL AKHIR -->

<div class="form-group">

<label>Tanggal Akhir</label>

<input
type="date"
name="tgl_akhir"
value="<?= $_GET['tgl_akhir'] ?? '' ?>"
>

</div>

<!-- FILTER PETUGAS -->

<div class="form-group">

<label>Petugas Input SOAP</label>

<select name="nip">

<option value="">
-- Semua Petugas --
</option>

<?php

$petugas = mysqli_query(
    $koneksi,
    "SELECT nip,nama
     FROM petugas
     WHERE status='1'
     ORDER BY nama ASC"
);

while($p = mysqli_fetch_assoc($petugas)){

$selected = (
isset($_GET['nip']) &&
$_GET['nip'] == $p['nip']
) ? 'selected' : '';

echo "
<option value='$p[nip]' $selected>
$p[nama]
</option>
";

}

?>

</select>

</div>

<!-- FILTER DOKTER -->

<div class="form-group">

<label>Dokter</label>

<select name="kd_dokter">

<option value="">
-- Semua Dokter --
</option>

<?php

$dokter = mysqli_query(
    $koneksi,
    "SELECT kd_dokter,nm_dokter
     FROM dokter
     WHERE status='1'
     ORDER BY nm_dokter ASC"
);

while($d = mysqli_fetch_assoc($dokter)){

$selected = (
isset($_GET['kd_dokter']) &&
$_GET['kd_dokter'] == $d['kd_dokter']
) ? 'selected' : '';

echo "
<option value='$d[kd_dokter]' $selected>
$d[nm_dokter]
</option>
";

}

?>

</select>

</div>

<!-- BUTTON -->

<div class="form-group">

<button
type="submit"
class="btn btn-filter">
FILTER
</button>

</div>

<div class="form-group">

<a href="soap.php"
class="btn btn-reset">
RESET
</a>

</div>

</div>

</form>

</div>

<!-- TABLE -->

<div class="scroll-box">

    <div class="table-wrapper">

<table
id="tabel-soap"
class="display nowrap">
<div class="scroll-box">

<thead>

<tr>

<th>No</th>

<th>Nama Pasien</th>
<th>No Rawat</th>
<th>No RM</th>

<th>Tanggal</th>
<th>Jam</th>

<th>Petugas & Dokter</th>

<th>Poli</th>

<th>Suhu</th>
<th>Tensi</th>
<th>Nadi</th>
<th>Respirasi</th>
<th>Tinggi</th>
<th>Berat</th>
<th>SPO2</th>
<th>GCS</th>
<th>Kesadaran</th>
<th>Alergi</th>
<th>Lingkar Perut</th>

<th>Keluhan</th>
<th>Pemeriksaan</th>
<th>Penilaian</th>
<th>RTL</th>
<th>Instruksi</th>
<th>Evaluasi</th>

</tr>

</thead>

<tbody>

<?php

while($row = mysqli_fetch_assoc($query)){

?>

<tr>

<td></td>



<td><?= $row['nm_pasien'] ?></td>

<td><?= $row['no_rawat'] ?></td>

<td><?= $row['no_rkm_medis'] ?></td>

<td><?= $row['tgl_perawatan'] ?></td>

<td><?= $row['jam_rawat'] ?></td>

<!-- PETUGAS + DOKTER -->

<td>

<div style="display:flex; flex-direction:column; gap:5px;">

    <span class="badge">

        Petugas :
        <?= $row['nama_petugas'] ?>

    </span>

    <span class="badge"
    style="background:#9333ea;">

        Dokter :
        <?= $row['nm_dokter'] ?>

    </span>

</div>

</td>

<td><?= $row['nm_poli'] ?></td>

<td><?= $row['suhu_tubuh'] ?></td>

<td><?= $row['tensi'] ?></td>

<td><?= $row['nadi'] ?></td>

<td><?= $row['respirasi'] ?></td>

<td><?= $row['tinggi'] ?></td>

<td><?= $row['berat'] ?></td>

<td><?= $row['spo2'] ?></td>

<td><?= $row['gcs'] ?></td>

<td><?= $row['kesadaran'] ?></td>

<td><?= $row['alergi'] ?></td>

<td><?= $row['lingkar_perut'] ?></td>

<td><?= $row['keluhan'] ?></td>

<td><?= $row['pemeriksaan'] ?></td>

<td><?= $row['penilaian'] ?></td>

<td><?= $row['rtl'] ?></td>

<td><?= $row['instruksi'] ?></td>

<td><?= $row['evaluasi'] ?></td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

<script>

$(document).ready(function() {

var table = $('#tabel-soap').DataTable({

responsive:true,

processing:true,

paging:true,

pageLength:20,

lengthMenu:[
[10,20,50,100],
[10,20,50,100]
],

order:[[4,'desc']],

dom:'Bfrtip',

buttons:[

{
extend:'excelHtml5',
title:'DATA SOAP PASIEN'
},

{
extend:'pdfHtml5',
title:'DATA SOAP PASIEN',
orientation:'landscape',
pageSize:'A3'
},

{
extend:'print',
title:'DATA SOAP PASIEN'
}

],

language:{

search:"Pencarian Nama Medis/Dokter :",

lengthMenu:"Tampilkan _MENU_ data",

info:"Menampilkan _START_ sampai _END_ dari _TOTAL_ data",

paginate:{
previous:"←",
next:"→"
},

zeroRecords:"Data tidak ditemukan",

processing:"Loading..."

},

columnDefs:[

{
searchable:false,
orderable:false,
targets:0
}

]

});

// NOMOR OTOMATIS

table.on(
'order.dt search.dt draw.dt',
function () {

let start = table.page.info().start;

table.column(0,{
search:'applied',
order:'applied'
}).nodes().each(function(cell,i){

cell.innerHTML = start + i + 1;

});

}).draw();

});

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
