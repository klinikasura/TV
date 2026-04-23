<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>myROBOT-V80</title>
  <link href="http://10.10.20.250/dashboard/APPS-ROBOT/BUILDING APLIKASI/@API-GITHUB-V80/ROBOT-GITHUB/ROBOTV80.png" rel="icon" type="image/png" />
<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #4facfe, #c2e9fb);
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0;
}

.container {
    background: white;
    padding: 30px 20px;
    border-radius: 20px;
    text-align: center;
    width: 90%;
    max-width: 350px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.pin-box {
    display: grid;
    grid-template-columns: repeat(4, 60px);
    justify-content: center;
    gap: 10px;
}

.pin-box input {
    width: 60px;
    height: 60px;
    font-size: 24px;
    text-align: center;
    border-radius: 10px;
    border: 2px solid #ddd;
    margin: auto;
}
button {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: none;
    background: #4facfe;
    color: white;
    font-size: 16px;
}

button:disabled {
    background: gray;
}

.error {
    background: red;
    color: white;
    padding: 10px;
    border-radius: 10px;
    margin-bottom: 15px;
}
</style>
</head>

<body>

<div class="container">
<center>
  <img src="LOGIN-ICON.png" width="250" height="180" class="fancy3d">
</center>

<style>
.fancy3d {
  display: block;
  transform-style: preserve-3d;
  animation: floatRotate 6s ease-in-out infinite, glow 2s alternate infinite;
}

@keyframes floatRotate {
  0%   { transform: translateY(0px) rotateY(0deg) rotateX(0deg); }
  25%  { transform: translateY(-15px) rotateY(45deg) rotateX(10deg); }
  50%  { transform: translateY(0px) rotateY(180deg) rotateX(0deg); }
  75%  { transform: translateY(15px) rotateY(270deg) rotateX(-10deg); }
  100% { transform: translateY(0px) rotateY(360deg) rotateX(0deg); }
}

@keyframes glow {
  0%   { box-shadow: 0 0 10px rgba(0,255,255,0.5); }
  50%  { box-shadow: 0 0 25px rgba(0,255,255,0.9); }
  100% { box-shadow: 0 0 10px rgba(0,255,255,0.5); }
}

.fancy3d {
  border-radius: 20px; /* sudut halus */
  box-shadow: 0 0 15px rgba(0,255,255,0.5);
}
</style>


 <h2>APLIKASI E-RM</h2>
    <h4>Masukkan PIN / Password SIMRS</h4>

    <?php if(isset($_GET['error'])) { ?>
        <?php if($_GET['error']=="locked") { ?>
            <div class="error">
                ⛔ Tunggu <span id="timer"><?php echo $_GET['time'] ?? 60; ?></span> detik
            </div>
        <?php } else { ?>
            <div class="error">❌ PIN / Password Salah</div>
        <?php } ?>
    <?php } ?>

    <form method="POST" action="proses_login.php" id="loginForm">
        <div class="pin-box">
            <?php for($i=0;$i<8;$i++) { ?>
                <input type="text" maxlength="1" class="pin" inputmode="numeric" pattern="[0-9]*">
            <?php } ?>
        </div>

        <input type="hidden" name="pin" id="fullPin">
<p>
        <button type="submit" id="loginBtn">MASUK</button>
<p>
by. myROBOT-V80  <img src="http://10.10.20.250/dashboard/APPS-ROBOT/GITHUB/CAPTCHA/captcha.php" /> 	<p> IP : <?php
// Mengetahui IP Pengunjung
function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'IP tidak dikenali';
    return $ipaddress;
}
   
   
// Mengetahui web browser yang digunakan pengunjung
function get_client_browser() {
    $browser = '';
    if(strpos($_SERVER['HTTP_USER_AGENT'], 'Netscape'))
        $browser = 'Netscape';
    else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox'))
        $browser = 'Firefox';
    else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome'))
        $browser = 'Chrome';
    else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera'))
        $browser = 'Opera';
    else if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE'))
        $browser = 'Internet Explorer';
    else
        $browser = 'Other';
    return $browser;
}
   echo " ". get_client_ip()."<br>";
   
?>
    </form>
</div>

<script>
const inputs = document.querySelectorAll(".pin");
const fullPin = document.getElementById("fullPin");
const form = document.getElementById("loginForm");

// 🔥 AUTO FOCUS SAAT LOAD
window.addEventListener("load", () => {
    inputs[0].focus();
});

// PIN handling
inputs.forEach((input, i) => {

    input.addEventListener("input", () => {

        // hanya angka
        input.value = input.value.replace(/[^0-9]/g, "");

        if (input.value && i < inputs.length - 1) {
            inputs[i + 1].focus();
        }

        updatePin();

        // auto submit kalau sudah penuh
        if (fullPin.value.length === inputs.length) {
            form.submit();
        }
    });

    input.addEventListener("keydown", (e) => {

        // BACKSPACE
        if (e.key === "Backspace") {
            if (!input.value && i > 0) {
                inputs[i - 1].focus();
            }
        }

        // ENTER = hapus / mundur
        if (e.key === "Enter") {
            e.preventDefault();

            if (input.value) {
                input.value = "";
            } else if (i > 0) {
                inputs[i - 1].focus();
                inputs[i - 1].value = "";
            }

            updatePin();
        }
    });
});

// ENTER global = submit kalau penuh
document.addEventListener("keydown", function(e) {
    if (e.key === "Enter") {
        if (fullPin.value.length === inputs.length) {
            form.submit();
        }
    }
});

function updatePin() {
    let pin = "";
    inputs.forEach(i => pin += i.value);
    fullPin.value = pin;
}

// 🔥 TIMER LOCK
let timerElement = document.getElementById("timer");

if (timerElement) {
    let timeLeft = parseInt(timerElement.innerText) || 60;
    let btn = document.getElementById("loginBtn");

    btn.disabled = true;

    let countdown = setInterval(() => {
        timeLeft--;
        timerElement.innerText = timeLeft;

        if (timeLeft <= 0) {
            clearInterval(countdown);

            timerElement.innerText = "0";

            setTimeout(() => {
                timerElement.innerText = "Silakan login...";
            }, 1000);

            setTimeout(() => {
                window.location.href = "index.php";
            }, 8000);
        }
    }, 1000);
}
</script>








</body>
</html>
