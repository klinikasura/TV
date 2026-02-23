<!DOCTYPE html>
<html>
<head>
  <title>Aplikasi RS. Asura</title>
  <link href="http://10.10.20.250/dashboard/download.jpeg" rel="icon" type="image/png" />





  <style>
    body {
      margin: 0;
      padding: 0;
      overflow: hidden;
    }
    .iframe-container {
      position: fixed;
      top: 10;
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
      height: 1000px;
    }
    .iframe-container iframe:nth-child(2) {
      flex-grow: 1;
    }
    #fullScreenBtn {
      position: fixed;
      top: 0px;
      left: 1180px;
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


</head>
<body>
  <?php $image_url = 'https://snapwidget.com/embed/1092490'; ?>
 
  <div style="display: flex; justify-content: space-between;">
    <div class="iframe-container">
     
      <iframe src="http://10.10.20.250/dashboard/APPS-ROBOT/TV/MASTER/display_antrian.php" class="snapwidget-widget" allowtransparency="true" frameborder="0" scrolling="no" title="Klinik Asura"></iframe>
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
</body>
</html>

