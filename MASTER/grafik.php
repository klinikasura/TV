 <title>myROBOT-V80</title>
  <link href="http://10.10.20.250/dashboard/APPS-ROBOT/BUILDING APLIKASI/@API-GITHUB-V80/ROBOT-GITHUB/ROBOTV80.png" rel="icon" type="image/png" />
<meta http-equiv="refresh" content="240;url=grafik.php">
<button id="fullScreenBtn">Full Layar</button>
<audio autoplay>
  <source src="http://10.10.20.250/dashboard/APPS-ROBOT/TV/AUDIO/GRAFIK.mp3" type="audio/mpeg">
</audio>
<style>
  body {
    margin: 0;
    padding: 0;
    overflow: hidden;
  }
  .iframe-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: row;
  }
  .iframe-container iframe {
    width: 50%;
    height: 100%;
    border: none;
  }
  #fullScreenBtn {
    position: fixed;
    top: 20px;
    left: 10px;
    z-index: 1;
    background-color: #4CAF50;
    color: #FFFFFF;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }
  #fullScreenBtn:hover {
    background-color: #3e8e41;
  }
  .angka-pasien {
    position: absolute;
    top: 10px;
    left: 10px;
    font-size: 36px;
    font-weight: bold;
    color: #FFFFFF;
    background-color: #4CAF50;
    padding: 10px;
    border-radius: 5px;
  }
</style>

<?php 
  // ambil data pasien belum bayar dari database
  $koneksi = mysqli_connect("10.10.20.250", "root", "", "sikdraisyah");
  $query = "SELECT COUNT(*) as jumlah_pasien FROM reg_periksa WHERE status_bayar = 'Belum Bayar'";
  $result = mysqli_query($koneksi, $query);
  $row = mysqli_fetch_assoc($result);
  $jumlah_pasien = $row['jumlah_pasien'];
?>

<div class="angka-pasien"><?php echo $jumlah_pasien; ?></div>

<div class="iframe-container">
  <iframe src="http://10.10.20.250/dashboard/APPS-ROBOT/GRAFIK/index2-tv.php" class="snapwidget-widget" allowtransparency="true" frameborder="0" scrolling="no" title="Klinik Asura"></iframe>



  <iframe src="http://10.10.20.250/dashboard/APPS-ROBOT/TV/MASTER/grafik2.php" class="snapwidget-widget" allowtransparency="true" frameborder="0" scrolling="no" title="Klinik Asura"></iframe>
</div>




<div style="position: absolute; top: 420px; left: 20px; background-color: #fff; padding: 10px; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0,0,0,0.5);">





<iframe src="http://10.10.20.250/dashboard/APPS-ROBOT/TV/MASTER/pie.php" class="snapwidget-widget" allowtransparency="true" frameborder="0" scrolling="no" style="border:none; overflow:hidden; width:1225px; height:350px" title="Klinik Asura"></iframe>








<script>
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



