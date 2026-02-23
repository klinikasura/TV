<?php
// Koneksi ke database
$host = "localhost";
$user = "root";
$password = "";
$database = "sikdraisyah";

$conn = new mysqli($host, $user, $password, $database);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query SQL
$tgl_hari_ini = date('Y-m-d');
$query = "
    SELECT pl.nm_poli, COUNT(rp.no_reg) as jumlah_pasien
    FROM reg_periksa rp
    INNER JOIN poliklinik pl ON rp.kd_poli = pl.kd_poli
    WHERE DATE(rp.tgl_registrasi) = '$tgl_hari_ini'
    AND rp.status_bayar = 'Belum Bayar'
    AND rp.status_lanjut = 'Ralan'
    GROUP BY pl.nm_poli;
";

// Eksekusi query
$result = $conn->query($query);

// Buat array untuk nm_poli dan jumlah_pasien
$nm_poli = array();
$jumlah_pasien = array();

while ($row = $result->fetch_assoc()) {
    $nm_poli[] = $row['nm_poli'];
    $jumlah_pasien[] = $row['jumlah_pasien'];
}

$conn->close();
?>


<?php
date_default_timezone_set('Asia/Jakarta');
$waktu = date('d M Y H:i:s');
?>

<!DOCTYPE html>
<html>
<head>
  <title>Aplikasi RS. Asura</title>
  <link href="http://10.10.20.250/dashboard/download.jpeg" rel="icon" type="image/png" />
  <meta http-equiv="refresh" content="2;url=grafik2.php">




    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h2>Grafik Pasien Belum Bayar - <?php echo $waktu; ?></h2>
    <canvas id="myChart"></canvas>

    <script>
        var ctx = document.getElementById("myChart").getContext("2d");
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($nm_poli); ?>,
                datasets: [{
                    label: 'Jumlah Pasien',
                    data: <?php echo json_encode($jumlah_pasien); ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script>
</body>
</html>

