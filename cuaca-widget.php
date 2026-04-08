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
  font-family: Arial, sans-serif;
  color: #111;
  height: 100vh;
  background: #f5f5f5;
  display: flex;
  justify-content: center;
  align-items: center;
}

/* CARD */
.weather {
  width: 400px;
  padding: 20px;
  border-radius: 20px;
  background: rgba(255,255,255,0.7);
  backdrop-filter: blur(10px);
  box-shadow: 0 5px 25px rgba(0,0,0,0.2);
  display: flex;
  align-items: center;
  gap: 15px;
}

/* ICON AREA */
.sky {
  position: relative;
  width: 120px;
  height: 100px;
}

/* CLOUD */
.cloud {
  position: absolute;
  width: 70px;
  height: 35px;
  background: #ccc;
  border-radius: 50px;
  opacity: 0.9;
  animation: floatCloud 4s ease-in-out infinite;
}

.cloud::before,
.cloud::after {
  content: "";
  position: absolute;
  background: #ccc;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  top: -15px;
}

.cloud::before { left: 5px; }
.cloud::after { right: 5px; }

/* FLOAT */
@keyframes floatCloud {
  0% { transform: translateY(0px); }
  50% { transform: translateY(-6px); }
  100% { transform: translateY(0px); }
}

/* RAIN */
.drop {
  position: absolute;
  width: 2px;
  height: 10px;
  background: rgba(0, 120, 255, 0.6);
  animation: fall linear infinite;
}

@keyframes fall {
  0% { transform: translateY(0px); opacity: 1; }
  100% { transform: translateY(100px); opacity: 0; }
}

/* TEXT */
.infoBox {
  flex: 1;
margin-right: 40px;
}
}

.temp {
  font-size: 22px;
  font-weight: bold;
}

#desc {
  font-size: 18px;
  margin-bottom: 6px;
}

.info {
  font-size: 13px;
  opacity: 0.7;
}

/* LIGHTNING */
.flash {
  position: fixed;
  width: 100%;
  height: 100%;
  background: white;
  opacity: 0;
}

@keyframes lightning {
  0% { opacity: 0; }
  10% { opacity: 0.6; }
  20% { opacity: 0; }
}

/* DAY */
.day {
  background: #f5f5f5;
}

/* NIGHT */
.night {
  background: #111;
  color: white;
}

.night .weather {
  background: rgba(0,0,0,0.5);
}

.night .cloud {
  background: #eee;
}

.night .cloud::before,
.night .cloud::after {
  background: #eee;
}
</style>
</head>

<body>

<div class="flash" id="flash"></div>

<div class="weather">

  <div class="sky" id="sky"></div>

  <div class="infoBox">
    <div class="temp" id="temp">--°C</div>
    <div id="desc">Memuat...</div>

    <div class="info">
      <span id="hum">--</span>% | 
      <span id="wind">--</span> km/j |
      <span id="press">--</span> hPa
    </div>
  </div>

</div>

<script>
function loadCuaca(){
  fetch("https://wttr.in/Tugumulyo?format=j1")
  .then(res => res.json())
  .then(data => {

    const cur = data.current_condition[0];

    document.getElementById("temp").innerText = cur.temp_C + "°C";

    let kondisi = cur.weatherDesc[0].value.toLowerCase();
    let desc = "Cerah";

    const sky = document.getElementById("sky");
    sky.innerHTML = "";

    if(kondisi.includes("rain")){
      desc = "Hujan";
      createCloud();
      createRain();
    }
    else if(kondisi.includes("cloud")){
      desc = "Berawan";
      createCloud();
    }
    else if(kondisi.includes("storm")){
      desc = "Badai Petir";
      createCloud();
      createRain();
      lightning();
    }
    else if(kondisi.includes("mist") || kondisi.includes("fog")){
      desc = "Berkabut";
      createCloud();
    }

    document.getElementById("desc").innerText = desc;

    document.getElementById("hum").innerText = cur.humidity;
    document.getElementById("wind").innerText = cur.windspeedKmph;
    document.getElementById("press").innerText = cur.pressure;

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

/* CLOUD */
function createCloud(){
  const sky = document.getElementById("sky");
  for(let i=0;i<2;i++){
    let cloud = document.createElement("div");
    cloud.classList.add("cloud");
    cloud.style.top = (Math.random()*50)+"px";
    cloud.style.left = (Math.random()*40)+"px";
    sky.appendChild(cloud);
  }
}

/* RAIN */
function createRain(){
  const sky = document.getElementById("sky");
  for(let i=0;i<40;i++){
    let drop = document.createElement("div");
    drop.classList.add("drop");
    drop.style.left = Math.random()*100 + "%";
    drop.style.animationDuration = (0.5 + Math.random()) + "s";
    sky.appendChild(drop);
  }
}

/* LIGHTNING */
function lightning(){
  const flash = document.getElementById("flash");

  setInterval(()=>{
    flash.style.animation = "lightning 0.4s";
    setTimeout(()=>{
      flash.style.animation = "";
    },400);
  },5000);
}

loadCuaca();
setInterval(loadCuaca, 60000);
</script>

</body>
</html>
