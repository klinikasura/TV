<title>Aplikasi RS. Asura</title>
<link href="http://10.10.20.250/dashboard/download.jpeg" rel="icon" type="image/png" />
<meta http-equiv="refresh" content="240;url=display-otomatis2.php">




<style>
    body { margin:0; padding:0; overflow:hidden; }
    .iframe-container {
        position:fixed; top:0; left:0; width:100%; height:100%;
        display:flex; flex-direction:column;
    }
    .iframe-container iframe { width:100%; border:none; }
    .iframe-container iframe:nth-child(1) { height:800px; }
    .iframe-container iframe:nth-child(2) { flex-grow:1; }

    #fullScreenBtn, #goToUrlBtn {
        position:fixed; top:8px; z-index:1;
        background:#4CAF50; color:#FFF; padding:10px 20px;
        border:none; border-radius:5px; cursor:pointer;
    }
    #fullScreenBtn { left:220px; }
    #goToUrlBtn    { left:240px; }
    #fullScreenBtn:hover, #goToUrlBtn:hover { background:#3e8e41; }
</style>

<?php $image_url = 'https://snapwidget.com/embed/1092490'; ?>

<div style="display:flex; justify-content:space-between;">
    <div class="iframe-container">
     
        <iframe src="http://10.10.20.250/dashboard/APPS-ROBOT/TV/panggil_pasien.php"
                class="snapwidget-widget" allowtransparency="true"
                frameborder="0" scrolling="no" title="Klinik Asura"></iframe>
    </div>
</div>

<script>
  const fullScreenBtn = document.getElementById('fullScreenBtn');

  fullScreenBtn.addEventListener('click', () => {
    if (document.fullscreenElement) {
      document.exitFullscreen();
    } else {
      document.documentElement.requestFullscreen();
    }
  });
</script>

