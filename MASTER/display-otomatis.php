 <title>myROBOT-V80</title>
  <link href="http://10.10.20.250/dashboard/APPS-ROBOT/BUILDING APLIKASI/@API-GITHUB-V80/ROBOT-GITHUB/ROBOTV80.png" rel="icon" type="image/png" />
<meta http-equiv="refresh" content="240;url=display-otomatis.php">

<button id="fullScreenBtn">Full Layar</button>



<style>
body { 
    margin:0; 
    padding:0; 
    overflow:hidden; 
    background: linear-gradient(135deg, #e0f7ff, #f0f9ff);
}

.iframe-container {
    position:fixed; 
    top:0; 
    left:0; 
    width:100%; 
    height:100%;
    display:flex; 
    flex-direction:column;
}

.iframe-container iframe { 
    width:100%; 
    border:none; 
}

.iframe-container iframe:nth-child(1) { height:300px; }
.iframe-container iframe:nth-child(2) { flex-grow:1; }

/* BUTTON */
#fullScreenBtn, #goToUrlBtn {
    position:fixed; 
    top:0px; 
    z-index:1;
    background: linear-gradient(135deg, #60a5fa, #3b82f6); /* biru muda */
    color:#FFF; 
    padding:10px 20px;
    border:none; 
    border-radius:8px; 
    cursor:pointer;
    font-weight:600;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}

#fullScreenBtn { left:20px; }
#goToUrlBtn    { left:240px; }

#fullScreenBtn:hover, #goToUrlBtn:hover { 
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    transform: scale(1.05);
}
.iframe-container {
    display: flex;
    flex-direction: column;
    gap: 20px;
    padding: 10px; /* jarak dari pinggir */
}
</style>

<?php $image_url = 'https://snapwidget.com/embed/1092490'; ?>

<div style="display:flex; justify-content:space-between;">
    <div class="iframe-container">
        <iframe src="http://10.10.20.250/dashboard/APPS-ROBOT/TV/hanya-pasien.php"
                class="snapwidget-widget" allowtransparency="true" style="border:none; overflow:hidden; width:100%; height:400px"
                frameborder="0" scrolling="no" title="Klinik Asura"></iframe>



        <iframe src="http://10.10.20.250/dashboard/APPS-ROBOT/TV/panggil_pasien.php"
                class="snapwidget-widget" allowtransparency="true"
                frameborder="0" scrolling="no" title="Klinik Asura"></iframe>
    </div>
</div>

<script>
    // Fullscreen otomatis saat halaman selesai dimuat
    document.addEventListener("DOMContentLoaded", function() {
        document.documentElement.requestFullscreen();
    });

    // Tombol Full Layar
    document.getElementById('fullScreenBtn').addEventListener('click', function() {
        if (document.fullscreenElement) {
            document.exitFullscreen();
        } else {
            document.documentElement.requestFullscreen();
        }
    });

    // Tombol Buka URL (buka di tab baru)
    document.getElementById('goToUrlBtn').addEventListener('click', function() {
        window.open('http://10.10.20.250/dashboard/APPS-ROBOT/TV/MASTER/display_antrian.php', '_blank');
    });
</script>

