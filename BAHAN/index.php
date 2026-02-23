<?php
// --------------------------------------------------
// Konfigurasi dasar
require_once('conf/conf.php');
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
date_default_timezone_set("Asia/Bangkok");
$tanggal = mktime(date("m"),date("d"),date("Y"));
$jam     = date("H:i");
$video_id = 'DOOrIxw5xOw';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Aplikasi RS. Asura</title>
    <link href="http://10.10.20.250/dashboard/download.jpeg" rel="icon" type="image/png" />
    <meta http-equiv="refresh" content="720;url=index.php">

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
            position:fixed; top:10px; left:10px; z-index:9999;
            background:#4CAF50; color:#FFF; padding:10px 30px;
            border:none; border-radius:5px; cursor:pointer;
        }
        #fullScreenBtn:hover { background:#3e8e41; }

        a {
            text-decoration:none; color:#FFF; background:#4CAF50;
            padding:10px 20px; border-radius:5px; margin:5px;
        }
        a:hover { background:#3e8e41; }

        /* Popup notifikasi pembayaran */
        #popupBayar {
            position:fixed; top:20px; right:20px; background:#fff; color:#000;
            padding:15px 20px; border-radius:8px; box-shadow:0 0 15px rgba(0,0,0,0.3);
            font-size:18px; font-weight:bold; display:none; z-index:9999;
        }
    </style>
</head>

<body>

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
        SELECT rp.tgl_registrasi,
               p.nm_pasien,
               d.nm_dokter,
               pl.nm_poli,
               rp.status_bayar,
               rp.status_lanjut
        FROM reg_periksa rp
        JOIN pasien p  ON rp.no_rkm_medis = p.no_rkm_medis
        JOIN dokter d  ON rp.kd_dokter   = d.kd_dokter
        JOIN poliklinik pl ON rp.kd_poli = pl.kd_poli
        WHERE DATE(rp.tgl_registrasi) = '$tgl_hari_ini'
          AND rp.status_bayar = 'Belum Bayar'
          AND rp.status_lanjut = 'Ralan'
    ";

    $result = $conn->query($sql);
    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    } else {
        $data[] = [
            'nm_pasien' => 'Hore.... Pasien Hari Ini Sudah Periksa Semua',
            'nm_poli'   => '',
            'nm_dokter' => ''
        ];
    }
    $conn->close();
    ?>

   <style>
    .running-text {
        width: 100%;
        height: 40px;
        line-height: 40px;
        background: green;
        color: white;
        font-size: 18px;
        overflow: hidden;
        white-space: nowrap;
        padding: 0;
        box-sizing: border-box;
        font-weight: bold; 
        text-align: center;          /* ← tambahkan ini */
    }
    .running-text span {
        display: inline-block;
    }
</style>






<style>
    .typing {
        display: inline-block;
        white-space: nowrap;
        overflow: hidden;
        border-right: 3px solid green;
        animation: typing 8s steps(40, end) infinite,
                   blink 0.5s step-end infinite;
    }

    @keyframes typing {
        0% { width: 0; }
        50% { width: 16%; }
        100% { width: 0; }   /* kembali ke 0 untuk loop */
    }

    @keyframes blink {
        from, to { border-color: transparent; }
        50%      { border-color: green; }
    }
</style>





<style>
    .bell-link {
        text-decoration: none;
        color: white;               /* teks putih */
    }
    .bell-icon {
        display: inline-block;
        animation: shake 1s ease-in-out infinite;
        color: #007bff;             /* lonceng biru */
        margin-right: 4px;
    }
    @keyframes shake {
        0%, 100% { transform: rotate(0deg); }
        25%      { transform: rotate(50deg); }
        75%      { transform: rotate(-50deg); }
    }
</style>





    <button id="fullScreenBtn">FULL</button>
    <div id="popupBayar">Pembayaran Baru Terdeteksi!</div>

 <!-- Header link -->
    <p>
        <a href="#">V80 AI IT</a>
        <a href="http://10.10.20.250/dashboard/APPS-ROBOT/TV/MASTER/tv.php" target="_">VIDEO</a>
        <a href="http://10.10.20.250/dashboard/APPS-ROBOT/TV/MASTER/jadwal-dokter.php" target="_blank">JADWAL</a>
        <a href="http://10.10.20.250/dashboard/APPS-ROBOT/TV/MASTER/display-otomatis.php" target="_blank">PASIEN</a>
        <a href="http://10.10.20.250/dashboard/APPS-ROBOT/TV/MASTER/display.php" target="_blank">ANTRIAN</a>
        <a href="http://10.10.20.250/dashboard/APPS-ROBOT/TV/MASTER/grafik.php" target="_blank">GRAFIK</a>
        <a href="http://10.10.20.250/dashboard/APPS-ROBOT/TV/MASTER/tiket.php" target="_blank">E-REG</a>
   
<a href="http://10.10.20.250/dashboard/APPS-ROBOT/TV/MASTER/farmasi.php"
   target="_blank"
   class="bell-link">
    <span class="bell-icon">🔔</span>FARMASI
</a>

<a href="http://10.10.20.250/dashboard/APPS-ROBOT/TV/MASTER/lab.php"
   target="_blank"
   class="bell-link">
    <span class="bell-icon">🔔</span>LAB
</a>






<font color="green" class="typing">&copy; AI ROBOT SYSTEM V80</font>




    </p>
<p>

  <div class="running-text">
    <span id="text"></span>
</div>





<script>
    // -------------------------------------------------
    // Variabel global
    var i = 0;
    var text = document.getElementById('text');
    var runningData = [];   // akan di‑isi oleh fetch()

    // -------------------------------------------------
    // Fungsi menampilkan satu baris data
    function updateText() {
        if (runningData.length === 0) return;   // belum ada data
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
            updateText();               // tampilkan baris pertama segera
        })
        .catch(e => console.error('Gagal ambil data:', e));

    // -------------------------------------------------
    // Refresh data dari server tiap 2 detik
    setInterval(function() {
        fetch('get_pasien.php')
            .then(r => r.json())
            .then(data => { runningData = data; })
            .catch(e => console.error('Gagal ambil data:', e));
    }, 2000);

    // -------------------------------------------------
    // Ganti teks tiap 2 detik
    setInterval(updateText, 2000);
</script>
   
<!-- Jam & tanggal -->
<div style="
    position:fixed;
    top:188px;
    left:9%;
    transform:translate(-50%, -50%);
    background:#fff;
    padding:10px;
    width:190px;
    height:152px;
    border-radius:10px;
    box-shadow:0 0 10px rgba(0,0,0,0.5);
    text-align:center;">

    <h2 id="jam" style="font-size:30px;">WIB</h2>
    <p id="tanggal" style="font-size:20px;">ROBOT</p>
    <p id="hari" style="font-size:20px;">Hamba Alloh</p>
</div>


    <!-- Notifikasi Adzan -->
    <div id="notifikasi-adzan"></div>

    <!-- Widget Cuaca -->
    <div style="position:absolute; top:100px; left:480px; background:#fff; padding:10px; width:170px; height:155px; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.5);">
        <div id="id4714861f0707" a='{"t":"v","v":"1.2","lang":"id","locs":[449],"ssot":"c","sics":"ds","cbkg":"#00000000","cfnt":"#000000","cprb":"#FFFFFF00","cprf":"#000000","emr":"bool","edy":"bool","eev":"bool","ent":"bool","slfs":14,"slbr":10,"eln":"bool","slmw":151}'>
            Sumber Data Cuaca: <a href="https://cuacalab.id/cuaca_palembang/3_hari/">cuaca Palembang 3 hari kedepan</a>
        </div>
        <script async src="https://static1.cuacalab.id/widgetjs/?id=id4714861f0707"></script>
    </div>

    <!-- Pasien Ranap -->
    <font color="#9900FF">
        <div style="position:absolute; top:100px; left:230px; background:#fff; padding:10px; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.5);">
            <iframe src="http://10.10.20.250/dashboard/APPS-ROBOT/TV/ranap.php" class="snapwidget-widget" allowtransparency="true" frameborder="0" scrolling="no" style="border:none; overflow:hidden; width:220px; height:150px" title="Klinik Asura"></iframe>
        </div>
    </font>

    <!-- Jadwal Sholat -->
    <font color="#9900FF">
        <div style="position:absolute; top:634px; left:8px; background:#fff; padding:10px; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.5);">
            <iframe src="http://10.10.20.250/dashboard/APPS-ROBOT/TV/MASTER/sholat.php" class="snapwidget-widget" allowtransparency="true" frameborder="0" scrolling="no" style="border:none; overflow:hidden; width:642px; height:70px" title="Klinik Asura"></iframe>
        </div>
    </font>

    <!-- Icon Robot -->
    <font color="#9900FF">
        <div style="position:absolute; top:190px; left:18px;">
            <img src="ROBOT.gif" alt="AI ROBOT SYSTEM V80" width="50">
        </div>
    </font>

    <!-- TV Live + Tabel -->
    <div style="display:flex; justify-content:space-between;">
        <div style="width:680px; height:780px;">
            <iframe width="100%" height="100%" src="https://www.youtube.com/embed/<?php echo $video_id;?>?autoplay=1&mute=1&loop=1" frameborder="0" allowfullscreen></iframe>
        </div>
        <div style="width:612px; height:600px;">
            <iframe src="http://10.10.20.250/dashboard/APPS-ROBOT/ANTRIAN-POLIKLINIK/poli-github-tv.php" class="snapwidget-widget" allowtransparency="true" frameborder="0" scrolling="no" style="border:none; overflow:hidden; width:100%; height:260px" title="Klinik Asura"></iframe>
            <iframe src="pasien.php" class="snapwidget-widget" allowtransparency="true" frameborder="0" scrolling="no" style="border:none; overflow:hidden; width:100%; height:700px" title="Klinik Asura"></iframe>
        </div>
    </div>

    <!-- Audio notifikasi pembayaran -->
    <audio id="soundBayar" src="http://10.10.20.250/dashboard/APPS-ROBOT/TV/AUDIO/BAYAR2.mp3" preload="auto" muted></audio>

    <!-- Skrip umum -->
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
        }
        setInterval(updateJam,1000);

        // -------------------------------------------------
        // Notifikasi Adzan
        var adzanPlaying = false;   // flag untuk menandakan adzan sedang berbunyi

        function playAdzan() {
            var adzan = new Audio('http://10.10.20.250/dashboard/APPS-ROBOT/TV/AUDIO/SHOLAT2.mp3');
            adzan.play();
            adzanPlaying = true;
            sound.muted = true;                     // matikan suara bayar
            // Setelah adzan selesai (≈30 detik, sesuaikan)
            setTimeout(function() {
                adzanPlaying = false;
                sound.muted = false;                // aktifkan kembali
            }, 30000);
        }

        function showNotifikasiAdzan(sholat) {
            var notifikasi = document.createElement("div");
            notifikasi.style.position = "fixed";
            notifikasi.style.top = "50%";
            notifikasi.style.left = "50%";
            notifikasi.style.transform = "translate(-50%, -50%)";
            notifikasi.style.background = "#fff";
            notifikasi.style.padding = "20px";
            notifikasi.style.borderRadius = "10px";
            notifikasi.style.boxShadow = "0px 0px 10px rgba(0,0,0,0.5)";
            notifikasi.innerHTML = "<h2>Saatnya Adzan Berkumandang !</h2><img src='http://10.10.20.250/dashboard/APPS-ROBOT/TV/JPG/ADZAN.jpeg' width='400' height='400'><p>Silahkan Menunaikan Ibadah Shalat " + sholat + "!</p>";
            document.body.appendChild(notifikasi);
            setTimeout(function() { document.body.removeChild(notifikasi); }, 250000);
        }

        var apiUrl = 'http://api.aladhan.com/v1/timingsByCity?city=Oki&country=Indonesia&method=2';
        setInterval(function() {
            fetch(apiUrl)
            .then(r=>r.json())
            .then(d=>{
                var w = [
                    {j:d.data.timings.Fajr.split(':')[0], m:d.data.timings.Fajr.split(':')[1], s:'Subuh'},
                    {j:d.data.timings.Dhuhr.split(':')[0], m:d.data.timings.Dhuhr.split(':')[1], s:'Dzuhur'},
                    {j:d.data.timings.Asr.split(':')[0], m:d.data.timings.Asr.split(':')[1], s:'Ashar'},
                    {j:d.data.timings.Maghrib.split(':')[0], m:d.data.timings.Maghrib.split(':')[1], s:'Maghrib'},
                    {j:d.data.timings.Isha.split(':')[0], m:d.data.timings.Isha.split(':')[1], s:'Isya'}
                ];
                var now = new Date();
                for (var i=0;i<w.length;i++) {
                    if (now.getHours()==parseInt(w[i].j) && now.getMinutes()==parseInt(w[i].m)) {
                        playAdzan();
                        showNotifikasiAdzan(w[i].s);
                    }
                }
            })
            .catch(e=>console.error(e));
        }, 1000);

        // -------------------------------------------------
        // Popup & suara pembayaran
        var popupShown = false;
        var sound = document.getElementById('soundBayar');

        // Aktifkan suara setelah user klik halaman
        document.body.addEventListener('click', function initSound() {
            sound.muted = false;
            sound.play().catch(e => console.log('Play failed:', e));
            document.body.removeEventListener('click', initSound);
        });

        function cekPembayaran() {
            if (adzanPlaying) return;   // tunggu adzan selesai
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'http://10.10.20.250/dashboard/APPS-ROBOT/TV/cek_pembayaran.php', true);
            xhr.onload = function() {
                if (xhr.status === 200 && xhr.responseText.trim() === '1') {
                    if (sound.muted) sound.muted = false;
                    sound.play().catch(e => console.log('Play failed:', e));

                    if (!popupShown) {
                        var pop = document.getElementById('popupBayar');
                        pop.style.display = 'block';
                        setTimeout(function() { pop.style.display = 'none'; }, 4000);
                        popupShown = true;
                    }
                }
            };
            xhr.send();
        }
        setInterval(cekPembayaran, 5000);

        // -------------------------------------------------
        // NOTIFIKASI SUARA JAM TERTENTU (tidak mengganggu adzan)
        var alarmTimes = [
            '00:00','01:00','02:00','03:00','04:00','05:00','06:00',
            '07:00','08:00','09:00','10:00','11:00','12:00','13:00',
            '14:00','15:00','16:00','17:00','18:00','19:00','20:00',
            '21:00','22:00','23:00'
        ];

        function playGoogleTTS(timeStr) {
            if (adzanPlaying) return;   // jangan jalan kalau adzan aktif
            var url = 'https://translate.google.com/translate_tts?ie=UTF-8&tl=id&client=tw-ob&q=' +
                      encodeURIComponent('Sekarang sudah jam ' + timeStr);
            var audio = new Audio(url);
            audio.play().catch(e => console.log('Play failed:', e));
        }

        function cekJam() {
            var now = new Date();
            var curr = now.getHours() + ':' + ('0'+now.getMinutes()).slice(-2);
            if (alarmTimes.includes(curr)) {
                playGoogleTTS(curr);
                // Hapus jam yang sudah dipicu supaya tidak berulang tiap detik
                alarmTimes = alarmTimes.filter(t => t !== curr);
            }
        }
        setInterval(cekJam, 1000);
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
                             ' (Belum Bayar) ';
            i++;
            if (i >= data.length) i = 0;
        }

        updateText();
        setInterval(updateText, 2000);
    </script>




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
