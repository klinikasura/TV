<!DOCTYPE html>
<html>
<head>
  <title>Data Registrasi Pasien</title>
  <meta http-equiv="refresh" content="2;url=pasien4.php">
  <style>
    table {border-collapse:collapse;width:100%}
    th,td {border:1px solid #ddd;padding:8px;text-align:left}
    th {background:#f0f0f0}
  </style>
</head>
<body>

<?php
// Koneksi
$servername = "10.10.20.250";
$username   = "root";
$password   = "";
$dbname     = "sikdraisyah";
$conn = new mysqli($servername,$username,$password,$dbname);
if($conn->connect_error) die("Koneksi gagal: ".$conn->connect_error);

// Pagination
$per_halaman = 4;
$halaman    = isset($_GET['halaman'])?$_GET['halaman']:1;
$offset     = ($halaman-1)*$per_halaman;

$tgl_hari_ini = date('Y-m-d');

// Query utama (tambah JOIN ke penjab)
$sql = "
  SELECT rp.no_reg, rp.no_rawat, rp.tgl_registrasi,
         p.nm_pasien, d.nm_dokter, pl.nm_poli,
         pj.png_jawab,               -- <-- ambil png_jawab
         rp.status_bayar, rp.status_lanjut
  FROM reg_periksa rp
  INNER JOIN pasien    p  ON rp.no_rkm_medis = p.no_rkm_medis
  INNER JOIN dokter    d  ON rp.kd_dokter    = d.kd_dokter
  INNER JOIN poliklinik pl ON rp.kd_poli     = pl.kd_poli
  INNER JOIN penjab       pj ON rp.kd_pj     = pj.kd_pj   -- <-- JOIN penjab
  WHERE DATE(rp.tgl_registrasi) = '$tgl_hari_ini'
    AND rp.status_bayar = 'Belum Bayar'
    AND rp.status_lanjut = 'Ralan'
  LIMIT $offset,$per_halaman";

$result = $conn->query($sql);

if($result->num_rows>0){
  echo "<table>";
  echo "<tr>";
  echo "<th>No.</th>";
  echo "<th>Tanggal</th>";
  echo "<th>Pasien</th>";
  echo "<th>Dokter</th>";
  echo "<th>Poliklinik</th>";
  echo "<th>Bayar</th>";   // <-- judul kolom baru
  echo "<th>Status</th>";
  echo "<th>Lanjut</th>";
  echo "</tr>";

  while($row=$result->fetch_assoc()){
    echo "<tr>";
    echo "<td>".$row["no_reg"]."</td>";
    echo "<td>".$row["tgl_registrasi"]."</td>";
    echo "<td>".$row["nm_pasien"]."</td>";
    echo "<td>".$row["nm_dokter"]."</td>";
    echo "<td>".$row["nm_poli"]."</td>";
    echo "<td>".$row["png_jawab"]."</td>";   // <-- tampilkan png_jawab
    echo "<td>".$row["status_bayar"]."</td>";
    echo "<td>".$row["status_lanjut"]."</td>";
    echo "</tr>";
  }
  echo "</table>";

  // Jumlah data
  $sql_jml = "
    SELECT COUNT(*) as jml
    FROM reg_periksa rp
    INNER JOIN pasien    p  ON rp.no_rkm_medis = p.no_rkm_medis
    INNER JOIN dokter    d  ON rp.kd_dokter    = d.kd_dokter
    INNER JOIN poliklinik pl ON rp.kd_poli     = pl.kd_poli
    INNER JOIN penjab       pj ON rp.kd_pj     = pj.kd_pj
    WHERE DATE(rp.tgl_registrasi) = '$tgl_hari_ini'
      AND rp.status_bayar = 'Belum Bayar'
      AND rp.status_lanjut = 'Ralan'";
  $jml = $conn->query($sql_jml)->fetch_assoc()['jml'];
  echo "Jumlah Pasien Belum Bayar: $jml";

  // Paging
  $jml_halaman = ceil($jml/$per_halaman);
  echo "<div>";
  for($i=1;$i<=$jml_halaman;$i++){
    if($i==$halaman) echo "<a href='?halaman=$i' style='font-weight:bold;'>$i</a> ";
    else             echo "<a href='?halaman=$i'>$i</a> ";
  }
  echo "</div>";
}else{
  echo "Hore.... Pasien Hari Ini Sudah Bayar Semua";
}

$conn->close();
?>

</body>
</html>

