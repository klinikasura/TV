<?php

include "koneksi2.php";

if(isset($_POST['simpan'])){

    $tanggal=date("Y-m-d H:i:s");

    $kode_brng=$_POST['kode_brng'];
    $nama_brng=$_POST['nama_brng'];

    $stok_awal=0;

    $jumlah_keluar=$_POST['jumlah_keluar'];

    $stok_akhir=$stok_awal-$jumlah_keluar;

    $tujuan=$_POST['tujuan'];

    $petugas=$_POST['petugas'];

    $keterangan=$_POST['keterangan'];

    mysqli_query($koneksi,"
    INSERT INTO robotv80_obat_keluar(
        tanggal,
        kode_brng,
        nama_brng,
        stok_awal,
        jumlah_keluar,
        stok_akhir,
        tujuan,
        petugas,
        keterangan
    )
    VALUES(
        '$tanggal',
        '$kode_brng',
        '$nama_brng',
        '$stok_awal',
        '$jumlah_keluar',
        '$stok_akhir',
        '$tujuan',
        '$petugas',
        '$keterangan'
    )
    ");

    echo "
    <script>
        alert('Data berhasil disimpan');
        window.location='cek-so.php';
    </script>
    ";
}

?>

<!DOCTYPE html>
<html>
<head>

 <title>myROBOT-V80</title>
  <link href="http://10.10.20.250/dashboard/APPS-ROBOT/BUILDING APLIKASI/@API-GITHUB-V80/ROBOT-GITHUB/ROBOTV80.png" rel="icon" type="image/png" />
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet"
href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">

<link rel="stylesheet"
href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<style>

body{
    background:#f4f6f9;
    font-family:Arial;
}

.container{
    width:95%;
    margin:auto;
}

.card{
    background:white;
    padding:20px;
    border-radius:15px;
    margin-top:20px;
    box-shadow:0 0 10px rgba(0,0,0,.1);
}

input,
select,
textarea{
    width:100%;
    padding:12px;
    border:1px solid #ccc;
    border-radius:10px;
    margin-bottom:15px;
}

button{
    background:#0d6efd;
    color:white;
    border:none;
    padding:12px 20px;
    border-radius:10px;
    cursor:pointer;
}

button:hover{
    background:#0b5ed7;
}

.stok{
    background:#d1ecf1;
    padding:15px;
    border-radius:10px;
    margin-bottom:15px;
    font-size:20px;
}
.btn-edit{
    background:#ffc107;
    color:black;
    padding:7px 12px;
    border-radius:8px;
    text-decoration:none;
    display:inline-block;
}

.btn-edit:hover{
    background:#e0a800;
}

.btn-hapus{
    background:#dc3545;
    color:white;
    padding:7px 12px;
    border-radius:8px;
    text-decoration:none;
    display:inline-block;
}

.btn-hapus:hover{
    background:#bb2d3b;
}
.btn-print{
    background:#28a745;
    color:white;
    padding:12px 20px;
    border-radius:10px;
    text-decoration:none;
    display:inline-block;
    margin-left:10px;
}

.btn-print:hover{
    background:#218838;
}
/* ==========================
   RESPONSIVE MOBILE
========================== */

@media screen and (max-width:768px){

    body{
        padding:10px;
        font-size:14px;
    }

    .container{
        width:100%;
        margin:0;
        padding:0;
    }

    .card{
        padding:15px;
        border-radius:12px;
        margin-top:10px;
    }

    h2{
        font-size:20px;
        margin-bottom:15px;
    }

    input,
    select,
    textarea{
        width:100%;
        padding:12px;
        font-size:14px;
        box-sizing:border-box;
    }

    button{
        width:100%;
        padding:14px;
        font-size:15px;
        margin-top:5px;
    }

    .btn-print{
        width:100%;
        display:block;
        text-align:center;
        margin-top:10px;
        margin-left:0;
        padding:14px;
        box-sizing:border-box;
    }

    .stok{
        font-size:16px;
        padding:12px;
    }

    /* Filter laporan */
    .filter-row{
        display:block !important;
    }

    .filter-row div{
        width:100%;
        margin-bottom:10px;
    }

    /* DataTables responsive */
    div.dataTables_wrapper{
        overflow-x:auto;
    }

    table.dataTable{
        width:100% !important;
        font-size:12px;
    }

    table.dataTable th,
    table.dataTable td{
        white-space:nowrap;
        padding:8px;
    }

}

/* HP kecil */
@media screen and (max-width:480px){

    h2{
        font-size:18px;
    }

    .stok{
        font-size:15px;
    }

    input,
    select,
    textarea{
        font-size:13px;
    }

    table.dataTable{
        font-size:11px;
    }

}

</style>

</head>

<body>

<div class="container">

<div class="card">

<h2>Input Obat Keluar (APPS myROBOT-V80)</h2>

<form method="POST">

<input type="hidden"
       name="kode_brng"
       id="kode_brng">
<input type="hidden" name="kode_brng" id="kode_brng">

<label>Nama Obat</label>

<input type="text"
       name="nama_brng"
       id="nama_obat"
       autocomplete="off"
       required>

<div class="stok">
Total Stok Saat Ini :
<b><span id="stok">0</span></b>
</div>

<label>Jumlah Keluar</label>

<input type="number"
       name="jumlah_keluar"
       required>

<label>Poli Tujuan</label>

<select name="tujuan" required>

<option value="">
Pilih Poli
</option>

<?php

$poli=mysqli_query($koneksi,"
SELECT nm_poli
FROM poliklinik
WHERE status='1'
ORDER BY nm_poli
");

while($p=mysqli_fetch_assoc($poli)){

?>

<option value="<?=$p['nm_poli'];?>">
<?=$p['nm_poli'];?>
</option>

<?php } ?>

</select>

<label>Petugas</label>

<select name="petugas" required>

<option value="">
Pilih Petugas
</option>

<?php

$pegawai=mysqli_query($koneksi,"
SELECT
    nama,
    jnj_jabatan
FROM pegawai
WHERE stts_aktif='AKTIF'
ORDER BY nama
");

while($pg=mysqli_fetch_assoc($pegawai)){

?>

<option value="<?=$pg['nama'];?>">
<?=$pg['nama'];?>
</option>

<?php } ?>

</select>

<label>Keterangan</label>

<textarea name="keterangan"></textarea>

<button type="submit" name="simpan">
Simpan Data
</button>

</form>

</div>

<div class="card">

<div class="card">

<h2>Filter Laporan</h2>

<form method="GET">

<div style="display:flex;gap:10px;flex-wrap:wrap;">

<div>
<label>Dari Tanggal</label>
<input type="date"
       name="tgl1"
       value="<?= $_GET['tgl1'] ?? '' ?>">
</div>

<div>
<label>Sampai Tanggal</label>
<input type="date"
       name="tgl2"
       value="<?= $_GET['tgl2'] ?? '' ?>">
</div>

<div>
<label>Bulan</label>
<input type="month"
       name="bulan"
       value="<?= $_GET['bulan'] ?? '' ?>">
</div>

<div style="margin-top:28px;">

<button type="submit">
Filter
</button>

<a href="cetak_obat_keluar.php?tgl1=<?= $_GET['tgl1'] ?? '' ?>&tgl2=<?= $_GET['tgl2'] ?? '' ?>&bulan=<?= $_GET['bulan'] ?? '' ?>"
target="_blank"
class="btn-print">
Cetak
</a>

</div>

</div>

</form>

</div>

<h2>Data Obat Keluar</h2>

<table id="table" class="display">

<thead>
<tr>
<th>Tanggal</th>
<th>Nama Obat</th>
<th>Jumlah</th>
<th>Poli</th>
<th>Petugas</th>
<th>Aksi</th>
</tr>
</thead>

<tbody>

<?php

$where="";

if(!empty($_GET['tgl1']) && !empty($_GET['tgl2'])){

    $tgl1=$_GET['tgl1'];
    $tgl2=$_GET['tgl2'];

    $where.=" AND DATE(tanggal)
              BETWEEN '$tgl1'
              AND '$tgl2'";
}

if(!empty($_GET['bulan'])){

    $bulan=$_GET['bulan'];

    $where.=" AND DATE_FORMAT(tanggal,'%Y-%m')
              ='$bulan'";
}

$q=mysqli_query($koneksi,"
SELECT *
FROM robotv80_obat_keluar
WHERE 1=1
$where
ORDER BY tanggal DESC
");
while($d=mysqli_fetch_assoc($q)){

?>

<tr>
<td><?=$d['tanggal']?></td>
<td><?=$d['kode_brng']?></td>
<td><?=$d['nama_brng']?></td>
<td><?=$d['jumlah_keluar']?></td>
<td><?=$d['tujuan']?></td>
<td><?=$d['petugas']?></td>

<td>

<a href="edit_obat_keluar.php?id=<?=$d['id']?>"
class="btn-edit">
✏ Edit
</a>

<a href="hapus_obat_keluar.php?id=<?=$d['id']?>"
class="btn-hapus"
onclick="return confirm('Yakin ingin menghapus data ini?')">
🗑 Hapus
</a>

</td>
</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

<script>

$(document).ready(function(){

    $('#table').DataTable();

    $("#nama_obat").autocomplete({

        source:'cari_obat.php',

        minLength:1,

        select:function(event,ui){

            $("#nama_obat").val(ui.item.value);

            $("#kode_brng").val(ui.item.kode);

            $("#stok").text(ui.item.stok);

            return false;
        }

    });

});

</script>

</body>
</html>
