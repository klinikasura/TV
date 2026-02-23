


<div id="notifikasi-adzan"></div>
<script>
  function playAdzan() {
    var audio = new Audio('http://10.10.20.251/dashboard/APPS-ROBOT/TV/AUDIO/SHOLAT2.mp3');
    audio.play();
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
    notifikasi.innerHTML = "<h2>Saatnya Adzan Berkumandang !</h2><img src='http://10.10.20.251/dashboard/APPS-ROBOT/TV/JPG/ADZAN.jpeg' width='400' height='400'><p>Silahkan Menunaikan Ibadah Shalat " + sholat + "!</p>";
    document.body.appendChild(notifikasi);
    setTimeout(function() {
      document.body.removeChild(notifikasi);
    }, 250000);
  }

  // API waktu adzan
  var apiUrl = 'http://api.aladhan.com/v1/timingsByCity?city=Oki&country=Indonesia&method=2';

  // Cek waktu adzan
  setInterval(function() {
    fetch(apiUrl)
      .then(response => response.json())
      .then(data => {
        var waktuAdzan = [
          { jam: data.data.timings.Fajr.split(':')[0], menit: data.data.timings.Fajr.split(':')[1], sholat: 'Subuh' }, // Subuh
          { jam: data.data.timings.Dhuhr.split(':')[0], menit: data.data.timings.Dhuhr.split(':')[1], sholat: 'Dzuhur' }, // Dzuhur
          { jam: data.data.timings.Asr.split(':')[0], menit: data.data.timings.Asr.split(':')[1], sholat: 'Ashar' }, // Ashar
          { jam: data.data.timings.Maghrib.split(':')[0], menit: data.data.timings.Maghrib.split(':')[1], sholat: 'Maghrib' }, // Maghrib
          { jam: data.data.timings.Isha.split(':')[0], menit: data.data.timings.Isha.split(':')[1], sholat: 'Isya' } // Isya
        ];
        var now = new Date();
        var jam = now.getHours();
        var menit = now.getMinutes();
        for (var i = 0; i < waktuAdzan.length; i++) {
          if (jam === parseInt(waktuAdzan[i].jam) && menit === parseInt(waktuAdzan[i].menit)) {
            playAdzan();
            showNotifikasiAdzan(waktuAdzan[i].sholat);
          }
        }
      })
      .catch(error => console.error(error));
  }, 8000);
</script>





















---------------------------------------------------














<div id="notifikasi-adzan"></div>

<audio id="audioAdzan" preload="auto">
  <source src="http://10.10.20.250/dashboard/APPS-ROBOT/TV/AUDIO/ADZAN2.mp3" type="audio/mpeg">
</audio>

<script>
let jadwalSholat = [];
let sudahAdzanHariIni = {};

function playAdzan() {
    const audio = document.getElementById("audioAdzan");
    audio.currentTime = 0;
    audio.play().catch(() => {
        console.log("Audio diblokir browser. Klik layar sekali untuk mengaktifkan.");
    });
}

// unlock audio (WAJIB untuk Chrome / Android TV)
document.addEventListener("click", () => {
    const audio = document.getElementById("audioAdzan");
    audio.play().then(() => {
        audio.pause();
        audio.currentTime = 0;
        console.log("Audio unlocked");
    });
}, { once: true });

function showNotifikasiAdzan(sholat) {
    var notifikasi = document.createElement("div");
    notifikasi.style.position = "fixed";
    notifikasi.style.top = "50%";
    notifikasi.style.left = "50%";
    notifikasi.style.transform = "translate(-50%, -50%)";
    notifikasi.style.background = "#fff";
    notifikasi.style.padding = "20px";
    notifikasi.style.borderRadius = "10px";
    notifikasi.style.boxShadow = "0px 0px 20px rgba(0,0,0,0.6)";
    notifikasi.style.zIndex = "9999";

    notifikasi.innerHTML = `
      <center>
        <h2>Saatnya Adzan Berkumandang</h2>
        <img src="http://10.10.20.250/dashboard/APPS-ROBOT/TV/JPG/ADZAN.jpeg" width="400">
        <h3>Silahkan menunaikan shalat ${sholat}</h3>
      </center>
    `;

    document.body.appendChild(notifikasi);

    setTimeout(() => {
        if (document.body.contains(notifikasi)) {
            document.body.removeChild(notifikasi);
        }
    }, 240000);
}

// Ambil jadwal 1x sehari
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

        console.log("Jadwal sholat hari ini:", jadwalSholat);
    });
}

// cek tiap 1 detik (ringan tapi akurat)
setInterval(() => {
    const now = new Date();
    const jam = String(now.getHours()).padStart(2,'0');
    const menit = String(now.getMinutes()).padStart(2,'0');
    const waktuSekarang = `${jam}:${menit}`;

    jadwalSholat.forEach(j => {
        if (waktuSekarang === j.waktu && !sudahAdzanHariIni[j.sholat]) {
            sudahAdzanHariIni[j.sholat] = true;
            playAdzan();
            showNotifikasiAdzan(j.sholat);
        }
    });
}, 1000);

// reset tiap tengah malam
setInterval(() => {
    const now = new Date();
    if (now.getHours() === 0 && now.getMinutes() === 0) {
        sudahAdzanHariIni = {};
        ambilJadwal();
    }
}, 60000);

ambilJadwal();
</script>











