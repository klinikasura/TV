<?php
include 'koneksi-scan.php';

if(isset($_POST['no_rm'])){

$no_rm = $_POST['no_rm'];

$q = $conn->query("
SELECT rp.no_rawat, rp.tgl_registrasi, rp.status_lanjut,
       rp.status_bayar,
       d.nm_dokter, pl.nm_poli, pj.png_jawab
FROM reg_periksa rp
JOIN dokter d ON rp.kd_dokter = d.kd_dokter
JOIN poliklinik pl ON rp.kd_poli = pl.kd_poli
JOIN penjab pj ON rp.kd_pj = pj.kd_pj
WHERE rp.no_rkm_medis = '$no_rm'
AND rp.stts != 'Batal'
ORDER BY rp.tgl_registrasi DESC
LIMIT 1000
");

echo "<hr><h3>Riwayat Berobat</h3>";

while($d = $q->fetch_assoc()){

echo "<div style='margin-bottom:15px;padding:10px;background:#f1faff;border-radius:8px'>";

echo "<b>Tanggal:</b> $d[tgl_registrasi]<br>";
echo "<b>No Rawat:</b> $d[no_rawat]<br>";
echo "<b>Poli:</b> $d[nm_poli]<br>";
echo "<b>Dokter:</b> $d[nm_dokter]<br>";
echo "<b>Pembayaran:</b> $d[png_jawab]<br>";

/* ================= DIAGNOSA ================= */
$dx = $conn->query("
SELECT p.nm_penyakit 
FROM diagnosa_pasien dp
JOIN penyakit p ON dp.kd_penyakit = p.kd_penyakit
WHERE dp.no_rawat='$d[no_rawat]'
");

echo "<b>Diagnosa:</b><ul>";
while($r = $dx->fetch_assoc()){
    echo "<li>$r[nm_penyakit]</li>";
}
echo "</ul>";

/* ================= OBAT ================= */
$obat = $conn->query("
SELECT db.nama_brng, dpo.jml
FROM detail_pemberian_obat dpo
JOIN databarang db ON dpo.kode_brng = db.kode_brng
WHERE dpo.no_rawat='$d[no_rawat]'
");

echo "<b>Obat:</b><ul>";
while($o = $obat->fetch_assoc()){
    echo "<li>$o[nama_brng] ($o[jml])</li>";
}
echo "</ul>";

/* ================= LAB ================= */
$lab = $conn->query("
SELECT tl.Pemeriksaan, dpl.nilai
FROM detail_periksa_lab dpl
JOIN template_laboratorium tl ON dpl.id_template = tl.id_template
WHERE dpl.no_rawat='$d[no_rawat]'
");

echo "<b>Lab:</b><ul>";
while($l = $lab->fetch_assoc()){
    echo "<li>$l[Pemeriksaan] : $l[nilai]</li>";
}
echo "</ul>";

/* =========================
   SOAP (PEMERIKSAAN RALAN)
========================= */
$soap = $conn->query("
SELECT * FROM pemeriksaan_ralan 
WHERE no_rawat = '$d[no_rawat]'
ORDER BY tgl_perawatan DESC, jam_rawat DESC
LIMIT 1
");

if($s = $soap->fetch_assoc()){

    echo "<div style='margin-top:10px;padding:8px;background:#fff3e0;border-radius:6px'>";
    echo "<b>SOAP:</b><br>";

    // SUBJECTIVE
    echo "<b>S (Keluhan):</b><br>";
    echo $s['keluhan'] ? $s['keluhan'] : "-";
    echo "<br><br>";

    // OBJECTIVE
    echo "<b>O (Pemeriksaan):</b><br>";
    echo $s['pemeriksaan'] ? $s['pemeriksaan'] : "-";
    echo "<br>";

    echo "<small>";
    echo "Tensi: $s[tensi] | ";
    echo "Nadi: $s[nadi] | ";
    echo "Suhu: $s[suhu_tubuh] | ";
    echo "Respirasi: $s[respirasi] | ";
    echo "SpO2: $s[spo2]";
    echo "</small><br><br>";

    // ASSESSMENT
    echo "<b>A (Penilaian):</b><br>";
    echo $s['penilaian'] ? $s['penilaian'] : "-";
    echo "<br><br>";

    // PLAN
    echo "<b>P (Rencana):</b><br>";
    echo $s['rtl'] ? $s['rtl'] : "-";
    echo "<br>";

    echo "<b>Instruksi:</b><br>";
    echo $s['instruksi'] ? $s['instruksi'] : "-";
    echo "<br><br>";

    // EVALUASI
    echo "<b>Evaluasi:</b><br>";
    echo $s['evaluasi'] ? $s['evaluasi'] : "-";

    echo "</div>";
}

/* ================= TOTAL BIAYA ================= */
$bill = $conn->query("
SELECT SUM(totalbiaya) as total 
FROM billing 
WHERE no_rawat='$d[no_rawat]'
")->fetch_assoc();

$total = $bill['total'] ?? 0;

echo "<b>Total Biaya:</b> Rp ".number_format($total)."<br>";

/* ================= STATUS BAYAR (DARI DB) ================= */
echo "<b>Status Bayar:</b> $d[status_bayar]";

echo "</div>";
}

}
?>
