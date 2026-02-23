<?php
session_start();
require_once 'config.php';
require_once 'includes.php';

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: http://10.10.20.250/dashboard/APPS-ROBOT/GITHUB/LOG/login.php');
    exit;
}

// Ambil data user berdasarkan ID di session
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM robot80_data_anggota WHERE id=$user_id";
$result = $mysqli->query($query);
$user = $result->fetch_assoc();

update_user_activity($user_id);
?>







<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
 <title>Aplikasi RS. Asura</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
       <link href="http://10.10.20.250/dashboard/download.jpeg" rel="icon" type="image/png" />

 <link rel="stylesheet" href="http://10.10.20.250/dashboard/APPS-ROBOT/assets/css/vendor.css">
    <link rel="stylesheet" href="http://10.10.20.250/dashboard/APPS-ROBOT/assets/css/style.css">
    <link rel="stylesheet" href="http://10.10.20.250/dashboard/APPS-ROBOT/assets/css/responsive.css">






    <style>
        .progress { display:none; width:100%; max-width:400px; height:20px; background:#eee; border-radius:4px; margin:10px 0; overflow:hidden; }
        .progress-bar { height:100%; width:0%; background:#28a745; transition:width .3s; }
        .msg { padding:8px; margin:5px 0; border-radius:4px; }
        .success { background:#d4edda; color:#155724; }
        .error   { background:#f8d7da; color:#721c24; }
    </style>
    <!-- jQuery (via CDN) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
    <h2>Backup Database</h2>

    <button id="btnBackup">Backup Sekarang</button>

    <div class="progress">
        <div class="progress-bar"></div>
    </div>

    <div id="status" class="msg" style="display:none;"></div>

    <script>
        $(function(){
            var $btn   = $('#btnBackup');
            var $prog  = $('.progress');
            var $bar   = $('.progress-bar');
            var $msg   = $('#status');

            $btn.on('click', function(){
                $btn.prop('disabled', true);
                $prog.show();
                $bar.css('width', '0%');
                $msg.hide().removeClass('success error');

                // Mulai backup via AJAX
                $.ajax({
                    url: 'backup.php',
                    type: 'POST',
                    xhr: function(){
                        var xhr = new window.XMLHttpRequest();
                        // Dapatkan progres (bisa pakai server‑side streaming, di sini cuma simulasi)
                        xhr.upload.addEventListener('progress', function(e){
                            if (e.lengthComputable) {
                                var pct = Math.round((e.loaded / e.total) * 100);
                                $bar.css('width', pct + '%');
                            }
                        }, false);
                        return xhr;
                    },
                    success: function(resp){
                        var parts = resp.split('|');
                        if (parts[0] === 'OK') {
                            $msg.addClass('success').html(parts[1]).show();
                        } else {
                            $msg.addClass('error').html(parts[1]).show();
                        }
                    },
                    error: function(){
                        $msg.addClass('error').html('Terjadi kesalahan jaringan.').show();
                    },
                    complete: function(){
                        $bar.css('width', '100%');
                        $btn.prop('disabled', false);
                        setTimeout(function(){ $prog.hide(); }, 2000);
                    }
                });
            });
        });
    </script>














      
    <!-- Footer Area -->
    
            <h1><div class="footer-bottom text-center">
                <ul>
                    <li>
                        <a href="http://10.10.20.250/dashboard/APPS-ROBOT/GITHUB/index-dashboard2.php">
<img border="0" src="http://10.10.20.250/dashboard/APPS-ROBOT/GITHUB/FILE/BERANDA.png"  height="45" width="45" /></a>
                            <i class=""></i>
                            <p>Beranda</p>
                        </a>
                    </li>
                    <li>
                        <a href="http://10.10.20.250/dashboard/APPS-ROBOT/GITHUB/index-dashboard-2-2.html">
<img border="0" src="http://10.10.20.250/dashboard/APPS-ROBOT/GITHUB/FILE/SATUSEHAT-INTEGRASI-VERSI-80-2.png"  height="50" width="50" /></a>
                            <i class=""></i>
                            <p>ASKES</p>
                        </a>
                    </li>
                    <li>
                        <a href="http://10.10.20.250/dashboard/APPS-ROBOT/GITHUB/WIDGET/NEW/AI-BACA-BARCODE-2">
<img border="0" src="http://10.10.20.250/dashboard/APPS-ROBOT/GITHUB/SCANRM2.webp"  height="50" width="50" /></a>
                            <i class=""></i>
                            <p>SCAN RM</p>
                        </a>
                    </li>
                    <li>
                        <a href="http://10.10.20.250/dashboard/APPS-ROBOT/GITHUB/index-dashboard-Playstore.html">
<img border="0" src="http://10.10.20.250/dashboard/APPS-ROBOT/GITHUB/FILE/PLAYSTORE.png"  height="45" width="45" /></a>
                            <i class=""></i>
                            <p>PLAYSTORE</p>
                        </a>
                    </li>
                    <li>
                        <a href="http://10.10.20.250/dashboard/APPS-ROBOT/GITHUB/logout2.php">
<img border="0" src="http://10.10.20.250/dashboard/APPS-ROBOT/GITHUB/LOGOUT.webp"  height="45" width="45" /></a>
                            <i class=""></i>
                            <p>LOGOUT</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
</div>



    
     









</body>
</html>
