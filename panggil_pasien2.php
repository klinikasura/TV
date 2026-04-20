<!DOCTYPE html>
<html>
<head>
 <title>myROBOT-V80</title>
  <link href="http://10.10.20.250/dashboard/APPS-ROBOT/BUILDING APLIKASI/@API-GITHUB-V80/ROBOT-GITHUB/ROBOTV80.png" rel="icon" type="image/png" />
    <meta http-equiv="refresh" content="240;url=panggil_pasien2.php">

<button id="fullScreenBtn">Full Layar</button>
 <style>
body {
    margin:0;
    font-family: 'Segoe UI', Tahoma, sans-serif;
    background: linear-gradient(135deg, #e0f7ff, #f0f9ff);
}
/* SEARCH BAR */
.search {
    margin: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.search input {
    padding: 8px;
    border-radius: 6px;
    border: 1px solid #93c5fd;
}

/* BUTTON */
.btn {
    background: linear-gradient(135deg, #60a5fa, #3b82f6);
    color: #fff;
    padding: 8px 15px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    text-decoration: none;
    font-weight: 600;
}

/* TABEL */
table {
    border-collapse: collapse;
    width: 98%;
    margin: 20px auto;
    background: #fafdff;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

/* HEADER */
th {
    background: linear-gradient(135deg, #38bdf8, #60a5fa);
    color: #ffffff;
    padding: 12px;
    text-align: center;
    font-size: 17px;
    letter-spacing: 0.5px;
}

/* ISI */
td {
    border: 1px solid #cfe8ff;
    padding: 10px;
    text-align: center;
    font-size: 16px;
    color: #1e3a8a;
    font-weight: 500;
}

/* ZEBRA */
tr:nth-child(even) {
    background: #f2f8ff;
}

/* HOVER */
tr:hover {
    background: #dbeafe;
    transform: scale(1.01);
}

/* KOLOM KHUSUS */
td:nth-child(3) { /* nama pasien */
    text-align: left;
    font-weight: 600;
    color: #1d4ed8;
}

td:nth-child(4) { /* dokter */
    color: #2563eb;
    font-weight: 600;
}

/* STATUS BELUM BAYAR */
td:nth-child(7) {
    color: #dc2626;
    font-weight: bold;
}

/* STATUS LANJUT */
td:nth-child(8) {
    color: #16a34a;
    font-weight: bold;
}

/* INFO TOTAL */
.jumlah {
    text-align: center;
    font-size: 18px;
    margin-top: 10px;
    color: #1e40af;
    font-weight: bold;
}

/* PAGING */
.paging {
    text-align: center;
    margin-top: 10px;
}

.paging a {
    display: inline-block;
    padding: 6px 12px;
    margin: 2px;
    background: #bfdbfe;
    color: #1e3a8a;
    border-radius: 6px;
    text-decoration: none;
    font-weight: bold;
}

.paging a:hover {
    background: #60a5fa;
    color: white;
}
</style>
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
    top:12px; 
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

#fullScreenBtn { left:440px; }
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
</head>
<body>
    <?php
    // Koneksi
    $servername = "10.10.20.250";
    $username = "root";
    $password = "";
    $dbname = "sikdraisyah";
    $conn = new mysqli($servername,$username,$password,$dbname);
    if ($conn->connect_error) die("Koneksi gagal: ".$conn->connect_error);

    // Pagination
    $per_halaman = 20;
    $halaman = isset($_GET['halaman']) ? $_GET['halaman'] : 1;
    $offset = ($halaman - 1) * $per_halaman;
    $tgl_hari_ini = date('Y-m-d');

    // Filter pencarian (jika ada)
    $filter = "";
    if (isset($_GET['cari']) && !empty($_GET['cari'])) {
        $cari = $conn->real_escape_string($_GET['cari']);
        $filter = " AND p.nm_pasien LIKE '%$cari%' ";
    }

    // Query utama (tambah JOIN ke penjab + filter)
    $sql = "
     SELECT rp.no_reg, rp.no_rawat, rp.tgl_registrasi, p.nm_pasien, d.nm_dokter, pl.nm_poli, pj.png_jawab, rp.status_bayar, rp.status_lanjut
     FROM reg_periksa rp
     INNER JOIN pasien p ON rp.no_rkm_medis = p.no_rkm_medis
     INNER JOIN dokter d ON rp.kd_dokter = d.kd_dokter
     INNER JOIN poliklinik pl ON rp.kd_poli = pl.kd_poli
     INNER JOIN penjab pj ON rp.kd_pj = pj.kd_pj
     WHERE DATE(rp.tgl_registrasi) = '$tgl_hari_ini' AND rp.status_bayar = 'Belum Bayar' AND rp.status_lanjut = 'Ralan' $filter
     LIMIT $offset,$per_halaman
     ";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Form pencarian
        echo "<div class='search'>";
        echo "<label>Cari Pasien:</label>";
        echo "<form method='get' style='display:inline;'>";
        echo "<input type='text' name='cari' placeholder='Ketik nama...' value='".(isset($_GET['cari'])?$_GET['cari']:"")."'>";
        echo "<button type='submit' class='btn'> Cari </button>";
        echo "<a href='?halaman=1' class='btn'> Reset </a>";
              echo "</form>";
        echo "</div>";

        echo "<table>";
        echo "<tr>";
        echo "<th>No.</th>";
        echo "<th>Tanggal</th>";
        echo "<th>Pasien</th>";
        echo "<th>Dokter</th>";
        echo "<th>Poliklinik</th>";
        echo "<th>Bayar</th>";
        echo "<th>Status</th>";
        echo "<th>Lanjut</th>";
        echo "<th>Speaker</th>";
        echo "</tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>".$row["no_reg"]."</td>";
            echo "<td>".$row["tgl_registrasi"]."</td>";
            echo "<td>".$row["nm_pasien"]."</td>";
            echo "<td>".$row["nm_dokter"]."</td>";
            echo "<td>".$row["nm_poli"]."</td>";
            echo "<td>".$row["png_jawab"]."</td>";
            echo "<td>".$row["status_bayar"]."</td>";
            echo "<td>".$row["status_lanjut"]."</td>";
            echo "<td> <span class='speaker' data-no-reg='".htmlspecialchars($row['no_reg'])."' data-pasien='".htmlspecialchars($row['nm_pasien'])."' data-poli='".htmlspecialchars($row['nm_poli'])."' data-dokter='".htmlspecialchars($row['nm_dokter'])."'> &#128266; </span> </td>";
            echo "</tr>";
        }
        echo "</table>";

        // Jumlah data (dengan filter)
        $sql_jml = "
         SELECT COUNT(*) as jml
         FROM reg_periksa rp
         INNER JOIN pasien p ON rp.no_rkm_medis = p.no_rkm_medis
         INNER JOIN dokter d ON rp.kd_dokter = d.kd_dokter
         INNER JOIN poliklinik pl ON rp.kd_poli = pl.kd_poli
         INNER JOIN penjab pj ON rp.kd_pj = pj.kd_pj
         WHERE DATE(rp.tgl_registrasi) = '$tgl_hari_ini' AND rp.status_bayar = 'Belum Bayar' AND rp.status_lanjut = 'Ralan' $filter
         ";
        $jml = $conn->query($sql_jml)->fetch_assoc()['jml'];
    echo "<div style='margin-left: 20px;'>Jumlah Pasien Belum Bayar: $jml</div>";

        // Paging (tambah parameter pencarian agar paging tetap filter)
        $jml_halaman = ceil($jml / $per_halaman);
       echo "<div class='paging'>";
for($i=1;$i<=$jml_halaman;$i++){
  if($i==$halaman) 
    echo "<a href='?halaman=$i' class='aktif'>$i</a> ";
  else             
    echo "<a href='?halaman=$i'>$i</a> ";
}
echo "</div>";
    } else {
        echo "Hore.... Pasien Hari Ini Sudah Bayar Semua";
    }
    ?>

    <script>
        let soundReady = true; // langsung aktifkan suara
        function playVoice(text) {
            if(!soundReady) return;
            if ('speechSynthesis' in window) {
                var msg = new SpeechSynthesisUtterance();
                msg.text = text;
                msg.lang = 'id-ID';
                window.speechSynthesis.speak(msg);
            } else {
                alert('Browser tidak mendukung speech synthesis.');
            }
        }

        document.addEventListener('click', function(e){
            if (e.target && e.target.className === 'speaker') {
                var noReg = e.target.getAttribute('data-no-reg');
                var nama = e.target.getAttribute('data-pasien');
                var poli = e.target.getAttribute('data-poli');
                var dokter = e.target.getAttribute('data-dokter');
                var teks = 'Nomor Antrian ' + noReg + ', Panggilan Untuk Pasien ' + nama + ' di ' + poli + ' dengan ' + dokter + ' .';
                playVoice(teks);
            }
        });
    </script>
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

</body>
</html>

