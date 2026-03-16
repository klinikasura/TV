<?php 
date_default_timezone_set("Asia/Jakarta");

/* ================= TANGGAL ================= */
$tanggal = date("d-m-Y");

/* ================= AMBIL JADWAL SHOLAT ================= */
$url = "http://api.aladhan.com/v1/timingsByCity?city=Oki&country=Indonesia&method=2";
$response = @file_get_contents($url);

$imsak = "--:--";
$maghrib = "--:--";

if($response !== FALSE){
    $data = json_decode($response, true);
    if(isset($data['data']['timings'])){
        $imsak   = substr($data['data']['timings']['Imsak'],0,5);
        $maghrib = substr($data['data']['timings']['Maghrib'],0,5);
    }
}

/* ================= DAFTAR HADIST ================= */
$hadist = [
"Rasulullah SAW bersabda: Puasa adalah perisai (HR. Bukhari & Muslim).",
"Barangsiapa berpuasa Ramadhan karena iman dan mengharap pahala, diampuni dosa-dosanya yang telah lalu (HR. Bukhari).",
"Bau mulut orang berpuasa lebih harum di sisi Allah daripada aroma kasturi (HR. Muslim).",
"Puasa dan Al-Qur'an akan memberi syafaat kepada seorang hamba pada hari kiamat (HR. Ahmad).",
"Setiap amal anak Adam dilipatgandakan, kecuali puasa. Puasa itu untuk-Ku dan Aku yang membalasnya (HR. Bukhari).",
"Ada pintu di surga bernama Ar-Rayyan, yang hanya dimasuki orang-orang berpuasa (HR. Bukhari & Muslim).",
"Orang yang berpuasa memiliki dua kebahagiaan: saat berbuka dan saat bertemu Rabb-nya (HR. Muslim)."
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Aplikasi RS. Asura</title>

<style>
body{
    margin:0;
    background:#0b3d91;
    overflow:hidden;
    font-family:Tahoma, Arial, sans-serif;
}

.banner{
    width:100%;
    height:60px;
    position:fixed;
    top:0;
    left:0;
    overflow:hidden;
    background:#0b3d91;
    border-top:3px solid #fff;
    border-bottom:3px solid #fff;
    display:flex;
    align-items:flex-start;   /* ganti ini */
    padding-top:2px;         /* atur naik turunnya di sini */
}

/* Running text */
#runningText{
    white-space:nowrap;
    font-size:20px;
    font-weight:bold;
    letter-spacing:1.5px;
    color:white;
    padding-left:100%;
}

/* Hadist color */
.hadist{
    color:#ffd700;
}
</style>
</head>

<body>

<div class="banner">
    <div id="runningText"></div>
</div>

<script>
var daftarHadist = <?php echo json_encode($hadist); ?>;
var indexHadist = 0;
var runningText = document.getElementById("runningText");

function mulaiRunning(){

    var teks =
        "Ramadhan 1447 H • " +
        "Tanggal <?= $tanggal; ?> • " +
        "Imsak <?= $imsak; ?> WIB • " +
        "Maghrib <?= $maghrib; ?> WIB • " +
        "HADIST: " + daftarHadist[indexHadist] + " • ";

    runningText.innerHTML = teks.replace(
        daftarHadist[indexHadist],
        '<span class="hadist">'+daftarHadist[indexHadist]+'</span>'
    );

    // reset animasi
    runningText.style.transition = "none";
    runningText.style.transform = "translateX(0)";
    void runningText.offsetWidth;

    var lebarText = runningText.offsetWidth;
    var lebarLayar = window.innerWidth;

    // kecepatan (pixel per detik)
    var speed = 80; // makin kecil = makin lambat
    var durasi = (lebarText + lebarLayar) / speed;

    runningText.style.transform = "translateX(" + lebarLayar + "px)";
    runningText.style.transition = "transform " + durasi + "s linear";

    setTimeout(function(){
        runningText.style.transform = "translateX(-" + lebarText + "px)";
    }, 50);

    setTimeout(function(){
        indexHadist++;
        if(indexHadist >= daftarHadist.length){
            indexHadist = 0;
        }
        mulaiRunning();
    }, durasi * 1000);
}

mulaiRunning();
</script>

</body>
</html>
