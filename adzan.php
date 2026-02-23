
<div id="notifikasi-adzan"></div>
<script>
  function playAdzan() {
    var audio = new Audio('');
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
    notifikasi.innerHTML = "<h2>Saatnya Adzan Berkumandang !</h2><img src='http://10.10.20.250/dashboard/APPS-ROBOT/TV/JPG/ADZAN.jpeg' width='400' height='400'><p>Silahkan Menunaikan Ibadah Shalat " + sholat + "!</p>";
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
  }, 1000);
</script>






