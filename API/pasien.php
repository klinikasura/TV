<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Aplikasi RS. Asura</title>
 
    <link href="http://10.10.20.250/dashboard/download.jpeg" rel="icon" type="image/png" />

    <!-- CSS yang sudah ada -->
    <link href="css-version/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="http://10.10.20.250/dashboard/APPS-ROBOT/assets/css/vendor.css">
    <link rel="stylesheet" href="http://10.10.20.250/dashboard/APPS-ROBOT/assets/css/style.css">
    <link rel="stylesheet" href="http://10.10.20.250/dashboard/APPS-ROBOT/assets/css/responsive.css">

    <style>
/* --- Scroll horizontal pada body --- */
body {
    overflow-x: auto;
    white-space: nowrap;
    margin: 0;
    padding: 0;
}
body > * {
    display: inline-block;
    vertical-align: top;
    white-space: normal;
}

/* --- Force landscape on portrait (TV) --- */
@media screen and (orientation: portrait) {
    body {
        transform: rotate(-90deg);
        transform-origin: top left;
        width: 100vh;
        height: 100vw;
        overflow: hidden;
        position: absolute;
        top: 100%;
        left: 0;
    }
}

/* --- Responsive wrapper & iframe --- */
.iframe-wrapper {
    display: inline-block;          /* supaya tetap inline dengan elemen lain */
    overflow: auto;                /* aktifkan scroll bila konten lebih lebar */
    -webkit-overflow-scrolling: touch;
    /* Default ukuran (untuk TV) */
    width: 2100px;
    height: 2100px;
}

/* Pastikan iframe mengisi penuh wrapper */
.iframe-wrapper iframe {
    width: 100%;
    height: 100%;
    border: 0;
}

/* HP (portrait) – kecilkan wrapper */
@media (max-width: 800px) {
    .iframe-wrapper {
        width: 100vw;   /* pakai lebar layar */
        height: 100vh;  /* pakai tinggi layar */
    }
}

/* TV (landscape) – pakai ukuran tetap */
@media (min-width: 1200px) and (orientation: landscape) {
    .iframe-wrapper {
        width: 2100px;
        height: 2100px;
    }
}
</style>

</head>
<body>


<div class="footer-area">
        <div class="footer-top text-center" style="background-image: url();">
            <div class="container">            </div>
        </div>










    <!-- Konten lain (jika ada) -->

    <div class="iframe-wrapper">
        <iframe src="http://10.10.20.250/dashboard/APPS-ROBOT/TV/MASTER/display-otomatis.php"
                frameborder="0" marginwidth="0" marginheight="0" scrolling="no">
            <h2 style="text-align:center">
                <a href="https://www.al-habib.info/islamic-widget/prayer-times.htm"></a>
            </h2>
        </iframe>
    </div>



<p>   <p>&nbsp;</p>



<div class="footer-area">
        <div class="footer-top text-center" style="background-image: url();">
            <div class="container">            </div>
        </div>







    <div class="footer-area">
        <div class="footer-top text-center" style="background-image: url();">
            <div class="container">            </div>
        </div>






    <div class="footer-area">
        <div class="footer-top text-center" style="background-image: url();">
            <div class="container">            </div>
        </div>



    <!-- Footer Area -->
    <div class="footer-bottom text-center">
        <ul>
            <li><a href="http://10.10.20.250/dashboard/APPS-ROBOT/GITHUB/index-dashboard2.php"><img border="0" src="http://10.10.20.250/dashboard/APPS-ROBOT/GITHUB/FILE/BERANDA.png" height="45" width="45" /><p>Beranda</p></a></li>
            <li><a href="http://10.10.20.250/dashboard/APPS-ROBOT/GITHUB/index-dashboard-2-2.html"><img border="0" src="http://10.10.20.250/dashboard/APPS-ROBOT/GITHUB/FILE/SATUSEHAT-INTEGRASI-VERSI-80-2.png" height="50" width="50" /><p>ASKES</p></a></li>
            <li><a href="http://10.10.20.250/dashboard/APPS-ROBOT/GITHUB/WIDGET/NEW/AI-BACA-BARCODE-2"><img border="0" src="http://10.10.20.250/dashboard/APPS-ROBOT/GITHUB/SCANRM2.webp" height="45" width="45" /><p>SCAN RM</p></a></li>
            <li><a href="http://10.10.20.250/dashboard/APPS-ROBOT/GITHUB/index-dashboard-Playstore.html"><img border="0" src="http://10.10.20.250/dashboard/APPS-ROBOT/GITHUB/FILE/PLAYSTORE.png" height="45" width="45" /><p>PLAYSTORE</p></a></li>
            <li><a href="http://10.10.20.250/dashboard/APPS-ROBOT/GITHUB/logout2.php"><img border="0" src="http://10.10.20.250/dashboard/APPS-ROBOT/GITHUB/LOGOUT.webp" height="45" width="45" /><p>LOGOUT</p></a></li>
        </ul>
    </div>

</body>
</html>

