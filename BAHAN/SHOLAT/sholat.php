<div id="notifikasi-adzan" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); opacity: 0; transition: opacity 0.5s;">
  <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: #fff; padding: 20px; border-radius: 10px;">
    <h2 id="judul-notifikasi">Waktu Adzan!</h2>
    <img src='http://10.10.20.251/dashboard/APPS-ROBOT/TV/JPG/ADZAN.jpeg' width='400' height='400'>
    <p id="isi-notifikasi">Silahkan Menunaikan Ibadah Shalat!</p>
  </div>
</div>
<script>
  function playAdzan() {
    var audio = new Audio('http://10.10.20.251/dashboard/APPS-ROBOT/TV/AUDIO/SHOLAT2.mp3');
    audio.play();
  }

  function showNotifikasiAdzan(sholat) {
    document.getElementById('judul-notifikasi').innerHTML = 'Saatnya Adzan Berkumandang !' + sholat + '!';
    document.getElementById('isi-notifikasi').innerHTML = 'Silahkan Menunaikan Ibadah Shalat ' + sholat + '!';
    document.getElementById('notifikasi-adzan').style.opacity = 1;
    document.getElementById('notifikasi-adzan').style.display = 'block';
    setTimeout(function() {
      hideNotifikasiAdzan();
    }, 250000); // 240 detik
  }

  function hideNotifikasiAdzan() {
    document.getElementById('notifikasi-adzan').style.opacity = 0;
    setTimeout(function() {
      document.getElementById('notifikasi-adzan').style.display = 'none';
    }, 500); // 0.5 detik
  }

  // Waktu adzan
  var waktuAdzan = [
    { jam: 4, menit: 30, sholat: 'Subuh' }, // Subuh
    { jam: 12, menit: 0, sholat: 'Dzuhur' }, // Dzuhur
    { jam: 15, menit: 30, sholat: 'Ashar' }, // Ashar
    { jam: 18, menit: 0, sholat: 'Maghrib' }, // Maghrib
    { jam: 19, menit: 30, sholat: 'Isya' } // Isya
  ];

  // Cek waktu adzan
  setInterval(function() {
    var now = new Date();
    var jam = now.getHours();
    var menit = now.getMinutes();
    for (var i = 0; i < waktuAdzan.length; i++) {
      if (jam === waktuAdzan[i].jam && menit === waktuAdzan[i].menit) {
        playAdzan();
        showNotifikasiAdzan(waktuAdzan[i].sholat);
      }
    }
  }, 1000);
</script>
