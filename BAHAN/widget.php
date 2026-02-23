
<style>
    #weather-popup {
        position: fixed;
        top:480px;
        left: 25%;
        transform: translate(-50%, -50%);
        background-color: #fff;
        padding: 10px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        z-index: 9999;
        display: none;
    }
</style>



<div id="weather-popup"></div>

<script>
    function loadWeatherPopup() {
        const popup = document.getElementById('weather-popup');
        popup.innerHTML = '<font color="#9900FF"> <div style="background:#fff; padding:10px; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.5);"> <iframe src="cuaca.php" class="snapwidget-widget" allowtransparency="true" frameborder="0" scrolling="no" style="border:none; overflow:hidden; width:400px; height:280px" title="Klinik Asura"></iframe> </div> </font>';
        popup.style.display = 'block';
        setTimeout(() => {
            popup.style.display = 'none';
            popup.innerHTML = '';
        }, 20000);
    }

    setInterval(loadWeatherPopup,240000);
</script>





---------------------------

<!-- ★ Efek Getar Layar ★ -->
<style>
    body.shake {
        animation: screenShake 0.3s ease-in-out;
    }
    @keyframes screenShake {
        0%, 100% { transform: translate(0,0); }
        10%      { transform: translate(-5px, 5px); }
        20%      { transform: translate(5px, -5px); }
        30%      { transform: translate(-4px, -4px); }
        40%      { transform: translate(4px, 4px); }
        50%      { transform: translate(-3px, 3px); }
        60%      { transform: translate(3px, -3px); }
        70%      { transform: translate(-2px, -2px); }
        80%      { transform: translate(2px, 2px); }
        90%      { transform: translate(-1px, 1px); }
    }
</style>



<script>
    // Fungsi untuk menambahkan efek getar
    function triggerShake(duration = 300) {
        document.body.classList.add('shake');
        setTimeout(() => document.body.classList.remove('shake'), duration);
    }

    // Panggil triggerShake setiap kali roket dibuat
    function createRocket() {
        // … (kode roket yang sudah ada) …
        triggerShake(300);   // getar selama 300 ms
        // … (sisanya) …
    }

    // jalankan roket tiap 120 detik
    setInterval(createRocket, 120000);
</script>




----------------------



<!-- ★ Efek Roket (gambar) ★ -->
<style>
    .rocket-img {
        position: fixed;
        width: 200px;               /* sesuaikan ukuran gambar */
        z-index: 9997;
        pointer-events: none;
    }
    .rocket-img.animate {
        animation: rocketFly 12s linear forwards,
                   rocketWiggle 0.5s ease-in-out infinite alternate;
    }
    @keyframes rocketFly {
        0%   { transform: translate(0,0); }
        100% { transform: translate(var(--dx), var(--dy)); }
    }
    @keyframes rocketWiggle {
        0%   { transform: translate(var(--dx), var(--dy)) rotate(-2deg); }
        100% { transform: translate(var(--dx), var(--dy)) rotate(2deg); }
    }
</style>




<script>
    function createRocket() {
        // Hapus roket sebelumnya
        const oldRocket = document.querySelector('.rocket-img');
        if (oldRocket) oldRocket.remove();

        const rocket = document.createElement('img');
        rocket.src = 'JPG/ROBOT.gif';
        rocket.className = 'rocket-img';
        document.body.appendChild(rocket);

        // posisi start acak di sisi kiri atau bawah
        const startSide = Math.random() < 0.5 ? 'left' : 'bottom';
        if (startSide === 'left') {
            rocket.style.left = '-100px';
            rocket.style.bottom = Math.random() * 80 + 'vh';
        } else {
            rocket.style.bottom = '-100px';
            rocket.style.left = Math.random() * 80 + 'vw';
        }

        // trigger animasi
        setTimeout(() => rocket.classList.add('animate'), 100);

        // hapus elemen setelah animasi selesai
        rocket.addEventListener('animationend', () => rocket.remove());
    }

    // jalankan roket tiap 120 detik
    setInterval(createRocket,120000);
</script>


------------------


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
    if (adzanPlaying) return; // tunggu adzan selesai
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
    if (adzanPlaying) return; // jangan jalan kalau adzan aktif
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










