<?php
include 'koneksi-scan.php';

$no_rm   = $_POST['no_rm'];
$poli    = $_POST['poli'];
$dokter  = $_POST['dokter'];
$penjamin= $_POST['penjamin'];

if(!$no_rm || !$poli || !$dokter || !$penjamin){
    die("Data tidak lengkap");
}

$tgl = date('Y-m-d');
$jam = date('H:i:s');

/* =========================
   VALIDASI PENJAB (FIX ERROR KAMU)
========================= */
$stmt = $conn->prepare("SELECT kd_pj FROM penjab WHERE kd_pj = ?");
$stmt->bind_param("s", $penjamin);
$stmt->execute();
$cek = $stmt->get_result()->fetch_assoc();

if(!$cek){
    die("Penjamin tidak valid");
}

$kd_pj = $cek['kd_pj'];

/* =========================
   NO REG
========================= */
$q = $conn->prepare("
    SELECT MAX(no_reg) AS maxno 
    FROM reg_periksa 
    WHERE tgl_registrasi=? AND kd_poli=?
");
$q->bind_param("ss", $tgl, $poli);
$q->execute();
$res = $q->get_result()->fetch_assoc();

$no_reg = isset($res['maxno']) ? (int)$res['maxno'] + 1 : 1;
$no_reg = str_pad($no_reg, 3, "0", STR_PAD_LEFT);

/* =========================
   NO RAWAT
========================= */
$no_rawat = date('Y/m/d')."/".$no_reg;

/* =========================
   INSERT REG PERIKSA
========================= */
$stmt = $conn->prepare("
INSERT INTO reg_periksa(
    no_reg,
    no_rawat,
    tgl_registrasi,
    jam_reg,
    kd_dokter,
    no_rkm_medis,
    kd_poli,
    kd_pj,
    status_lanjut,
    stts_daftar
)
VALUES (?,?,?,?,?,?,?,?,?,?)
");

$status_lanjut = "Ralan";
$stts_daftar = "Baru";

$stmt->bind_param(
    "ssssssssss",
    $no_reg,
    $no_rawat,
    $tgl,
    $jam,
    $dokter,
    $no_rm,
    $poli,
    $kd_pj,
    $status_lanjut,
    $stts_daftar
);

$insert = $stmt->execute();

if($insert){
    echo "
    <script>
        alert('Pendaftaran berhasil!');
        window.location='scan.php';
    </script>
    ";
}else{
    echo "Gagal: ".$conn->error;
}
?>
