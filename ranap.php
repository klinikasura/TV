<title>Aplikasi RS. Asura</title>
<link href="http://10.10.20.250/dashboard/download.jpeg" rel="icon" type="image/png">

<?php
require_once('conf/conf.php');
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
date_default_timezone_set("Asia/Bangkok");

$tanggal= mktime(date("m"),date("d"),date("Y"));
$jam=date("H:i");
?>



<?php
// ================== KONEKSI DATABASE ==================
$servername = "10.10.20.250";
$username = "root";
$password = "";
$dbname = "sikdraisyah";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

// ================== QUERY PASIEN RAWAT INAP ==================
$sql = "SELECT 
ki.no_rawat,
k.kd_kamar,
b.nm_bangsal,
k.trf_kamar,
k.status,
k.kelas,
rp.no_reg,
rp.tgl_registrasi,
rp.stts,
p.nm_pasien
FROM kamar_inap ki
JOIN kamar k ON ki.kd_kamar = k.kd_kamar
JOIN bangsal b ON k.kd_bangsal = b.kd_bangsal
JOIN reg_periksa rp ON ki.no_rawat = rp.no_rawat
JOIN pasien p ON rp.no_rkm_medis = p.no_rkm_medis
WHERE k.status = 'ISI'
AND ki.stts_pulang = '-'";

$result = $conn->query($sql);
$data = array();

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $data[] = $row;
  }
}

// jumlah pasien
$jumlah_pasien = count($data);

$conn->close();
?>

<style>
body{
    font-family: Arial, Helvetica, sans-serif;
    background:white;
}

/* judul */
.judul{
    font-size:19px;

    color:red;
    text-align:center;
  
}

/* area running */
#marquee{
    font-size:15px;
    color:green;
    overflow:hidden;
    white-space:nowrap;


}

#text{
    display:inline-block;
    padding:8px;
}

.nama{
    font-weight:bold;
    font-size:16px;
    color:#008000;
}

/* tulisan kosong */
.kosong{
    font-size:19px;
    color:red;
    font-weight:bold;
    margin-top:8px;

    animation: kedip 1.2s infinite;
}

@keyframes kedip{
    0%{opacity:1;}
    50%{opacity:0.2;}
    100%{opacity:1;}
}
</style>


<!-- JUMLAH KAMAR TERISI -->
<div class="judul">
(<?php
$tes=("select count(status) as jml from kamar where status = 'ISI'");
$hasil=bukaquery($tes);
while ($dataj = mysqli_fetch_array ($hasil)){
  $jml= ($dataj['jml']);
}
echo $jml;
?>) PASIEN RAWAT INAP</div>


<div id="marquee">
    <div id="text"></div>
</div>

<script>
var data   = <?= json_encode($data); ?>;
var jumlah = <?= $jumlah_pasien ?>;
var text   = document.getElementById('text');

// =================== JIKA TIDAK ADA PASIEN ===================
if(jumlah === 0){

    text.innerHTML =
    `<div class="kosong">
     KOSONG
     </div>`;

}
// =================== JIKA ADA PASIEN ===================
else{

    var i = 0;

    function tampil(){
        var pasien = data[i];

        text.innerHTML =
        `<div>
            <div class="nama">${pasien.nm_pasien}</div>
            No Rawat : ${pasien.no_rawat}<br>
            Ruang    : ${pasien.nm_bangsal} - Kelas ${pasien.kelas}<br>
            Tanggal Masuk : ${pasien.tgl_registrasi}
        </div>`;

        i++;
        if(i >= data.length) i = 0;
    }

    tampil();
    setInterval(tampil,5000);
}
</script>

