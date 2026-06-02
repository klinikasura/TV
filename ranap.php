<!DOCTYPE html>
<html>
<head>
<title>Aplikasi RS. Asura</title>
<link href="http://10.10.20.250/dashboard/download.jpeg" rel="icon" type="image/png">
<meta charset="UTF-8">

<style>
body{
    font-family: Arial, Helvetica, sans-serif;
    background:#fff;
    margin:0;
    padding:10px;
}

.judul{
    text-align:center;
    font-size:17px;
    color:red;
    font-weight:bold;
    margin-bottom:8px;
}

#text{
    padding:5px;
    min-height:120px;
    font-size:14px;
    line-height:1.2;
    color:#000;
}

.kosong{
    color:red;
    font-size:15px;
    font-weight:bold;
    text-align:center;
    animation:kedip 1s infinite;
}

.nilai{
    color:green;
    font-weight:bold;
}

@keyframes kedip{
    0%{opacity:1;}
    50%{opacity:0.2;}
    100%{opacity:1;}
}
</style>
</head>

<body>

<?php
require_once('conf/conf.php');

date_default_timezone_set("Asia/Bangkok");

$servername = "10.10.20.250";
$username   = "root";
$password   = "";
$dbname     = "sikdraisyah";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal : " . $conn->connect_error);
}

$sql = "SELECT
            ki.no_rawat,
            b.nm_bangsal,
            k.kelas,
            rp.tgl_registrasi,
            p.nm_pasien,
            IFNULL(ki.diagnosa_awal,'-') AS diagnosa
        FROM kamar_inap ki
        JOIN kamar k ON ki.kd_kamar = k.kd_kamar
        JOIN bangsal b ON k.kd_bangsal = b.kd_bangsal
        JOIN reg_periksa rp ON ki.no_rawat = rp.no_rawat
        JOIN pasien p ON rp.no_rkm_medis = p.no_rkm_medis
        WHERE k.status='ISI'
        AND ki.stts_pulang='-'
        ORDER BY b.nm_bangsal ASC";

$result = $conn->query($sql);

$data = [];

if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $data[] = $row;
    }
}

$jumlah_pasien = count($data);

$conn->close();
?>

<div class="judul">
(<?= $jumlah_pasien ?>) PASIEN RAWAT INAP
</div>

<div id="text"></div>

<script>

var data   = <?= json_encode($data); ?>;
var jumlah = <?= $jumlah_pasien ?>;

var text = document.getElementById("text");

if(jumlah === 0){

    text.innerHTML =
    '<div class="kosong">KOSONG</div>';

}else{

    var indexPasien = 0;

    function ketikBaris(baris, callback){

        text.innerHTML = "";

        let noBaris = 0;

        function tampilBaris(){

            if(noBaris >= baris.length){

                setTimeout(function(){
                    callback();
                }, 3000);

                return;
            }

            let div = document.createElement("div");

            div.innerHTML =
            baris[noBaris][0] +
            ' : <span class="nilai"></span>';

            text.appendChild(div);

            let span = div.querySelector(".nilai");

            let huruf = 0;

            let interval = setInterval(function(){

                span.innerHTML += baris[noBaris][1].charAt(huruf);

                huruf++;

                if(huruf >= baris[noBaris][1].length){

                    clearInterval(interval);

                    noBaris++;

                    setTimeout(tampilBaris, 200);

                }

            }, 25);

        }

        tampilBaris();
    }

    function tampilPasien(){

        let p = data[indexPasien];

        let baris = [

            ["Nama Pasien", p.nm_pasien],
            ["No Rawat", p.no_rawat],
            ["Ruang", p.nm_bangsal + " - " + p.kelas],
            ["Tgl Masuk", p.tgl_registrasi],
            ["Diagnosa", p.diagnosa]

        ];

        ketikBaris(baris, function(){

            indexPasien++;

            if(indexPasien >= data.length){
                indexPasien = 0;
            }

            tampilPasien();

        });

    }

    tampilPasien();

}

</script>

</body>
</html>
```

