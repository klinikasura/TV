<div id="weather-app">
  <div class="card">

    <canvas id="effectCanvas"></canvas>
    <div class="flash" id="flash"></div>

    <div class="main">
      <img id="icon"/>
      <div class="temp" id="temp">--°</div>
    </div>

    <div id="desc">Loading...</div>

    <div class="details">
      💨 <span id="wind"></span> km/h |
      💧 <span id="humidity"></span>%
    </div>

    <div class="hourly" id="hourly"></div>

  </div>
</div>

<style>
body {
  margin:0;
  font-family:Arial;
  background:#f5f5f5;
}

#weather-app {
  display:flex;
  justify-content:center;
}

/* CARD */
.card {
  width:188px;
  height:180px;
  padding:10px;
  border-radius:12px;
  background:#fff;
  color:#111;
  box-shadow:0 5px 20px rgba(0,0,0,0.1);
  position:relative;
  overflow:hidden;
}

/* CANVAS EFFECT */
#effectCanvas {
  position:absolute;
  top:0;
  left:0;
  width:100%;
  height:100%;
  pointer-events:none;
}

/* PETIR FLASH */
.flash {
  position:absolute;
  width:100%;
  height:100%;
  background:white;
  opacity:0;
  top:0;
  left:0;
}

.flash.active {
  animation: flash 0.3s;
}

@keyframes flash {
  0% {opacity:0;}
  50% {opacity:0.8;}
  100% {opacity:0;}
}

/* ICON */
#icon {
  width:80px;
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

.temp {
  font-size:32px;
  margin-left:5px;
}

#desc {
  font-size:18px;
  text-align:center;
}

.details {
  font-size:14px;
  text-align:center;
}

.hourly {
  display:flex;
  justify-content:space-between;
  font-size:12px;
  margin-top:5px;
}
</style>

<script>
const lat = -3.238;
const lon = 104.807;

const canvas = document.getElementById("effectCanvas");
const ctx = canvas.getContext("2d");

let particles = [];
let mode = "clear";

/* RESIZE */
function resize(){
  canvas.width = canvas.offsetWidth;
  canvas.height = canvas.offsetHeight;
}
resize();
window.addEventListener("resize", resize);

/* PARTICLE SYSTEM */
function createRain(){
  particles = [];
  for(let i=0;i<80;i++){
    particles.push({
      x: Math.random()*canvas.width,
      y: Math.random()*canvas.height,
      l: Math.random()*10+10,
      xs: -2,
      ys: Math.random()*4+4
    });
  }
}

function createFog(){
  particles = [];
  for(let i=0;i<20;i++){
    particles.push({
      x: Math.random()*canvas.width,
      y: Math.random()*canvas.height,
      r: Math.random()*30+20,
      xs: Math.random()*0.5
    });
  }
}

function draw(){
  ctx.clearRect(0,0,canvas.width,canvas.height);

  if(mode==="rain"){
    ctx.strokeStyle="rgba(0,0,0,0.2)";
    ctx.lineWidth=1;

    for(let p of particles){
      ctx.beginPath();
      ctx.moveTo(p.x,p.y);
      ctx.lineTo(p.x+p.xs,p.y+p.l);
      ctx.stroke();

      p.x+=p.xs;
      p.y+=p.ys;

      if(p.y>canvas.height){
        p.y=0;
        p.x=Math.random()*canvas.width;
      }
    }
  }

  if(mode==="fog"){
    ctx.fillStyle="rgba(200,200,200,0.1)";
    for(let p of particles){
      ctx.beginPath();
      ctx.arc(p.x,p.y,p.r,0,Math.PI*2);
      ctx.fill();

      p.x+=p.xs;
      if(p.x>canvas.width) p.x=0;
    }
  }

  if(mode==="clear"){
    let grd = ctx.createRadialGradient(
      canvas.width/2,canvas.height/2,10,
      canvas.width/2,canvas.height/2,80
    );
    grd.addColorStop(0,"rgba(255,200,0,0.3)");
    grd.addColorStop(1,"rgba(255,255,255,0)");

    ctx.fillStyle = grd;
    ctx.fillRect(0,0,canvas.width,canvas.height);
  }

  requestAnimationFrame(draw);
}
draw();

/* PETIR */
function lightning(){
  setInterval(()=>{
    if(mode==="storm"){
      const flash = document.getElementById("flash");
      flash.classList.add("active");

      // suara petir
      const audio = new Audio("https://www.soundjay.com/nature/thunder-01.mp3");
      audio.volume = 0.3;
      audio.play();

      setTimeout(()=>flash.classList.remove("active"),200);
    }
  },5000);
}
lightning();

/* ICON */
function icon(code){
  if(code==0) return "https://cdn-icons-png.flaticon.com/512/869/869869.png";
  if(code<=3) return "https://cdn-icons-png.flaticon.com/512/414/414825.png";
  return "https://cdn-icons-png.flaticon.com/512/1163/1163624.png";
}

function desc(code){
  return {
    0:"Cerah",
    2:"Berawan",
    3:"Mendung",
    61:"Hujan",
    95:"Badai"
  }[code] || "Cuaca";
}

/* WEATHER */
async function loadWeather(){
  const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&hourly=temperature_2m,weathercode,relativehumidity_2m&current_weather=true&timezone=auto`;

  const res = await fetch(url);
  const data = await res.json();

  const w = data.current_weather;

  document.getElementById("temp").innerText = Math.round(w.temperature)+"°";
  document.getElementById("wind").innerText = w.windspeed;
  document.getElementById("humidity").innerText = data.hourly.relativehumidity_2m[0];
  document.getElementById("icon").src = icon(w.weathercode);
  document.getElementById("desc").innerText = desc(w.weathercode);

  // MODE EFFECT
  if(w.weathercode>=95){
    mode="storm";
    createRain();
  }
  else if(w.weathercode>=61){
    mode="rain";
    createRain();
  }
  else if(w.weathercode<=3){
    mode="fog";
    createFog();
  }
  else{
    mode="clear";
  }

  // HOURLY
  let html="";
  for(let i=1;i<=5;i++){
    html+=`
    <div>
      ${data.hourly.time[i].slice(11,16)}<br>
      ${data.hourly.temperature_2m[i]}°
    </div>`;
  }
  document.getElementById("hourly").innerHTML = html;
}

loadWeather();
setInterval(loadWeather,40000);
</script>
