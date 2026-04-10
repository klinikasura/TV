<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
  <title>myROBOT-V80</title>
  <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
  <link href="http://10.10.20.250/dashboard/APPS-ROBOT/BUILDING APLIKASI/@API-GITHUB-V80/ROBOT-GITHUB/ROBOTV80.png" rel="icon" type="image/png" />

<meta http-equiv="refresh" content="0;url=https://klinikasura.github.io/APLIKASI-V80/APPS"> 









<style>
* {
  margin:0;
  padding:0;
  box-sizing:border-box;
  font-family:'Segoe UI', sans-serif;
}

/* BACKGROUND ANIMASI */
body {
  overflow:hidden;
  background: linear-gradient(-45deg, #bdefff, #d9f6ff, #a6e4ff, #c9f2ff);
  background-size: 400% 400%;
  animation: gradientMove 10s ease infinite;
}

@keyframes gradientMove {
  0% { background-position:0% 50%; }
  50% { background-position:100% 50%; }
  100% { background-position:0% 50%; }
}

/* OVERLAY */
.overlay {
  position:fixed;
  width:100%;
  height:100%;
  display:flex;
  justify-content:center;
  align-items:center;
}

/* POPUP */
.popup {
  width:320px;
  padding:25px;
  border-radius:20px;
  text-align:center;

  background: rgba(255,255,255,0.7);
  backdrop-filter: blur(12px);

  box-shadow: 0 10px 25px rgba(0,0,0,0.2);
  transition:0.2s;
}

/* ROBOT IMAGE */
.robot-img {
  width:120px;
  margin-bottom:10px;

  /* efek hidup dikit (optional halus) */
  animation: float 3s ease-in-out infinite;
}

@keyframes float {
  0%,100% { transform: translateY(0px); }
  50% { transform: translateY(-8px); }
}

/* TITLE */
.title {
  font-size:22px;
  margin-top:10px;
  color:#4fc3f7;
  font-weight:bold;
  letter-spacing:2px;
}

/* SUBTEXT */
.sub {
  font-size:14px;
  color:#555;
  margin-top:5px;
}

</style>




<audio autoplay>
    
<source src="https://klinikasura.github.io/APLIKASI-V80/APPS/ROBOT.mp3" type="audio/mpeg">
    </audio>

</head>


<body>

<div class="overlay">
  <div class="popup" id="popup">

    <!-- ROBOT GAMBAR -->
    <img src="http://10.10.20.250/dashboard/APPS-ROBOT/BUILDING APLIKASI/@API-GITHUB-V80/ROBOT-GITHUB/ROBOTV80.png" class="robot-img">

    <div class="title">myROBOT-V80</div>
    <div class="sub">Versi 8.0</div>

  </div>
</div>

<script>
// PARALLAX (GERAK IKUT MOUSE)
const popup = document.getElementById("popup");

document.addEventListener("mousemove", (e)=>{
  let x = (window.innerWidth/2 - e.pageX)/50;
  let y = (window.innerHeight/2 - e.pageY)/50;
  popup.style.transform = `rotateY(${x}deg) rotateX(${y}deg)`;
});
</script>

</body>
</html>
