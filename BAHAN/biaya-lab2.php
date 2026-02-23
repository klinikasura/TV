<title>Aplikasi RS. Asura</title>
<link href="http://10.10.20.250/dashboard/download.jpeg" rel="icon" type="image/png" />
<meta http-equiv="refresh" content="1800;url=http://10.10.20.250/dashboard/APPS-ROBOT/TV/template-lab.php">
<audio autoplay>
  <source src="http://10.10.20.250/dashboard/APPS-ROBOT/TV/AUDIO/GRAFIK.mp3" type="audio/mpeg">
</audio>
<style>
  body {
    margin: 0;
    padding: 0;
    overflow: hidden;
  }
  .marquee {
    width: 100%;
    height: 50px;
    font-size: 24px;
    font-weight: bold;
    color: #FFFFFF;
    padding: 10px;
    border-radius: 5px;
  }
</style>

<?php 
  // ambil data dari database
  $koneksi = mysqli_connect("10.10.20.250", "root", "", "sikdraisyah");
  $query = "SELECT Pemeriksaan, biaya_item FROM template_laboratorium WHERE biaya_item > 0";
  $result = mysqli_query($koneksi, $query);
?>

<marquee behavior="scroll" direction="left" scrollamount="5" class="marquee">
  <?php
  $warna = array("black", "black", "black", "black", "black");
  $i = 0;
  while ($row = mysqli_fetch_assoc($result)) {
    echo "<span style='color:".$warna[$i]."'>".$row['Pemeriksaan']." - Rp. ".$row['biaya_item']."</span> | ";
    $i++;
    if ($i >= count($warna)) {
      $i = 0;
    }
  }
  ?>
</marquee>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    document.documentElement.requestFullscreen();
  });
</script>

