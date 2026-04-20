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

    <div id="desc">myROBOT-V80....</div>

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
  height:220px;
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

.temp { font-size:32px; }

#desc {
  font-size:24px;
  text-align:center;
}

.details {
  font-size:18px;
  text-align:center;
}

/* 🌙NIGHT */
.night {
  text-align:center;
  font-size:14px;
  margin-top:4px;
  color:#8be9fd;
  text-shadow:#8be9fd;
}

/* POPUP CYBER */
.popup {
  position:absolute;
  top:10px;
  left:50%;
  transform:translateX(-50%) scale(0.8);
  background:rgba(0,0,0,0.7);
  color:#00f7ff;
  padding:6px 12px;
  border-radius:8px;
  font-size:10px;
  opacity:0;

  border:1px solid rgba(0,255,255,0.6);
  box-shadow:0 0 6px #00f7ff;
  transition:all 0.25s;
}

.popup.show {
  opacity:1;
  transform:translateX(-50%) scale(1);
}
</style>

<script>
const lat = -3.238;
const lon = 104.807;

const canvas = document.getElementById("effectCanvas");
const ctx = canvas.getContext("2d");

let particles = [];
let mode = "clear";
let lastWeatherCode = null;

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
    ctx.fillStyle="rgba(140,120,140,0.2)";
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

/* LIGHTNING */
setInterval(()=>{
  if(mode==="storm"){
    flash.classList.add("active");
    setTimeout(()=>flash.classList.remove("active"),200);
  }
},5000);

/* POPUP */
function showPopup(text){
  const p=document.getElementById("popup");
  p.innerText=text;
  p.classList.add("show");
  setTimeout(()=>p.classList.remove("show"),2500);
}

/* SOUND ONLINE */
function playWeatherSound(code){
  let src="";

  if(code>=95)
    src="https://cdn.pixabay.com/download/audio/2022/03/15/audio_115b9b7e1c.mp3";
  else if(code>=61)
    src="https://cdn.pixabay.com/download/audio/2022/02/23/audio_4c4b1f3b56.mp3";
  else if(code<=3)
    src="https://cdn.pixabay.com/download/audio/2021/09/06/audio_6b1f9c2bdf.mp3";
  else
    src="https://cdn.pixabay.com/download/audio/2022/03/10/audio_d0f9fd0b6c.mp3";

  let a=new Audio(src);
  a.volume=0.5;
  a.play().catch(()=>{});
}

/* ICON */
function icon(code){
  if(code>=95) return "JPG/95-95.gif";
  if(code>=61) return "JPG/61-61-61.gif";
  if(code<=3) return "JPG/3-3.gif";
  return "JPG/CERAH.png";
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
  const url=`https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current_weather=true&hourly=temperature_2m,weathercode,relativehumidity_2m&timezone=auto`;

  const res=await fetch(url);
  const data=await res.json();
  const w=data.current_weather;

  temp.innerText=Math.round(w.temperature)+"°";
  wind.innerText=w.windspeed;
  humidity.innerText=data.hourly.relativehumidity_2m[0];
  iconEl=document.getElementById("icon");
  iconEl.src=icon(w.weathercode);
  descEl=document.getElementById("desc");
  descEl.innerText=desc(w.weathercode);

  /* 🌙 NIGHT SMART */
  let temps=[], codes={};

  for(let i=0;i<data.hourly.time.length;i++){
    let h=parseInt(data.hourly.time[i].slice(11,13));
    if(h>=18 && h<=23){
      temps.push(data.hourly.temperature_2m[i]);
      let c=data.hourly.weathercode[i];
      codes[c]=(codes[c]||0)+1;
    }
  }

  let avg=null;
  if(temps.length){
    avg=temps.reduce((a,b)=>a+b)/temps.length;
  }

  let dom=null,max=0;
  for(let c in codes){
    if(codes[c]>max){
      max=codes[c];
      dom=c;
    }
  }

  if(avg!==null){
    nightForecast.innerText=
      "Malam : "+Math.round(avg)+"° | "+desc(parseInt(dom));
  }

  /* CHANGE DETECT */
  if(lastWeatherCode!==null && lastWeatherCode!==w.weathercode){
    playWeatherSound(w.weathercode);

    let txt="PERINGATAN CUACA : ";
    if(w.weathercode>=95) txt+="BADAI";
    else if(w.weathercode>=61) txt+="HUJAN";
    else if(w.weathercode<=3) txt+="MENDUNG";
    else txt+="CERAH";

    showPopup(txt);
  }

  lastWeatherCode=w.weathercode;

  if(w.weathercode>=95){mode="storm";createRain();}
  else if(w.weathercode>=61){mode="rain";createRain();}
  else if(w.weathercode<=3){mode="fog";createFog();}
  else{mode="clear";}
}

loadWeather();
setInterval(loadWeather,40000);
</script>
