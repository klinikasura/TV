<!-- index.php -->
<!DOCTYPE html>
<html>
<head>
<title>myROBOT-V80</title>
  <link href="http://10.10.20.250/dashboard/APPS-ROBOT/BUILDING APLIKASI/@API-GITHUB-V80/ROBOT-GITHUB/ROBOTV80.png" rel="icon" type="image/png" />
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, sans-serif;
        background: #eaf6fb;
        padding: 30px;
    }

    h2 {
        color: #2a7da8;
    }

    #cari_pasien {
        width: 300px;
        padding: 10px 15px;
        border: 1px solid #b3d9ea;
        border-radius: 8px;
        outline: none;
        font-size: 14px;
        transition: 0.3s;
    }

    #cari_pasien:focus {
        border-color: #4fc3f7;
        box-shadow: 0 0 8px rgba(79, 195, 247, 0.5);
    }

    #popup_pasien {
        display: none;
        width: 320px;
        margin-top: 10px;
        padding: 15px;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border-left: 5px solid #4fc3f7;
        animation: fadeIn 0.3s ease-in-out;
    }

    #popup_pasien h3 {
        margin-top: 0;
        color: #2a7da8;
        border-bottom: 1px solid #e0f2f9;
        padding-bottom: 5px;
    }

    #hasil_pasien p {
        margin: 5px 0;
        font-size: 14px;
        color: #333;
    }

    #hasil_pasien b {
        color: #0288d1;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-5px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<style>
.btn-print {
    display: inline-block;
    padding: 10px 20px;
    background: #007bff;
    color: white;
    font-weight: bold;
    font-family: Tahoma, Arial, sans-serif;
    text-decoration: none;
    border-radius: 6px;
    border: 2px solid #0056b3;
    box-shadow: 0 3px 6px rgba(0,0,0,0.3);
    cursor: pointer;
    transition: 0.3s;
}
.btn-print:hover {
    background: #0056b3;
    transform: translateY(-2px);
}

</style>

<style>



/* Reset & Global */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', sans-serif;
}
body {
    background: #f2f4f8;
    width: 100%;
    min-height: 100vh;
    margin: 0;
    padding-bottom: 100px;
}


/* HEADER */
.header {
    background: linear-gradient(135deg,#4e8cff,#6fb1ff);
    padding: 20px;
    color: #fff;
    border-bottom-left-radius: 30px;
    border-bottom-right-radius: 30px;
    position: sticky;
    top: 0;
    z-index: 999;
}
.header-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.header-top div small {
    font-size: 12px;
    display: block;
    margin-bottom: 5px;
}
.icons {
    display: flex;
    gap: 10px;
}
.icon {
    width: 50px;
    height: 50px;
    background: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
.icon img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}

/* CARD */
.card {
    background: #fff;
    margin: -30px 20px 20px;
    border-radius: 20px;
    padding: 20px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}
.balance-btn {
    margin: 10px 0;
    padding: 10px 15px;
    background: #eef2ff;
    border: none;
    border-radius: 10px;
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s;
}
.balance-btn:hover {
    background: #d4e0ff;
}
.card-number {
    font-weight: bold;
    letter-spacing: 2px;
    margin-top: 10px;
}
.card-footer {
    display: flex;
    justify-content: space-between;
    font-size: 12px;
    color: #777;
    margin-top: 10px;
}

/* MENU GRID */
.menu {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    margin: 20px 10px;
    gap: 15px;
}
.menu-item {
    background: #fff;
    width: 70px;
    height: 70px;
    border-radius: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    font-size: 12px;
    text-align: center;
    cursor: pointer;
    transition: 0.3s;
}
.menu-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
}
.menu-item img {
    width: 40px;
    height: 40px;
    margin-bottom: 5px;
}

/* SECTION */
.section {
    padding: 0 20px;
    margin-top: 20px;
}
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}
.section-header h3 {
    font-size: 16px;
    color: #333;
}
.section-header a {
    color: #4e8cff;
    text-decoration: none;
    font-size: 12px;
}

/* ACTIVITY BOX */
.activity-box, .success-box {
    background: #fff;
    border-radius: 20px;
    padding: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    margin-bottom: 15px;
}
.activity-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}
.activity-item:last-child {
    border-bottom: none;
}
.left {
    display: flex;
    gap: 10px;
    align-items: center;
}
.icon-box {
    width: 45px;
    height: 45px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: bold;
}
.google { background: #ff6a5c; }
.bitcoin { background: #7bc96f; }
.dividend { background: #f5c04f; }

.red { color: #e53935; font-weight: bold; }
.green { color: #43a047; font-weight: bold; }
.blue { color: #4e8cff; font-weight: bold; }

/* SEND MONEY */
.send-money {
    display: flex;
    gap: 15px;
    overflow-x: auto;
    padding-bottom: 10px;
}
.send-item {
    min-width: 70px;
    text-align: center;
    flex-shrink: 0;
}
.send-item img {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    object-fit: cover;
    margin-bottom: 5px;
}

/* BOTTOM NAV */
.bottom-nav {
    position: fixed;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    max-width: 420px;
    width: 100%;
    background: #fff;
    display: flex;
    justify-content: space-around;
    padding: 10px 0;
    border-top-left-radius: 25px;
    border-top-right-radius: 25px;
    box-shadow: 0 -5px 20px rgba(0,0,0,0.1);
}
.nav-item {
    font-size: 12px;
    text-align: center;
}
.home-btn {
    background: #4e8cff;
    width: 55px;
    height: 55px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    margin-top: -30px;
    font-size: 22px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

/* RESPONSIVE */
@media (max-width: 360px) {
    .menu-item { width: 60px; height: 60px; }
    .send-item img { width: 50px; height: 50px; }
    .card { margin: -30px 10px 20px; padding: 15px; }
}


.scroll-box {
    width: 100%;
    height: 300px;          /* tinggi area tampilan di web kamu */
    overflow: auto;         /* ini bikin bisa geser kanan & bawah */
    border: 1px solid #ddd;
    border-radius: 8px;
    background: #00000008;
}

/* ukuran asli layar antrian */
.scroll-box iframe {
    width: 1200px;   /* lebar dashboard TV biasanya */
    height: 580px;   /* tinggi konten antrian */
    border: none;
}

/* ===== LINK GLOBAL ===== */
a{
text-decoration:none;
color:#333;
transition:0.3s;
}

a:hover{
color:#4e8cff;
}


/* ===== BUTTON LINK STYLE ===== */
.link-btn{

display:inline-block;

padding:10px 15px;

background:linear-gradient(135deg,#4e8cff,#6fb1ff);

color:white;

border-radius:10px;

font-size:14px;

font-weight:bold;

box-shadow:0 5px 15px rgba(0,0,0,0.15);

transition:0.3s;

}

.link-btn:hover{

transform:translateY(-3px);

box-shadow:0 8px 20px rgba(0,0,0,0.25);

}


/* ===== ACTIVITY BOX LINK ===== */
.activity-box a{

display:block;

color:inherit;

}

.activity-box:hover{

transform:translateY(-2px);

transition:0.3s;

box-shadow:0 12px 25px rgba(0,0,0,0.12);

}


/* ===== SUCCESS BOX STYLE ===== */
.success-box{

background:linear-gradient(135deg,#ffffff,#f3f6ff);

border-left:5px solid #4e8cff;

}


/* ===== SCROLL BOX ANTRIAN ===== */
.scroll-box{

width:100%;

height:320px;

overflow:auto;

border-radius:15px;

box-shadow:0 5px 20px rgba(0,0,0,0.08);

background:#fff;

}


/* ===== IFRAME STYLE ===== */
iframe{

border-radius:10px;

}


/* ===== POPUP PEMBAYARAN ===== */
#popupBayar{

position:fixed;

top:20px;

right:20px;

background:#4caf50;

color:white;

padding:15px 20px;

border-radius:12px;

box-shadow:0 5px 20px rgba(0,0,0,0.3);

display:none;

font-weight:bold;

z-index:9999;

animation:slideNotif 0.5s ease;

}


/* animasi notif */
@keyframes slideNotif{

from{
opacity:0;
transform:translateX(50px);
}

to{
opacity:1;
transform:translateX(0);
}

}


/* ===== MENU ITEM LINK ===== */
.menu a{

display:block;

}

.menu-item{

cursor:pointer;

}


/* ===== SEND MONEY ITEM ===== */
.send-item{

background:#fff;

padding:10px;

border-radius:15px;

box-shadow:0 5px 20px rgba(0,0,0,0.08);

transition:0.3s;

}

.send-item:hover{

transform:translateY(-4px);

box-shadow:0 10px 25px rgba(0,0,0,0.15);

}


/* ===== ICON BOX HOVER ===== */
.icon-box{

transition:0.3s;

}

.icon-box:hover{

transform:scale(1.1);

}


/* ===== BOTTOM NAV HOVER ===== */
.bottom-nav img{

transition:0.3s;

}

.bottom-nav img:hover{

transform:scale(1.2);

}


/* ===== FULLSCREEN BUTTON (opsional) ===== */
.fullscreen-btn{

position:fixed;

top:15px;

right:15px;

background:#4e8cff;

color:white;

border:none;

padding:10px 15px;

border-radius:10px;

cursor:pointer;

font-size:14px;

box-shadow:0 5px 15px rgba(0,0,0,0.2);

}

.fullscreen-btn:hover{

background:#3a72d6;

}


@keyframes swing {
  0% { transform: rotate(0deg); }
  25% { transform: rotate(10deg); }
  50% { transform: rotate(0deg); }
  75% { transform: rotate(-10deg); }
  100% { transform: rotate(0deg); }
}

#goyang {
  display: inline-block;
  animation: swing 1s infinite ease-in-out;
  transform-origin: top center;
}


</style>




</head>
<body>

<button class="btn-print" onclick="window.print()">PRINT HALAMAN</button>

<input type="text" id="cari_pasien" placeholder="No RM / KTP / Nama">

<div id="popup_pasien">
    <h3>Data Pasien</h3>
    <div id="hasil_pasien"></div>

    <!-- 🔥 tombol load more -->
    <button id="load_more" style="display:none;margin-top:10px;">
        Load More
    </button>
</div>
<script>
$(document).ready(function(){

    let page = 0;
    let no_rm_global = '';

    // ======================
    // SEARCH PASIEN
    // ======================
    $("#cari_pasien").keyup(function(){

        let keyword = $(this).val();
        page = 0;

        if(keyword.length >= 3){

            $.ajax({
                url: "cari-pasien-rm.php",
                method: "POST",
                data: {keyword: keyword},
                success: function(response){

                    $("#popup_pasien").show();
                    $("#hasil_pasien").html(response);

                }
            });

        } else {
            $("#popup_pasien").hide();
        }
    });

    // ======================
    // CLICK PASIEN (PENTING)
    // ======================
    $(document).on("click", ".pasien-item", function(){

        let no_rm = $(this).data("rm");

        no_rm_global = no_rm;
        page = 0;

        $.ajax({
            url: "detail-pasien-rm.php",
            method: "POST",
            data: {no_rm: no_rm},
            success: function(res){

                $("#hasil_pasien").html(res);

                loadRiwayat(true);
            }
        });
    });

    // ======================
    // LOAD RIWAYAT
    // ======================
   function loadRiwayat(reset=false){

    if(!no_rm_global) return;

    $.ajax({
        url: "riwayat-scan.php",
        method: "POST",
        data: {
            no_rm: no_rm_global,
            page: page
        },
        success: function(res){

            if(reset){
                $("#hasil_pasien").append("<div id='riwayat'></div>");
                $("#riwayat").html(res);
            } else {
                $("#riwayat").append(res);
            }

            // =========================
            // CEK DATA MASIH ADA ATAU TIDAK
            // =========================
            let masihAda = $("#data_status").data("more");

            if(masihAda == 1){
                $("#load_more").show();
            } else {
                $("#load_more").hide();
            }

        }
    });
}
    // ======================
    // LOAD MORE
    // ======================
    $("#load_more").click(function(){
        page++;
        loadRiwayat();
    });

});
</script>

<!-- BOTTOM NAV -->
<div class="bottom-nav">
   <a href="http://10.10.20.250/dashboard/ROBOT-DASHBOARD/"  class="nav-item"><img src="http://10.10.20.250/dashboard/APPS-ROBOT/ANDROID-NEW/BERANDA2.png" alt="" class="profile-pic" height="70" width="50" ><br> BERANDA </a>

 <a href="http://10.10.20.250/dashboard/APPS-ROBOT/GITHUB/SOAP/ROBOT-V80/rawat_jalan/manage?t=d9d3d5af7281" id="vib1" class="nav-item">
  <img src="http://10.10.20.250/dashboard/APPS-ROBOT/GITHUB/SOAP.png"
       alt=""
       id="goyang"
       class="profile-pic"
       height="70"
       width="50">
  <br> SOAP
</a>

     <a href="http://10.10.20.250/dashboard/APPS-ROBOT/GITHUB/index-dashboard2.php"  class="home-btn"><img src="http://10.10.20.250/dashboard/APPS-ROBOT/ANDROID-NEW/APPS2.png" alt="" class="profile-pic" height="100" width="100" > </a>




<a href="<?= $user['gaji']; ?>" class="nav-item" id="vib1">
  <img src="http://10.10.20.250/dashboard/APPS-ROBOT/ANDROID-NEW/GAJI.png"
       alt=""
       id="goyang"
       class="profile-pic"
       height="70"
       width="50">
  <br> SLIP GAJI
</a>

   


     <a href="http://10.10.20.250/dashboard/APPS-ROBOT/ANDROID-NEW/logout2.php" class="nav-item"><img src="http://10.10.20.250/dashboard/APPS-ROBOT/ANDROID-NEW/LOGOUT.png" alt="" class="profile-pic" height="70" width="50" ><br> LOGOUT</a>
</div>

