<title>Aplikasi RS. Asura</title>
<link href="http://10.10.20.250/dashboard/download.jpeg" rel="icon" type="image/png" />


 <button id="fullScreenBtn">Full Layar</button>

<style>
  body {
    margin: 0;
    padding: 0;
    overflow: hidden;
  }
  #my-video {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
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
      height: 300px;
    }
    .iframe-container iframe:nth-child(2) {
      flex-grow: 1;
    }
    #fullScreenBtn {
      position: fixed;
      top: 0px;
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




<button id="goFS">FULL LAYAR</button>
<button type="button" class="get_bg">

<script>
  var goFS = document.getElementById("goFS");
  goFS.addEventListener("click", function() {
    document.body.requestFullscreen();
  }, false);
</script>

<?php $video_file = 'http://10.10.20.250/dashboard/APPS-ROBOT/TV/VIDEO/PROFILE.mp4'; ?>

<div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%;">
  <link href="https://vjs.zencdn.net/7.11.4/video-js.css" rel="stylesheet" />
  <script src="https://vjs.zencdn.net/7.11.4/video.js"></script>
  <video id="my-video" class="video-js" controls preload="auto" loop autoplay muted playsinline>
    <source src="<?php echo $video_file; ?>" type="video/mp4" />
    <p class="vjs-no-js"> To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a> </p>
  </video>
  <script>
    var player = videojs('my-video');
    player.ready(function() {
      player.play();
    });
  </script>
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

