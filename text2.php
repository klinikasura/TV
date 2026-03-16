<?php
date_default_timezone_set("Asia/Jakarta");

/* ================= TANGGAL ================= */
$tanggal = date("d-m-Y H:i");

/* ================= AMBIL RSS BERITA ================= */
/* Sumber: CNN Indonesia */
$rssUrl = "https://www.cnnindonesia.com/nasional/rss";

$newsItems = [];

$rss = @simplexml_load_file($rssUrl);

if($rss !== false){
    $count = 0;
    foreach($rss->channel->item as $item){
        $newsItems[] = trim((string)$item->title);
        if(++$count >= 7) break; // ambil 7 berita terbaru
    }
}

/* Jika gagal ambil RSS */
if(empty($newsItems)){
    $newsItems[] = "Berita tidak tersedia saat ini.";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Breaking News Online</title>

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

    border-top:3px solid #fff;
    border-bottom:3px solid #fff;
    display:flex;
    align-items:center;
    align-items:flex-start;   /* ganti ini */
    padding-top:0px;         /* atur naik turunnya di sini */
}

#runningText{
    white-space:nowrap;
    font-size:22px;
    font-weight:bold;
    letter-spacing:1.5px;
    color:white;
    padding-left:100%;
}
</style>
</head>

<body>

<div class="banner">
    <div id="runningText"></div>
</div>

<script>
var daftarNews = <?php echo json_encode($newsItems); ?>;
var indexNews = 0;
var runningText = document.getElementById("runningText");

function mulaiRunning(){

    var teks =
        "🔴 BREAKING NEWS • " +
        "Update: <?= $tanggal; ?> WIB • " +
        daftarNews[indexNews] + " • ";

    runningText.innerHTML = teks;

    runningText.style.transition = "none";
    runningText.style.transform = "translateX(0)";
    void runningText.offsetWidth;

    var lebarText = runningText.offsetWidth;
    var lebarLayar = window.innerWidth;

    var speed = 90; 
    var durasi = (lebarText + lebarLayar) / speed;

    runningText.style.transform = "translateX(" + lebarLayar + "px)";
    runningText.style.transition = "transform " + durasi + "s linear";

    setTimeout(function(){
        runningText.style.transform = "translateX(-" + lebarText + "px)";
    }, 50);

    setTimeout(function(){
        indexNews++;
        if(indexNews >= daftarNews.length){
            indexNews = 0;
        }
        mulaiRunning();
    }, durasi * 1000);
}

mulaiRunning();

/* Auto refresh berita setiap 15 menit */
setInterval(function(){
    location.reload();
}, 900000);
</script>

</body>
</html>
