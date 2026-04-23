<?php
include 'koneksi-scan.php';

$no_rm = $_GET['no_rm'] ?? '';

if ($no_rm == '') {
    die("No RM tidak ditemukan");
}

/* =========================
   AMBIL DATA PASIEN (AMAN)
========================= */
$stmt = $conn->prepare("
    SELECT 
        no_rkm_medis,
        nm_pasien,
        no_ktp,
        alamat,
        nm_ibu,
        jk,
        tgl_lahir
    FROM pasien
    WHERE no_rkm_medis = ?
");

$stmt->bind_param("s", $no_rm);
$stmt->execute();
$q = $stmt->get_result();
$d = $q->fetch_assoc();

if (!$d) {
    die("Pasien tidak ditemukan");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Daftar Pasien</title>

<style>
body{
    font-family:Segoe UI;
    background:#f2f4f8;
    padding:20px;
}

.box{
    background:#fff;
    padding:20px;
    border-radius:10px;
    max-width:500px;
    margin:auto;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

h2{
    color:#2a7da8;
}

.info{
    background:#eaf6fb;
    padding:10px;
    border-radius:8px;
    margin-bottom:15px;
}

input, select{
    width:100%;
    padding:10px;
    margin-bottom:10px;
    border:1px solid #ccc;
    border-radius:6px;
}

button{
    padding:10px;
    width:100%;
    background:#28a745;
    color:#fff;
    border:none;
    border-radius:6px;
    cursor:pointer;
}
</style>

</head>
<body>

<div class="box">

<h2>Pendaftaran Pasien</h2>

<div class="info">
    <b>RM:</b> <?= htmlspecialchars($d['no_rkm_medis']) ?><br>
    <b>Nama:</b> <?= htmlspecialchars($d['nm_pasien']) ?><br>
    <b>NIK:</b> <?= htmlspecialchars($d['no_ktp']) ?><br>
    <b>Alamat:</b> <?= htmlspecialchars($d['alamat']) ?><br>
</div>

<form method="POST" action="simpan-daftar-scan.php">

    <input type="hidden" name="no_rm" value="<?= htmlspecialchars($d['no_rkm_medis']) ?>">

    <!-- POLIKLINIK DINAMIS -->
    <label>Poliklinik</label>
    <select name="poli" required>
        <option value="">-- Pilih Poli --</option>
        <?php
        $poli = $conn->query("SELECT kd_poli, nm_poli FROM poliklinik");
        while($p = $poli->fetch_assoc()){
            echo "<option value='{$p['kd_poli']}'>".$p['nm_poli']."</option>";
        }
        ?>
    </select>

    <!-- DOKTER DINAMIS -->
    <label>Dokter</label>
    <select name="dokter" required>
        <option value="">-- Pilih Dokter --</option>
        <?php
        $dokter = $conn->query("SELECT kd_dokter, nm_dokter FROM dokter");
        while($dtr = $dokter->fetch_assoc()){
            echo "<option value='{$dtr['kd_dokter']}'>".$dtr['nm_dokter']."</option>";
        }
        ?>
    </select>

    <!-- PENJAMIN -->
    <label>Penjamin</label>
    <select name="penjamin" required>
        <option value="">-- Pilih --</option>
        <option value="UMUM">UMUM</option>
        <option value="BPJS">BPJS</option>
    </select>

    <button type="submit">DAFTARKAN</button>

</form>

</div>

</body>
</html>
