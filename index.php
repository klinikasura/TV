<?php // -------------------------------------------------- // Konfigurasi dasar
require_once('conf/conf.php');
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
date_default_timezone_set("Asia/Bangkok");
$tanggal = mktime(date("m"),date("d"),date("Y"));
$jam = date("H:i");
$video_id = 'DOOrIxw5xOw';
?>



<!DOCTYPE html>
<html>
<head>
 <title>myROBOT-V80</title>
  <link href="http://10.10.20.250/dashboard/APPS-ROBOT/BUILDING APLIKASI/@API-GITHUB-V80/ROBOT-GITHUB/ROBOTV80.png" rel="icon" type="image/png" />
<link rel="stylesheet" href="style-tv.css">
 <meta http-equiv="refresh" content="600;url=index.php">


    <!-- CSS umum -->
    <style>
        body { margin:0; padding:0; overflow:hidden; }
        .iframe-container {
            position:fixed; top:0; left:0; width:100%; height:100%;
            display:flex; flex-direction:column;
        }
        .iframe-container iframe { width:100%; border:none; }
        .iframe-container iframe:nth-child(1) { height:300px; }
        .iframe-container iframe:nth-child(2) { flex-grow:1; }

        #fullScreenBtn {
            position:fixed; top:11px; left:18px; z-index:9999;
            background:#1e88e5; color:#FFF; padding:10px 30px;
            border:none; border-radius:100px; cursor:pointer;
        }
        #fullScreenBtn:hover { background:#3e8e41; }

        a {
            text-decoration:none; color:#FFF; background:#4CAF50;
            padding:10px 20px; border-radius:5px; margin:5px;
        }
        a:hover { background:#3e8e41; }

        
        /* ★ Efek Salju ★ */
        .snow {
            position:fixed; top:0; left:0; width:100%; height:100%;
            pointer-events:none; z-index:9998;
        }
        .snowflake {
            position:absolute; background:#fff; border-radius:50%;
            animation: fall linear infinite, drift ease-in-out infinite;
        }
        @keyframes fall {
            0% { top:-10px; }
            100% { top:100vh; }
        }
        @keyframes drift {
            0% { transform: translateX(0); }
            50% { transform: translateX(25px); }
            100% { transform: translateX(0); }
        }




/* ===== LOGO RAMADHAN MELAYANG ===== */
#ramadhan-logo{
    position: fixed;
    top: 10px;           /* turun dari atas (biar agak tengah) */
    left: 1190px;          /* pojok kiri */
    width: 40px;        /* ukuran sedang */
    opacity: 0.88;       /* transparan */
    z-index: 9999;
    pointer-events: none;  /* tidak bisa di klik (aman) */
    animation: ramadhanFloat 4s ease-in-out infinite;
}

/* efek gerak pelan (naik turun) */
@keyframes ramadhanFloat{
    0%   { transform: translateY(0px); }
    50%  { transform: translateY(-12px); }
    100% { transform: translateY(0px); }
}

/* efek glow lembut */
#ramadhan-logo img{
    width: 100%;
    filter: drop-shadow(0 0 8px rgba(255,215,120,0.6));
}






    </style>




<!-- CODING TAMPILKAN PASIEN BARU DAFTAR DAN SUDAH BAYAR -->





<style>
@keyframes slideIn {
    0% { transform: translateX(120%); opacity:0; }
    100% { transform: translateX(0); opacity:1; }
}

@keyframes slideOut {
    0% { transform: translateX(0); opacity:1; }
    100% { transform: translateX(120%); opacity:0; }
}

@keyframes slideInLeft {
    0% {
        transform: translateX(-120%);
        opacity: 0;
    }
    100% {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOutLeft {
    0% {
        transform: translateX(0);
        opacity: 1;
    }
    100% {
        transform: translateX(-120%);
        opacity: 0;
    }
}
</style>


<!-- CODING TAMPILKAN PASIEN BARU DAFTAR DAN SUDAH BAYAR -->

</head>
<body>




<!-- CODING TAMPILKAN PASIEN BARU DAFTAR DAN SUDAH BAYAR -->





<audio id="notifSound" preload="auto">
    <source src="AUDIO/BAYAR2.mp3" type="audio/mpeg">
</audio>

<audio id="notifBayar" preload="auto">
    <source src="AUDIO/BAYAR2.mp3" type="audio/mpeg">
</audio>





<!-- CODING TAMPILKAN PASIEN BARU DAFTAR  -->

<script>
let dataSebelumnya = [];

// 🔊 suara
function playNotif() {
    const audio = document.getElementById("notifSound");
    audio.currentTime = 0;
    audio.play().catch(() => {});
}

// 🎨 warna berdasarkan cara bayar
function getWarna(cara) {
    if (!cara) return "#6c757d";

    cara = cara.toLowerCase();

    if (cara.includes("bpjs")) return "#007bff"; // biru
    if (cara.includes("umum")) return "#28a745"; // hijau

    return "#ff9800"; // lainnya
}

// 🎬 popup
function tampilkanPopup(pasien) {
    const popup = document.createElement("div");

    let warna = getWarna(pasien.cara_bayar);

    popup.style.position = "fixed";
    popup.style.top = (20 + (Math.random()*50)) + "px"; 
    popup.style.right = "20px";
    popup.style.background = warna;
    popup.style.color = "white";
    popup.style.padding = "20px 30px";
    popup.style.borderRadius = "12px";
    popup.style.boxShadow = "0 0 25px rgba(0,0,0,0.4)";
    popup.style.zIndex = "99999";
    popup.style.fontSize = "20px";
    popup.style.minWidth = "300px";
    popup.style.animation = "slideIn 0.80s ease";

    popup.innerHTML = `
        <div style="font-size:14px;opacity:0.8;">INFORMASI : Pasien Baru </div>
        <div style="font-size:24px;font-weight:bold;margin:5px 0;">
            ${pasien.nm_pasien}
        </div>
        <div style="font-size:16px;">
            Ke : ${pasien.nm_poli || '-'}
        </div>
        <div style="font-size:14px;opacity:0.9;">
            Pembayaran : ${pasien.cara_bayar || '-'}
        </div>
    `;

    document.body.appendChild(popup);

bicara(`Pasien baru atas nama ${pasien.nm_pasien}, ${pasien.nm_poli}`);


    // 🔊 bunyi
    playNotif();

    // ❌ hilang
    setTimeout(() => {
        popup.style.animation = "slideOut 0.80s ease";
        setTimeout(() => popup.remove(), 800);
    }, 8000);
}

// 🔍 MONITOR DATA REALTIME
setInterval(function() {
    fetch('get_pasien2.php')
        .then(r => r.json())
        .then(dataBaru => {

            if (dataSebelumnya.length > 0) {

                dataBaru.forEach(pasienBaru => {

                    let pasienLama = dataSebelumnya.find(
                        d => d.no_rawat === pasienBaru.no_rawat
                    );

                    // 🆕 PASIEN BARU
                    if (!pasienLama) {
                        tampilkanPopup(pasienBaru);
                    }

                    // 💰 STATUS BAYAR BERUBAH
                    if (
                        pasienLama &&
                        pasienLama.status_bayar === "Belum Bayar" &&
                        pasienBaru.status_bayar === "Sudah Bayar"
                    ) {
                        tampilkanPopupBayar(pasienBaru);
                    }

                });
            }

            dataSebelumnya = dataBaru;

        })
        .catch(e => console.error(e));

}, 1000);

</script>




<!-- CODING TAMPILKAN PASIEN SUDAH BAYAR -->



<script>
function playNotifBayar() {
    const audio = document.getElementById("notifBayar");
    if (audio) {
        audio.currentTime = 0;
        audio.play().catch(() => {});
    }
}

function tampilkanPopupBayar(pasien) {
    const popup = document.createElement("div");

   popup.style.position = "fixed";
  popup.style.top = (20 + (Math.random()*50)) + "px";    // 🔥 PINDAH KE ATAS
    popup.style.left = "20px";   // 🔥 DI KIRI
    popup.style.right = "auto";

    popup.style.background = "#00c853"; // hijau terang
    popup.style.color = "white";
    popup.style.padding = "20px 30px";
    popup.style.borderRadius = "12px";
    popup.style.boxShadow = "0 0 25px rgba(0,0,0,0.4)";
    popup.style.zIndex = "99999";
    popup.style.fontSize = "20px";
    popup.style.minWidth = "300px";
    popup.style.animation = "slideInLeft 0.80s ease";

    popup.innerHTML = `
        <div style="font-size:14px;">INFORMASI : Pembayaran Selesai </div>
        <div style="font-size:24px;font-weight:bold;">
           Atas Nama : ${pasien.nm_pasien}
        </div>
        <div> Dari : ${pasien.nm_poli}</div>
    `;

    document.body.appendChild(popup);

bicara(`Pasien atas nama ${pasien.nm_pasien} telah menyelesaikan pembayaran`);

    playNotifBayar();

    setTimeout(() => {
        popup.style.animation = "slideOut 0.80s ease";
        setTimeout(() => popup.remove(), 800);
    }, 8000);
}
</script>



<!-- CODING GOOGLE SUARA TAMPILKAN PASIEN BARU DAFTAR DAN SUDAH BAYAR -->


<script>
function bicara(teks) {
    const speech = new SpeechSynthesisUtterance(teks);

    speech.lang = "id-ID"; // Bahasa Indonesia
    speech.rate = 0.9;     // kecepatan
    speech.pitch = 1;      // nada

    window.speechSynthesis.speak(speech);
}
</script>




<!-- CODING TAMPILKAN PASIEN BARU DAFTAR DAN SUDAH BAYAR -->



<script>
// ================= GLOBAL =================
let dataSebelumnya = [];
let modeAdzan = false;
let antrianNotif = [];
let modeQueue = true; // true = ditunda saat adzan

// ================= AUDIO CONTROL =================
function muteSemuaAudio() {
    document.querySelectorAll("audio").forEach(el => {
        if (el.id !== "audioAdzan") {
            el.pause();
            el.currentTime = 0;
        }
    });

    // 🔥 stop suara robot
    window.speechSynthesis.cancel();
}

function playAdzan() {
    const audio = document.getElementById("audioAdzan");
    audio.volume = 1;
    audio.currentTime = 0;

    audio.play().catch(() => {
        console.log("Klik dulu biar audio aktif");
    });
}

// ================= TEXT TO SPEECH =================
function bicara(teks) {
    if (modeAdzan) return;

    const speech = new SpeechSynthesisUtterance(teks);
    speech.lang = "id-ID";
    speech.rate = 0.4;
    speech.pitch = 1;

    window.speechSynthesis.speak(speech);
}

// ================= AUDIO NOTIF =================
function playNotif() {
    const audio = document.getElementById("notifSound");
    audio.currentTime = 0;
    audio.play().catch(() => {});
}

function playNotifBayar() {
    const audio = document.getElementById("notifBayar");
    audio.currentTime = 0;
    audio.play().catch(() => {});
}

// ================= WARNA =================
function getWarna(cara) {
    if (!cara) return "#6c757d";

    cara = cara.toLowerCase();

    if (cara.includes("bpjs")) return "#007bff";
    if (cara.includes("umum")) return "#28a745";

    return "#ff9800";
}

// ================= QUEUE =================
function jalankanAntrianNotif() {
    if (antrianNotif.length === 0) return;

    let delay = 0;

    antrianNotif.forEach(item => {
        setTimeout(() => {
            if (item.tipe === "baru") tampilkanPopup(item.data, true);
            if (item.tipe === "bayar") tampilkanPopupBayar(item.data, true);
        }, delay);

        delay += 1500;
    });

    antrianNotif = [];
}

// ================= POPUP PASIEN BARU =================
function tampilkanPopup(pasien, pakaiSuara = true) {

    if (modeAdzan) {
        if (modeQueue) {
            antrianNotif.push({ tipe: "baru", data: pasien });
            return;
        } else {
            pakaiSuara = false;
        }
    }

    const popup = document.createElement("div");

    popup.style.position = "fixed";
    popup.style.top = (20 + (Math.random()*50)) + "px";
    popup.style.right = "20px";
    popup.style.background = getWarna(pasien.cara_bayar);
    popup.style.color = "white";
    popup.style.padding = "20px 30px";
    popup.style.borderRadius = "12px";
    popup.style.zIndex = "99999";
    popup.style.animation = "slideIn 0.8s ease";

    popup.innerHTML = `
        <div>INFORMASI : Pasien Baru</div>
        <div style="font-size:22px;font-weight:bold;">
            ${pasien.nm_pasien}
        </div>
        <div>Ke : ${pasien.nm_poli || '-'}</div>
        <div>Cara Pembayaran : ${pasien.cara_bayar || '-'}</div>
    `;

    document.body.appendChild(popup);

    if (pakaiSuara) {
        playNotif();
        bicara(`INFORMASI, Pasien baru atas nama ${pasien.nm_pasien}, Ke ${pasien.nm_poli}`);
    }

    setTimeout(() => {
        popup.remove();
    }, 8000);
}

// ================= POPUP BAYAR =================
function tampilkanPopupBayar(pasien, pakaiSuara = true) {

    if (modeAdzan) {
        if (modeQueue) {
            antrianNotif.push({ tipe: "bayar", data: pasien });
            return;
        } else {
            pakaiSuara = false;
        }
    }

    const popup = document.createElement("div");

    popup.style.position = "fixed";
    popup.style.top = (20 + (Math.random()*50)) + "px";
    popup.style.left = "20px";
    popup.style.background = "#00c853";
    popup.style.color = "white";
    popup.style.padding = "20px 30px";
    popup.style.borderRadius = "12px";
    popup.style.zIndex = "99999";

    popup.innerHTML = `
        <div>INFORMASI : Pembayaran Selesai </div>
        <div style="font-size:22px;font-weight:bold;">
           Atas Nama : ${pasien.nm_pasien}
        </div>
        <div> Dari : ${pasien.nm_poli}</div>
    `;

    document.body.appendChild(popup);

    if (pakaiSuara) {
        playNotifBayar();
        bicara(`INFORMASI, Pasien atas nama ${pasien.nm_pasien} telah menyelesaikan pembayaran`);
    }

    setTimeout(() => {
        popup.remove();
    }, 8000);
}

// ================= MONITOR DATA =================
setInterval(function() {
    fetch('get_pasien2.php')
        .then(r => r.json())
        .then(dataBaru => {

            if (dataSebelumnya.length > 0) {

                dataBaru.forEach(pasienBaru => {

                    let pasienLama = dataSebelumnya.find(
                        d => d.no_rawat === pasienBaru.no_rawat
                    );

                    if (!pasienLama) {
                        tampilkanPopup(pasienBaru);
                    }

                    if (
                        pasienLama &&
                        pasienLama.status_bayar === "Belum Bayar" &&
                        pasienBaru.status_bayar === "Sudah Bayar"
                    ) {
                        tampilkanPopupBayar(pasienBaru);
                    }

                });
            }

            dataSebelumnya = dataBaru;

        })
        .catch(e => console.error(e));

}, 1000);

// ================= ADZAN =================
function showNotifikasiAdzan(sholat) {

    modeAdzan = true;

    muteSemuaAudio();
    playAdzan();

    const box = document.createElement("div");

    box.style.position = "fixed";
    box.style.top = "0";
    box.style.left = "0";
    box.style.width = "100%";
    box.style.height = "100%";
    box.style.background = "black";
    box.style.color = "white";
    box.style.display = "flex";
    box.style.justifyContent = "center";
    box.style.alignItems = "center";
    box.style.fontSize = "60px";
    box.style.zIndex = "9999";

    box.innerHTML = `Saatnya Adzan ${sholat}`;

    document.body.appendChild(box);

    setTimeout(() => {
        box.remove();
        tampilkanCountdownIqomah(sholat);
    }, 240000);
}

// ================= IQOMAH =================
function tampilkanCountdownIqomah(sholat) {

    let sisa = 60;

    const box = document.createElement("div");

    box.style.position = "fixed";
    box.style.top = "0";
    box.style.left = "0";
    box.style.width = "100%";
    box.style.height = "100%";
    box.style.background = "black";
    box.style.color = "white";
    box.style.display = "flex";
    box.style.flexDirection = "column";
    box.style.justifyContent = "center";
    box.style.alignItems = "center";
    box.style.fontSize = "70px";
    box.style.zIndex = "9999";

    document.body.appendChild(box);

    const interval = setInterval(() => {

        let m = Math.floor(sisa / 60);
        let d = sisa % 60;

        box.innerHTML = `
            <h4>Sholat ${sholat}</h4>
            <h2>${m}:${d}</h2>
        `;

        sisa--;

        if (sisa < 0) {
            clearInterval(interval);
            box.remove();

            modeAdzan = false;
            jalankanAntrianNotif();
        }

    }, 1000);
}
</script>







<!-- CODING TAMPILKAN PASIEN BARU DAFTAR DAN SUDAH BAYAR -->
















<!-- LOGO RAMADHAN -->
<div id="ramadhan-logo">
    <img src="JPG/WIFI.webp">
</div>




<!-- PASIEN BELUM BAYAR -->
<?php
// --------------------------------------------------
// Data pasien belum bayar (running text)
$servername = "10.10.20.250";
$username   = "root";
$password   = "";
$dbname     = "sikdraisyah";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Koneksi gagal: ".$conn->connect_error);

$tgl_hari_ini = date('Y-m-d');

$sql = "
    SELECT rp.tgl_registrasi, p.nm_pasien, d.nm_dokter, pl.nm_poli,
           rp.status_bayar, rp.status_lanjut
    FROM reg_periksa rp
    JOIN pasien p   ON rp.no_rkm_medis = p.no_rkm_medis
    JOIN dokter d   ON rp.kd_dokter   = d.kd_dokter
    JOIN poliklinik pl ON rp.kd_poli = pl.kd_poli
    WHERE DATE(rp.tgl_registrasi) = '$tgl_hari_ini'
      AND rp.status_bayar = 'Belum Bayar'
      AND rp.status_lanjut = 'Ralan'
";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) $data[] = $row;
} else {
    $data[] = [
        'nm_pasien' => 'Hore.... Pasien Hari Ini Sudah Periksa Semua',
        'nm_poli'   => '',
        'nm_dokter' => ''
    ];
}
$conn->close();
?>



<!-- CSS PASIEN BELUM BAYAR -->
<style>
    .running-text {
        width:100%; height:40px; line-height:40px;
        background:green; color:white; font-size:18px;
        overflow:hidden; white-space:nowrap; padding:0;
        box-sizing:border-box; font-weight:bold; text-align:center;
    }
    .running-text span { display:inline-block; }
</style>

<style>
    .typing {
        display:inline-block; white-space:nowrap; overflow:hidden;
        border-right:3px solid green;
        animation: typing 8s steps(40,end) infinite,
                   blink 0.5s step-end infinite;
    }
    @keyframes typing {
        0% { width:0; }
        50% { width:16%; }
        100% { width:0; }
    }
    @keyframes blink {
        from, to { border-color:transparent; }
        50% { border-color:green; }
    }
.typing {
  color: green;
  font-size: 18px; /* ukuran sedang */
}
</style>






    <!-- CSS UKURAN TV -->
<style>
    .bell-link { text-decoration:none; color:white; }
    .bell-icon {
        display:inline-block; animation:shake 1s ease-in-out infinite;
        color:#007bff; margin-right:4px;
    }
    @keyframes shake {
        0%,100% { transform:rotate(0deg); }
        25% { transform:rotate(50deg); }
        75% { transform:rotate(-50deg); }
    }
</style>





<!-- TOMBOL -->
<button id="fullScreenBtn">FULL</button>





<!-- ★ Container Salju ★ -->
<div class="" id=""></div>







<!-- Header link -->
<p>
    <a href="#">ROBOT AI</a>
    <a href="http://10.10.20.250/dashboard/APPS-ROBOT/TV/MASTER/jadwal-dokter.php" target="_blank">JADWAL</a>
    <a href="http://10.10.20.250/dashboard/APPS-ROBOT/TV/MASTER/display-otomatis.php" target="_blank">PASIEN</a>
    <a href="http://10.10.20.250/dashboard/APPS-ROBOT/TV/MASTER/display.php" target="_blank">ANTRIAN</a>
    <a href="http://10.10.20.250/dashboard/APPS-ROBOT/TV/MASTER/grafik.php" target="_blank">GRAFIK</a>
    <a href="http://10.10.20.250/dashboard/APPS-ROBOT/TV/MASTER/tiket.php" target="_blank">E-REG</a>
    <a href="http://10.10.20.250/dashboard/APPS-ROBOT/TV/MASTER/farmasi.php" target="_blank" class="bell-link">
        <span class="bell-icon"></span>FARMASI
    </a>
    <a href="http://10.10.20.250/dashboard/APPS-ROBOT/TV/MASTER/lab.php" target="_blank" class="bell-link">
        <span class="bell-icon"></span>LAB
    </a>
    <font color="green" class="typing">&copy; myROBOT-V80</font>
</p>




<div id="notifikasi-adzan"></div>

<audio id="audioAdzan" preload="auto">
  <source src="http://10.10.20.250/dashboard/APPS-ROBOT/TV/AUDIO/ADZAN2-AUDIO.mp3" type="audio/mpeg">
</audio>

<style>
@keyframes fadeIn{
    from{opacity:0}
    to{opacity:1}
}
</style>

<style>
.kedip {
    color: #00ccff; /* biru muda */
    animation: blink 1s infinite;
}

@keyframes blink {
    0% { opacity: 1; }
    50% { opacity: 0.2; }
    100% { opacity: 1; }
}
</style>

<script>
let jadwalSholat = [];
let sudahAdzanHariIni = {};
let iqomahMenit = 1; // UBAH DURASI IQOMAH DI SINI

function muteSemuaAudio() {
    document.querySelectorAll("video, audio").forEach(el => {
        el.dataset.volume = el.volume;
        el.volume = 0;
    });
}

function unmuteSemuaAudio() {
    document.querySelectorAll("video, audio").forEach(el => {
        if (el.dataset.volume) {
            el.volume = el.dataset.volume;
        }
    });
}

function playAdzan() {
    const audio = document.getElementById("audioAdzan");
    audio.currentTime = 0;
    audio.play().catch(() => console.log("Klik layar untuk unlock audio"));
}

/* Unlock autoplay */
document.addEventListener("click", () => {
    const audio = document.getElementById("audioAdzan");
    audio.play().then(() => {
        audio.pause();
        audio.currentTime = 0;
    });
}, { once: true });

function tampilkanCountdownIqomah(sholat) {
    let sisaDetik = iqomahMenit * 60;

    const box = document.createElement("div");
    box.style.position = "fixed";
    box.style.top = "0";
    box.style.left = "0";
    box.style.width = "100%";
    box.style.height = "100%";
    box.style.background = "black";
    box.style.color = "white";
    box.style.display = "flex";
    box.style.flexDirection = "column";
    box.style.justifyContent = "center";
    box.style.alignItems = "center";
    box.style.fontSize = "70px";
    box.style.zIndex = "9999";

    document.body.appendChild(box);

    const interval = setInterval(() => {
        let menit = Math.floor(sisaDetik / 60);
        let detik = sisaDetik % 60;

        box.innerHTML = `
            <h4>Selamat Menunaikan Ibadah Sholat ${sholat}</h4>
            <h2>${String(menit).padStart(2,'0')}:${String(detik).padStart(2,'0')}</h2>
        `;

        sisaDetik--;

        if (sisaDetik < 0) {
            clearInterval(interval);
            document.body.removeChild(box);
            unmuteSemuaAudio();
        }
    }, 1000);
}

function showNotifikasiAdzan(sholat) {
    muteSemuaAudio();
    playAdzan();

    const notifikasi = document.createElement("div");
    notifikasi.style.position = "fixed";
    notifikasi.style.top = "0";
    notifikasi.style.left = "0";
    notifikasi.style.width = "100%";
    notifikasi.style.height = "100%";
    notifikasi.style.backgroundImage = "url('http://10.10.20.250/dashboard/APPS-ROBOT/TV/JPG/MASJID.jpg')";
    notifikasi.style.backgroundSize = "cover";
    notifikasi.style.backgroundPosition = "center";
    notifikasi.style.backgroundRepeat = "no-repeat";
    notifikasi.style.display = "flex";
    notifikasi.style.flexDirection = "column";
    notifikasi.style.justifyContent = "center";
    notifikasi.style.alignItems = "center";
    notifikasi.style.zIndex = "9999";
    notifikasi.style.animation = "fadeIn 2s";

    notifikasi.innerHTML = `
        <h1 style="
            background:rgba(0,0,0,0.55);
            padding:25px 60px;
            border-radius:15px;
            color:white;
            font-size:70px;
            text-align:center;
            backdrop-filter: blur(6px);
        ">
            Saatnya Adzan ${sholat}
        </h1>
    `;

    document.body.appendChild(notifikasi);

    setTimeout(() => {
        document.body.removeChild(notifikasi);
        tampilkanCountdownIqomah(sholat);
    }, 250000); // 4 menit durasi adzan
}

function ambilJadwal() {
    const today = new Date();
    const tanggal = today.getDate();
    const bulan = today.getMonth() + 1;
    const tahun = today.getFullYear();

    const apiUrl = `https://api.aladhan.com/v1/timingsByCity/${tanggal}-${bulan}-${tahun}?city=Oki&country=Indonesia&method=2`;

    fetch(apiUrl)
    .then(res => res.json())
    .then(data => {
        const t = data.data.timings;

        jadwalSholat = [
            { waktu: t.Fajr, sholat: "Subuh" },
            { waktu: t.Dhuhr, sholat: "Dzuhur" },
            { waktu: t.Asr, sholat: "Ashar" },
            { waktu: t.Maghrib, sholat: "Maghrib" },
            { waktu: t.Isha, sholat: "Isya" }
        ];
    });
}

/* Cek waktu setiap detik */
setInterval(() => {
    const now = new Date();
    const jam = String(now.getHours()).padStart(2,'0');
    const menit = String(now.getMinutes()).padStart(2,'0');
    const waktuSekarang = `${jam}:${menit}`;

    jadwalSholat.forEach(j => {
        if (waktuSekarang === j.waktu && !sudahAdzanHariIni[j.sholat]) {
            sudahAdzanHariIni[j.sholat] = true;
            showNotifikasiAdzan(j.sholat);
        }
    });
}, 1000);

ambilJadwal();
</script>






<p>
    <div class="running-text"><span id="text"></span></div>
</p>
















<!-- TABEL PASIEN BELUM BAYAR -->
<script>
// -------------------------------------------------
// Variabel global
var i = 0;
var text = document.getElementById('text');
var runningData = []; // akan di‑isi oleh fetch()

// -------------------------------------------------
// Fungsi menampilkan satu baris data
function updateText() {
    if (runningData.length === 0) return; // belum ada data
    var row = runningData[i];
    text.innerHTML = row.nm_pasien +
        (row.nm_poli ? ' (' + row.nm_poli + ' - ' + row.nm_dokter + ')' : '') +
        ' (Belum Bayar)';
    i++;
    if (i >= runningData.length) i = 0;
}

// -------------------------------------------------
// Ambil data pertama kali
fetch('get_pasien.php')
    .then(r => r.json())
    .then(data => {
        runningData = data;
        updateText(); // tampilkan baris pertama segera
    })
    .catch(e => console.error('Gagal ambil data:', e));

// -------------------------------------------------
// Refresh data dari server tiap 1 detik
setInterval(function() {
    fetch('get_pasien.php')
        .then(r => r.json())
        .then(data => { runningData = data; })
        .catch(e => console.error('Gagal ambil data:', e));
}, 1000);

// -------------------------------------------------
// Ganti teks tiap 1 detik
setInterval(updateText, 1000);
</script>





<!-- FITUR -->

<div style="position:fixed; top:210px; left:9%; transform:translate(-50%, -50%);
background:#fff; padding:6px; width:200px; height:160px; border-radius:12px;
box-shadow:0 0 40px rgba(0,0,0,0.12); text-align:center; border:20px solid #00FF00;">



  <h2 id="jam" style="font-size:30px; margin:2px 0 0 0; line-height:40px;">WIB</h2>
<p>
  <p id="tanggal" style="font-size:24px; margin:2px 0; line-height:20px;">ROBOT</p>
<p>
  <p id="hari" style="font-size:20px; margin:0; line-height:20px;">Hamba Alloh</p>


<p id="hariNasional" style="font-size:14px; margin:4px 0 0 0; line-height:16px; color:#c00000; font-weight:bold;"></p>
<p id="hariKesehatan" style="font-size:14px; margin:0; line-height:16px; color:#008000; font-weight:bold;"></p>


</div>








<script>
// -------------------------------------------------
// Fullscreen
document.getElementById('fullScreenBtn').addEventListener('click', function() {
    if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen().catch(err => console.log('Fullscreen gagal:', err));
    } else {
        document.exitFullscreen();
    }
});

// Auto fullscreen saat load (opsional)
window.addEventListener('load', function() {
    // document.documentElement.requestFullscreen().catch(err => console.log('Fullscreen gagal:', err));
});

// -------------------------------------------------
// Jam & hari
ffunction updateJam() {
    var now = new Date();

    let jam = now.getHours().toString().padStart(2,'0');
    let menit = now.getMinutes().toString().padStart(2,'0');
    let detik = now.getSeconds().toString().padStart(2,'0');

    let waktu = jam + ":" + menit + ":" + detik;

    let elemenJam = document.getElementById('jam');
    elemenJam.innerHTML = waktu;

    document.getElementById('tanggal').innerHTML =
        now.getDate()+"/"+(now.getMonth()+1)+"/"+now.getFullYear();

    document.getElementById('hari').innerHTML =
        ['Hari Minggu','Hari Senin','Hari Selasa','Hari Rabu','Hari Kamis','Hari Jumat','Hari Sabtu'][now.getDay()];

    // ================= CEK JAM KHUSUS =================
    if ((jam === "12" || jam === "15") && menit === "00") {
        elemenJam.classList.add("kedip");
    } else {
        elemenJam.classList.remove("kedip");
    }
}
setInterval(updateJam,1000);
</script>


<script>

// ================= HARI SPESIAL OTOMATIS =================
async function cekHariSpesial() {

    const now = new Date();
    const tahun = now.getFullYear();
    const bulan = String(now.getMonth()+1).padStart(2,'0');
    const tanggal = String(now.getDate()).padStart(2,'0');

    const todayStr = `${tahun}-${bulan}-${tanggal}`;

    let nasional = [];
    let kesehatan = [];

    try {

        // API Kalender Indonesia
        const response = await fetch(`https://api-harilibur.vercel.app/api?year=${tahun}`);
        const data = await response.json();

        data.forEach(item => {
            if (item.holiday_date === todayStr) {
                nasional.push("🎉 " + item.holiday_name);
            }
        });

    } catch (error) {
        console.log("Gagal ambil data libur:", error);
    }

    // ===== HARI KESEHATAN (tetap manual tetap tahunan) =====
    const tgl = now.getDate();
    const bln = now.getMonth()+1;

    if (tgl==7 && bln==4) kesehatan.push("Hari Kesehatan Dunia");
    if (tgl==12 && bln==11) kesehatan.push("Hari Kesehatan Nasional");
    if (tgl==24 && bln==3) kesehatan.push("Hari Tuberkulosis Sedunia");
    if (tgl==31 && bln==5) kesehatan.push("Hari Tanpa Tembakau Sedunia");
    if (tgl==1 && bln==12) kesehatan.push("Hari AIDS Sedunia");

    document.getElementById("hariNasional").innerHTML = nasional.join("<br>");
    document.getElementById("hariKesehatan").innerHTML = kesehatan.join("<br>");
}

cekHariSpesial();

</script>









<!-- Widget Cuaca -->
<font color="">
  <div style="position:absolute; top:125px; left:480px; background:; padding:0; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.5); border: 2px solid ;">
    <iframe src="http://10.10.20.250/dashboard/APPS-ROBOT/TV/cuaca.php" class="snapwidget-widget" allowtransparency="true" frameborder="0" scrolling="no" style="border:none; width:188px; height:170px; border-radius:8px;" title="Klinik Asura"></iframe>
  </div>
</font>








<!-- Pasien Ranap -->
<font color="#9900FF">
  <div style="position:absolute; top:125px; left:230px; background:#fff; padding:0; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.5); border: 2px solid #00FF00;">
    <iframe src="http://10.10.20.250/dashboard/APPS-ROBOT/TV/ranap.php" class="snapwidget-widget" allowtransparency="true" frameborder="0" scrolling="no" style="border:none; width:240px; height:165px; border-radius:8px;" title="Klinik Asura"></iframe>
  </div>
</font>




<!-- Jadwal Sholat -->
<font color="#9900FF">
  <div style="position:absolute; top:650px; left:8px; background:#fff; padding:0; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.5); border: 2px solid #00FF00;">
    <iframe src="http://10.10.20.250/dashboard/APPS-ROBOT/TV/MASTER/sholat.php" class="snapwidget-widget" allowtransparency="true" frameborder="0" scrolling="no" style="border:none; width:660px; height:84px; border-radius:8px;" title="Klinik Asura"></iframe>
  </div>
</font>




<!-- PASIEN BELUM BAYAR -->
<font color="#9900FF">
  <div style="position:absolute; top:55px; left:2px; background:#fff; padding:0; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.5); border: 2px solid #00FF00;">
    <iframe src="http://10.10.20.250/dashboard/APPS-ROBOT/TV/pasien-belum-bayar.php" class="snapwidget-widget" allowtransparency="true" frameborder="0" scrolling="no" style="border:none; width:1278px; height:40px; border-radius:8px;" title="Klinik Asura"></iframe>
  </div>
</font>




<!-- PASIEN SUDAH BAYAR -->
<font color="#9900FF">
    <div style="position:absolute; top:408px; left:678px; background:#fff; padding:10px; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.5); border: 2px solid #007bff;">
  <iframe src="http://10.10.20.250/dashboard/APPS-ROBOT/TV/pasien-sudah-bayar.php" class="snapwidget-widget" allowtransparency="true" frameborder="0" scrolling="no" style="border:none; overflow:hidden; width:578px; height:17px" title="Klinik Asura"></iframe>
</div>
</font>







<!-- TEXT POLI -->
<font color="#9900FF">
  <div style="position:absolute; top:300px; left:2px; background:#fff; padding:0; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.5); border: 2px solid #00FF00;">
    <iframe src="http://10.10.20.250/dashboard/APPS-ROBOT/TV/text.php" class="snapwidget-widget" allowtransparency="true" frameborder="0" scrolling="no" style="border:none; width:670px; height:30px; border-radius:8px;" title="Klinik Asura"></iframe>
  </div>
</font>







<!-- TV Live + Tabel -->
<div style="display:flex; justify-content:space-between;">
  <div style="width:680px; height:780px;">
    <iframe width="100%" height="100%" src="https://www.youtube.com/embed/<?php echo $video_id;?>?autoplay=1&mute=1&loop=1&vq=hd480" frameborder="0" allowfullscreen></iframe> </div>







  <div style="width:612px; height:600px; top:80px;">
    <iframe src="http://10.10.20.250/dashboard/APPS-ROBOT/ANTRIAN-POLIKLINIK/poli-github-tv.php" class="snapwidget-widget" allowtransparency="true" frameborder="0" scrolling="no" style="border:none; overflow:hidden; width:100%; height:260px" title="Klinik Asura"></iframe>


    <iframe src="pasien.php" class="snapwidget-widget" allowtransparency="true" frameborder="0" scrolling="no" style="border:none; overflow:hidden; width:100%; height:700px" title="Klinik Asura"></iframe>
  </div>
</div>








<!-- KALENDER HARI NASIONAL DAN HARI KESEHATAN -->
<script>
// -------------------------------------------------
// Fullscreen
document.getElementById('fullScreenBtn').addEventListener('click', function() {
  if (!document.fullscreenElement) {
    document.documentElement.requestFullscreen().catch(err => console.log('Fullscreen gagal:', err));
  } else {
    document.exitFullscreen();
  }
});

// Auto fullscreen saat load (opsional)
window.addEventListener('load', function() {
  // document.documentElement.requestFullscreen().catch(err => console.log('Fullscreen gagal:', err));
});

// -------------------------------------------------
// Jam & hari
function updateJam() {
  var now = new Date();
  document.getElementById('jam').innerHTML = now.getHours()+":"+now.getMinutes()+":"+now.getSeconds();
  document.getElementById('tanggal').innerHTML = now.getDate()+"/"+(now.getMonth()+1)+"/"+now.getFullYear();
  document.getElementById('hari').innerHTML = ['Hari Minggu','Hari Senin','Hari Selasa','Hari Rabu','Hari Kamis','Hari Jumat','Hari Sabtu'][now.getDay()];

  // Ambil data hari libur nasional dari API
  fetch('https://api-harilibur.pages.dev/api')
    .then(response => response.json())
    .then(data => {
      const today = now.toISOString().split('T')[0];
      const hariLibur = data.find(holiday => holiday.holiday_date === today);
      if (hariLibur) {
        document.getElementById('hariNasional').innerHTML = hariLibur.holiday_name;
      } else {
        document.getElementById('hariNasional').innerHTML = "";
      }
    })
    .catch(error => console.error(error));

  // Ambil data hari kesehatan dari API
  fetch('https://api-hari-kesehatan.pages.dev/api')
    .then(response => response.json())
    .then(data => {
      const today = now.toISOString().split('T')[0];
      const hariKesehatan = data.find(hari => hari.date === today);
      if (hariKesehatan) {
        document.getElementById('hariKesehatan').innerHTML = hariKesehatan.name;
      } else {
        document.getElementById('hariKesehatan').innerHTML = "";
      }
    })
    .catch(error => console.error(error));
}
setInterval(updateJam,2000);
</script>












<script>
// -------------------------------------------------
// Running text pasien belum bayar
var data = <?= json_encode($data); ?>;
var i = 0;
var text = document.getElementById('text');
function updateText() {
    var row = data[i];
    text.innerHTML = row.nm_pasien +
        (row.nm_poli ? ' (' + row.nm_poli + ' - ' + row.nm_dokter + ')' : '') +
        ' ';
    i++;
    if (i >= data.length) i = 0;
}
updateText();
setInterval(updateText, 1000);
</script>





<!-- FULL LAYAR -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    document.documentElement.requestFullscreen();
});
document.getElementById('fullScreenBtn').addEventListener('click', function() {
    if (document.fullscreenElement) {
        document.exitFullscreen();
    } else {
        document.documentElement.requestFullscreen();
    }
});
</script>




</body>
</html>
