<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>myROBOT-V80</title>
  <link href="http://10.10.20.250/dashboard/APPS-ROBOT/BUILDING APLIKASI/@API-GITHUB-V80/ROBOT-GITHUB/ROBOTV80.png" rel="icon" type="image/png" />

 <meta http-equiv="refresh" content="60;url=cuaca-widget.php">

<style>
body {
  margin: 0;
  font-family: sans-serif;
  background: #1e3c72;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  color: white;
  overflow: hidden;
}

/* CARD */
.weather {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 16px;
  border-radius: 15px;
  background: rgba(255,255,255,0.15);
  backdrop-filter: blur(10px);
  box-shadow: 0 5px 20px rgba(0,0,0,0.4);
  width: 350px;

  animation: float 4s ease-in-out infinite;
}

/* FLOAT */
@keyframes float {
  0%,100% { transform: translateY(0); }
  50% { transform: translateY(-10px); }
}

/* ICON */
.icon {
  font-size: 80px;
  animation: iconMove 3s ease-in-out infinite;
}

@keyframes iconMove {
  0%,100% { transform: rotate(0deg) scale(1); }
  50% { transform: rotate(10deg) scale(1.1); }
}

/* TEMP */
.temp {
  font-size: 30px;
  font-weight: bold;
  animation: fadeIn 1s ease;
}

.info {
  font-size: 14px;
}

/* FADE */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px);}
  to { opacity: 1; transform: translateY(0);}
}

/* HUJAN */
.rain {
  position: fixed;
  width: 100%;
  height: 100%;
  pointer-events: none;
}

.drop {
  position: absolute;
  width: 2px;
  height: 12px;
  background: rgba(255,255,255,0.5);
  animation: fall linear infinite;
}

@keyframes fall {
  to { transform: translateY(100vh); }
}

#desc {
  font-size: 18px;
  font-weight: 500;
}

/* SIANG */
.day {
  background: linear-gradient(#1e88e5);
}

/* MALAM */
.night {
  background: linear-gradient(black);
}

/* PETIR */
.flash {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: white;
  opacity: 0;
  pointer-events: none;
}

/* animasi petir */
@keyframes lightning {
  0% { opacity: 0; }
  10% { opacity: 0.8; }
  20% { opacity: 0; }
}
</style>
</head>

<body>

<div class="flash" id="flash"></div>

<div class="rain" id="rain"></div>

<div class="weather">
  <div class="icon" id="icon">⛅</div>

  <div class="info">
    <div class="temp" id="temp">--°C</div>
    <div id="desc">Memuat...</div><p>

    <div style="opacity:0.8;">
      💧<span id="hum">--</span>% 
      🌪️ <span id="press">--</span> 
      🍃 <span id="wind">--</span>
    </div>
  </div>
</div>

<script>
fetch("https://wttr.in/Tugumulyo?format=j1")
.then(res => res.json())
.then(data => {
  const cur = data.current_condition[0];

  document.getElementById("temp").innerText = cur.temp_C + "°C";

  let kondisi = cur.weatherDesc[0].value.toLowerCase();
  let desc = "Cerah";
  let icon = "☀️";

  if(kondisi.includes("rain")) {
    desc = "Hujan";
    icon = "🌧️";
    createRain();
  }
  else if(kondisi.includes("cloud")) {
    desc = "Berawan";
    icon = "☁️";
  }
  else if(kondisi.includes("storm")) {
    desc = "Badai Petir";
    icon = "⛈️";
    createRain();
  }
  else if(kondisi.includes("mist") || kondisi.includes("fog")) {
    desc = "Berkabut";
    icon = "🌫️";
  }

  document.getElementById("desc").innerText = desc;

  document.getElementById("hum").innerText = cur.humidity;
  document.getElementById("press").innerText = cur.pressure + " hPa";
  document.getElementById("wind").innerText = cur.windspeedKmph + " km/j";

  document.getElementById("icon").innerText = icon;
});

// LOAD PERTAMA
loadCuaca();

// AUTO UPDATE (tiap 5 menit)
setInterval(loadCuaca, 300000);

// HUJAN ANIMASI
function createRain(){
  const rain = document.getElementById("rain");
  for(let i=0;i<80;i++){
    let drop = document.createElement("div");
    drop.classList.add("drop");
    drop.style.left = Math.random()*100 + "vw";
    drop.style.animationDuration = (0.5 + Math.random()) + "s";
    rain.appendChild(drop);
  }
}
</script>


<script>
function loadCuaca(){
  fetch("https://wttr.in/Tugumulyo?format=j1")
  .then(res => res.json())
  .then(data => {
    const cur = data.current_condition[0];

    document.getElementById("temp").innerText = cur.temp_C + "°C";

    let kondisi = cur.weatherDesc[0].value.toLowerCase();
    let desc = "Cerah";
    let icon = "☀️";

    // RESET HUJAN
    document.getElementById("rain").innerHTML = "";

    if(kondisi.includes("rain")) {
      desc = "Hujan";
      icon = "🌧️";
      createRain();
    }
    else if(kondisi.includes("cloud")) {
      desc = "Berawan";
      icon = "☁️";
    }
    else if(kondisi.includes("storm")) {
      desc = "Badai Petir";
      icon = "⛈️";
      createRain();
      lightning(); // AKTIFKAN PETIR
    }
    else if(kondisi.includes("mist") || kondisi.includes("fog")) {
      desc = "Berkabut";
      icon = "🌫️";
    }

    document.getElementById("desc").innerText = desc;

    document.getElementById("hum").innerText = cur.humidity;
    document.getElementById("press").innerText = cur.pressure + " hPa";
    document.getElementById("wind").innerText = cur.windspeedKmph + " km/j";

    document.getElementById("icon").innerText = icon;

    // 🌅 SIANG / 🌙 MALAM OTOMATIS
    const jam = new Date().getHours();
    if(jam >= 6 && jam < 18){
      document.body.classList.add("day");
      document.body.classList.remove("night");
    } else {
      document.body.classList.add("night");
      document.body.classList.remove("day");
    }
  });
}

// PETIR REAL ⚡
function lightning(){
  const flash = document.getElementById("flash");

  setInterval(() => {
    flash.style.animation = "lightning 0.4s";
    setTimeout(() => {
      flash.style.animation = "";
    }, 400);
  }, 5000); // tiap 5 detik
}

// LOAD AWAL
loadCuaca();

// AUTO UPDATE TANPA REFRESH
setInterval(loadCuaca, 600000); // 10 menit
</script>

</body>
</html>
