<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Aplikasi RS. Asura</title>
  <link href="http://10.10.20.250/dashboard/download.jpeg" rel="icon" type="image/png">
 <style>

/* =========================
   BACKGROUND UTAMA
========================= */
/* ====== DASAR HALAMAN ====== */
body {
  font-family: Arial, Helvetica, sans-serif;
  max-width: 100%;
  margin: 0;
  padding: 0;
  color: #000;
  background: #f2f5f9;   /* tidak hitam, lembut di mata */
}

/* ====== PANEL JADWAL ATAS ====== */
/* PANEL JADWAL 1 BARIS */
/* ===== PANEL JADWAL TIPIS 1 BARIS ===== */
#times{
  position: fixed;
  top: 2px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  flex-wrap: nowrap;        /* tidak boleh turun baris */
  gap: 3px;
  background: #ffffff;
  padding: 2px 4px;         /* super tipis */
  border-radius: 5px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.18);
  white-space: nowrap;
  z-index: 999;
}

/* kotak tiap waktu */
#times > span{
  padding: 2px 5px;         /* kecil */
  font-size: 17px;          /* kecil tapi terbaca */
  border-radius: 4px;
  border: 1px solid #d6dce6;
  background: #f7f9fc;
  min-width: 62px;          /* biar semua muat satu baris */
  text-align: center;
}

/* tulisan "Subuh" dll */
#times .label{
  display: inline;
  font-weight: 6000;
  font-size: 17px;
  margin-right: 20px;
  color: #333;
}

/* hampir adzan */
.akan{
  background: #d9ecff !important;
  border-color: #6fb3ff !important;
  color: #003c80;
}

/* sedang waktu sholat */
.aktif{
  background: #dff5e4 !important;
  border-color: #35a85b !important;
  color: #0b5d2a;
  font-weight: bold;
}




/* ====== NOTIFIKASI (dibawah jadwal) ====== */
#notif-container{
  position: fixed;
  top: 30px;               /* posisi tepat bawah jadwal */
  left: 50%;
  transform: translateX(-50%);
  width: 95%;
  max-width: 900px;
  display: flex;
  justify-content: center;
  pointer-events: none;
  z-index: 998;
  font-size: 40px;
}

.notif {
  padding: 6px 14px;
  background: #ffffff;
  border: 1px solid #bfc7d4;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.25);
  font-size: 18px;
  font-weight: 600;
  color: #222;
  animation: fadeIn 0.4s ease;
}

/* animasi muncul halus */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translate(-50%, -10px);
  }
  to {
    opacity: 1;
    transform: translate(-50%, 0px);
  }
}

/* ====== TANGGAL ====== */
#date {
  text-align: center;
  font-size: 12px;
  margin-top: 120px; /* turun supaya tidak ketabrak jadwal */
  color: #333;
}

/* ====== RESPONSIVE TV BESAR ====== */
@media (min-width: 1200px){
  #times > span{
    font-size: 13px;
    min-width: 115px;
  }
  .notif{
    font-size: 13px;
  }
}


</style>

</head>
<body>
  <div id="times">
    <span><span class="label">Subuh:</span><span id="subuh">--:--</span></span>
    <span><span class="label">Dzuhur:</span><span id="dzuhur">--:--</span></span>
    <span><span class="label">Ashar:</span><span id="ashar">--:--</span></span>
    <span><span class="label">Maghrib:</span><span id="maghrib">--:--</span></span>
    <span><span class="label">Isya:</span><span id="isya">--:--</span></span>
  </div>
  <div id="notif-container"></div>
  <div id="date"></div>
  <script>
    let notifShown = false;
    function notify(msg) {
      if (notifShown) return;
      notifShown = true;
      const container = document.getElementById('notif-container');
      const el = document.createElement('div');
      el.className = 'notif';
      el.textContent = msg;
      container.appendChild(el);
      setTimeout(() => {
        el.remove();
        notifShown = false;
      }, 2000); // 2 detik
    }

    const blinkIntervals = {};
    function updateTimes() {
      // Tambahin timezone supaya API ngasih waktu WIB
      const api = 'http://api.aladhan.com/v1/timingsByCity?city=Oki&country=Indonesia&method=2&timezone=Asia/Jakarta';
      fetch(api)
        .then(r => r.json())
        .then(data => {
          if (data.code !== 200) {
            console.error('API error:', data);
            return;
          }
          const t = data.data.timings;
          const map = {
            subuh: 'Fajr',
            dzuhur: 'Dhuhr',
            ashar: 'Asr',
            maghrib: 'Maghrib',
            isya: 'Isha'
          };
          Object.entries(map).forEach(([id, key]) => {
            document.getElementById(id).textContent = t[key];
          });
          document.getElementById('date').textContent = data.data.date.readable;
          const toMin = s => {
            const [h,m] = s.split(':').map(Number);
            return h*60 + m;
          };
          const now = new Date();
          const nowMin = now.getHours()*60 + now.getMinutes();
          document.querySelectorAll('#times > span').forEach(el => el.classList.remove('akan','aktif'));
          Object.entries(map).forEach(([id, key]) => {
            const tm = toMin(t[key]);
            const el = document.getElementById(id).parentElement;
            if (nowMin >= tm && nowMin < tm + 5) {
              el.classList.add('aktif');
              notify(`Waktu ${key} telah tiba`);
              if (blinkIntervals[id]) {
                clearInterval(blinkIntervals[id]);
                delete blinkIntervals[id];
              }
            } else if (tm > nowMin && tm - nowMin <= 60) {
              el.classList.add('akan');
              notify(`Waktu ${key} akan tiba dalam ${tm - nowMin} menit`);
              if (!blinkIntervals[id]) {
                blinkIntervals[id] = setInterval(function() {
                  if (el.style.opacity == 1) {
                    el.style.opacity = 0.2;
                  } else {
                    el.style.opacity = 1;
                  }
                }, 2000);
              }
            } else {
              el.style.opacity = 1; // Kembalikan opacity ke 1 jika tidak akan tiba
              el.classList.remove('akan', 'aktif');
              if (blinkIntervals[id]) {
                clearInterval(blinkIntervals[id]);
                delete blinkIntervals[id];
              }
            }
          });
        })
        .catch(err => {
          console.error('Fetch error:', err);
          const now = new Date();
          document.getElementById('date').textContent = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        });
    }
    // refresh tiap detik
    updateTimes();
    setInterval(updateTimes, 1000);
  </script>
</body>
</html>
