<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>myROBOT-V80</title>

<link href="http://10.10.20.250/dashboard/APPS-ROBOT/BUILDING APLIKASI/@API-GITHUB-V80/ROBOT-GITHUB/ROBOTV80.png" rel="icon" type="image/png" />

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body {
  margin: 0;
  padding: 0;
  overflow: hidden;
  font-family: 'Segoe UI', 'Poppins', Arial, sans-serif;
  color: white;

}

/* BUTTON */
#fullScreenBtn {
  position: fixed;
  top: 2px;
  left: 12px;
  z-index: 10;
  color: white;
  padding: 10px 20px;
  border-radius: 8px;
  cursor: pointer;
  background: #1e88e5;
}

/* CONTAINER */
.container {
  display: flex;
  flex-direction: column;
  height: 100vh;
}

/* IFRAME */
iframe {
  width: 100%;
  height: 80%;
  border: none;
}

/* CHART AREA */
.charts {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 10px;
  padding: 10px;
  margin-top: -10px;
}

/* CHART BOX */
.chart-box {
background: #1e88e5;
  border-radius: 15px;
  padding: 10px;
  text-align: center;
}

h3 {
  margin-bottom: 5px;
}

canvas {
  max-height: 250px;
}
</style>
</head>

<body>



<audio autoplay>
  <source src="http://10.10.20.250/dashboard/APPS-ROBOT/TV/AUDIO/JADWAL.mp3" type="audio/mpeg">
</audio>

<div class="container">
<p>
  <!-- IFRAME -->
  <iframe src="http://10.10.20.250/dashboard/APPS-ROBOT/ANTRIAN-POLIKLINIK/poli-github-tv-2.php"
    scrolling="no"
    title="Klinik Asura">
  </iframe>


<iframe src="http://10.10.20.250/dashboard/APPS-ROBOT/TV/pasien8.php" class="snapwidget-widget" allowtransparency="true" frameborder="0" scrolling="no" style="border:none; overflow:hidden; width:100%; height:350px" title="Klinik Asura"></iframe>

<button id="fullScreenBtn">Full Layar</button>

  

</body>
</html>
