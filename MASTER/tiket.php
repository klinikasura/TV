 <title>myROBOT-V80</title>
  <link href="http://10.10.20.250/dashboard/APPS-ROBOT/BUILDING APLIKASI/@API-GITHUB-V80/ROBOT-GITHUB/ROBOTV80.png" rel="icon" type="image/png" />


  <meta http-equiv="refresh" content="240;url=tiket.php">



 <button id="fullScreenBtn">Full Layar</button>


<audio autoplay>
    <source src="http://10.10.20.250/dashboard/APPS-ROBOT/TV/AUDIO/TIKET.mp3" type="audio/mpeg">
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
      flex-direction: column;
    }
    .iframe-container iframe {
      width: 100%;
      border: none;
    }
    .iframe-container iframe:nth-child(1) {
      height: 300px;
    }
    .iframe-container iframe:nth-child(2) {
      flex-grow: 1;
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
  </style>







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
    flex-direction: column;
  }
  .iframe-container iframe {
    width: 100%;
    border: none;
  }
  .iframe-container iframe:nth-child(1) {
    height: 800px;
  }
  .iframe-container iframe:nth-child(2) {
    flex-grow: 1;
  }
</style>



<?php $image_url = 'https://snapwidget.com/embed/1092490'; ?>

<div style="display: flex; justify-content: space-between;">
  <div class="iframe-container">
    <iframe src="http://10.10.20.250/dashboard/APPS-ROBOT/DAFTAR-PASIEN/index-daftarpasien-robot-tv.php" class="snapwidget-widget" allowtransparency="true" frameborder="0" scrolling="no" title="Klinik Asura"></iframe>
   
  </div>
</div>






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



