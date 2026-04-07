<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>myROBOT-V80</title>
<meta http-equiv="refresh" content="40;url=pie.php">

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
  top: 440px;
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
  height: 60%;
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



<div class="container">
  <!-- CHART -->
  <div class="charts">

<div class="chart-box">
  <h3>Cara Bayar Pasien</h3>
  <canvas id="chartCaraBayar"></canvas>
</div>

    <div class="chart-box">
      <h3>Status Pasien Bayar</h3>
      <canvas id="chartBayar"></canvas>
    </div>

    <div class="chart-box">
      <h3>Poliklinik</h3>
      <canvas id="chartPoli"></canvas>
    </div>

    <div class="chart-box">
      <h3>Dokter</h3>
      <canvas id="chartDokter"></canvas>
    </div>

  </div>

</div>

<script>
let charts = {};

function createChart(id, labels, data) {
  if (charts[id]) {
    charts[id].data.labels = labels;
    charts[id].data.datasets[0].data = data;
    charts[id].update();
    return;
  }

  charts[id] = new Chart(document.getElementById(id), {
    type: 'pie',
    data: {
      labels: labels,
      datasets: [{
        data: data,
        backgroundColor: [
          '#6C5CE7','#00CEC9','#FF7675','#FDCB6E',
          '#55EFC4','#FAB1A0','#81ECEC','#A29BFE'
        ]
      }]
    },
    options: {
      plugins: {
        legend: {
          labels: {
            color: 'white',
            font: {
              size: 12
            }
          }
        },
        tooltip: {
          titleColor: 'white',
          bodyColor: 'white'
        }
      }
    }
  });
}

async function loadData() {
  const res = await fetch('dashboard_today.php?t=' + new Date().getTime());
  const data = await res.json();

createChart(
  'chartCaraBayar',
  data.cara_bayar.map(x => x.label),
  data.cara_bayar.map(x => x.jumlah)
);
  createChart('chartBayar', data.status_bayar.map(x => x.label), data.status_bayar.map(x => x.jumlah));
  createChart('chartPoli', data.kd_poli.map(x => x.label), data.kd_poli.map(x => x.jumlah));
  createChart('chartDokter', data.kd_dokter.map(x => x.label), data.kd_dokter.map(x => x.jumlah));
}

// load pertama
loadData();

// realtime tiap 5 detik
setInterval(loadData, 5000);
</script>

<script>
// FULLSCREEN
document.addEventListener("DOMContentLoaded", function() {
  document.documentElement.requestFullscreen();
});

document.getElementById('fullScreenBtn').addEventListener('click', function() {
  if (document.fullscreenElement) {
    document.exitFullscreen();
  } else {
    document.documentElement.requestFullscreen();
  }
});
</script>

</body>
</html>
