<div id="weather-app">
  <div class="card">
    <canvas id="effectCanvas"></canvas>

    <div class="flash" id="flash"></div>

    <!-- POPUP -->
    <div id="popup" class="popup"></div>

    <div class="main">
      <img id="icon"/>
      <div class="temp" id="temp">--°</div>
    </div>

    <div id="desc">Loading...</div>

    <div class="details">
       <span id="wind"></span> km/h |
       <span id="humidity"></span>%
    </div>
  </div>
</div>

<style>
body {
  margin:0;
  font-family:Arial;
  background:#0a0a0a;
}

#weather-app {
  display:flex;
  justify-content:center;
}

.card {
  width:188px;
  height:240px;
  padding:10px;
  border-radius:12px;
  background:#111;
  color:#00f7ff;
  box-shadow:0 0 20px rgba(0,255,255,0.2);
  position:relative;
  overflow:hidden;
}

#effectCanvas {
  position:absolute;
  width:100%;
  height:100%;
}

.flash {
  position:absolute;
  width:100%;
  height:100%;
  background:white;
  opacity:0;
}

.flash.active {
  animation: flash 0.3s;
}

@keyframes flash {
  50% {opacity:0.8;}
}

#icon {
  width:110px;
  animation: float 3s infinite ease-in-out;
}

@keyframes float {
  50% {transform:translateY(-3px);}
}

.main {
  display:flex;
  justify-content:center;
  align-items:center;
}

.temp { font-size:40px; }

#desc {
  font-size:20px;
  text-align:center;
}

.details {
  font-size:18px;
  text-align:center;
}

/* ===== POPUP ===== */
.popup {
  position:absolute;
  top:10px;
  left:50%;
  transform:translateX(-50%) translateY(-10px) scale(0.9);
  padding:6px 12px;
  border-radius:8px;
  font-size:10px;
  opacity:0;
  color:#fff;
  border:1px solid rgba(255,255,255,0.2);
  box-shadow:0 0 10px rgba(0,0,0,0.3);
  transition:all 0.3s ease;
  pointer-events:none;
}

.popup.show {
  opacity:1;
  transform:translateX(-50%) translateY(0) scale(1);
}

/* CUACA STYLE */
.popup.clear {
  background:linear-gradient(135deg,#00c6ff,#0072ff);
  box-shadow:0 0 12px #00c6ff;
}

.popup.rain {
  background:linear-gradient(135deg,#3a7bd5,#00d2ff);
  box-shadow:0 0 12px #00d2ff;
}

.popup.storm {
  background:linear-gradient(135deg,#232526,#ff4b1f);
  box-shadow:0 0 12px #ff4b1f;
}

.popup.fog {
  background:linear-gradient(135deg,#757f9a,#d7dde8);
  box-shadow:0 0 12px #aaa;
}
</style>

<script>
const lat = -3.238;
const lon = 104.807;

const canvas = document.getElementById("effectCanvas");
const ctx = canvas.getContext("2d");
const flash = document.getElementById("flash");

const tempEl = document.getElementById("temp");
const windEl = document.getElementById("wind");
const humidityEl = document.getElementById("humidity");
const iconEl = document.getElementById("icon");
const descEl = document.getElementById("desc");

let particles = [];
let mode = "clear";
let lastWeatherCode = null;
let popupTimeout = null;

/* RESIZE */
function resize(){
  canvas.width = canvas.offsetWidth;
  canvas.height = canvas.offsetHeight;
}
resize();
window.addEventListener("resize", resize);

/* PARTICLES */
function createRain(){
  particles=[];
  for(let i=0;i<80;i++){
    particles.push({
      x:Math.random()*canvas.width,
      y:Math.random()*canvas.height,
      l:10+Math.random()*10,
      xs:-2,
      ys:4+Math.random()*4
    });
  }
}

function createFog(){
  particles=[];
  for(let i=0;i<20;i++){
    particles.push({
      x:Math.random()*canvas.width,
      y:Math.random()*canvas.height,
      r:20+Math.random()*30,
      xs:Math.random()*0.5
    });
  }
}

/* DRAW */
function draw(){
  ctx.clearRect(0,0,canvas.width,canvas.height);

  if(mode==="rain"||mode==="storm"){
    ctx.strokeStyle="rgba(0,255,255,0.2)";
    for(let p of particles){
      ctx.beginPath();
      ctx.moveTo(p.x,p.y);
      ctx.lineTo(p.x+p.xs,p.y+p.l);
      ctx.stroke();
      p.x+=p.xs; p.y+=p.ys;
      if(p.y>canvas.height){p.y=0;p.x=Math.random()*canvas.width;}
    }
  }

  if(mode==="fog"){
    ctx.fillStyle="rgba(200,200,200,0.2)";
    for(let p of particles){
      ctx.beginPath();
      ctx.arc(p.x,p.y,p.r,0,Math.PI*2);
      ctx.filter="blur(12px)";
      ctx.fill();
      ctx.filter="none";
      p.x+=p.xs;
      if(p.x>canvas.width) p.x=0;
    }
  }

  if(mode==="clear"){
    let grd=ctx.createRadialGradient(
      canvas.width/2,canvas.height/2,10,
      canvas.width/2,canvas.height/2,80
    );
    grd.addColorStop(0,"rgba(0,255,255,0.2)");
    grd.addColorStop(1,"transparent");
    ctx.fillStyle=grd;
    ctx.fillRect(0,0,canvas.width,canvas.height);
  }

  requestAnimationFrame(draw);
}
draw();

/* FLASH */
setInterval(()=>{
  if(mode==="storm"){
    flash.classList.add("active");
    setTimeout(()=>flash.classList.remove("active"),200);
  }
},5000);

/* POPUP */
function showPopup(text, mode = "clear"){
  const p = document.getElementById("popup");

  if(popupTimeout) clearTimeout(popupTimeout);

  p.className = "popup";
  p.classList.add(mode);

  p.innerText = text;
  p.classList.add("show");

  popupTimeout = setTimeout(()=>{
    p.classList.remove("show");
  },3000);
}

/* ICON */
function icon(code){
  if(code>=95) return "JPG/95-petir-icon.gif";
  if(code>=61) return "JPG/61-hujan-icon.gif";
  if(code<=3) return "JPG/3-3.gif";
  return "JPG/3-3.gif";
}

/* DESC */
function desc(code){
  const map = {
    0:"Cerah",1:"Cerah Berawan",2:"Berawan",3:"Mendung",
    45:"Kabut",48:"Kabut Tebal",
    51:"Gerimis",53:"Gerimis",55:"Gerimis Lebat",
    61:"Hujan Ringan",63:"Hujan",65:"Hujan Lebat",
    80:"Hujan Lokal",81:"Hujan",82:"Hujan Deras",
    95:"Badai Petir"
  };
  return map[code] ?? "Angin";
}

/* WEATHER */
async function loadWeather(){
  const url=`https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current_weather=true&hourly=temperature_2m,weathercode,relativehumidity_2m&timezone=auto`;

  const res=await fetch(url);
  const data=await res.json();
  const w=data.current_weather;

  tempEl.innerText=Math.round(w.temperature)+"°";
  windEl.innerText=w.windspeed;
  iconEl.src=icon(w.weathercode);
  descEl.innerText=desc(w.weathercode);

  const nowIndex = data.hourly.time.indexOf(w.time);
  humidityEl.innerText = data.hourly.relativehumidity_2m[nowIndex] ?? "-";

  /* MODE */
  if(w.weathercode>=95){
    mode="storm"; createRain();
  }
  else if(w.weathercode>=61){
    mode="rain"; createRain();
  }
  else if(w.weathercode<=3){
    mode="clear";
  }
  else{
    mode="fog"; createFog();
  }

  /* DETECT CHANGE */
  if(lastWeatherCode !== null && lastWeatherCode !== w.weathercode){

    let old = desc(lastWeatherCode);
    let now = desc(w.weathercode);

    let popupMode = "clear";

    if(w.weathercode>=95) popupMode="storm";
    else if(w.weathercode>=61) popupMode="rain";
    else if(w.weathercode<=3) popupMode="clear";
    else popupMode="fog";

    showPopup(`CUACA: ${old} ➜ ${now}`, popupMode);
  }

  lastWeatherCode = w.weathercode;
}

loadWeather();
setInterval(loadWeather,40000);
</script>
