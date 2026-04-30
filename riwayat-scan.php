<?php
include 'koneksi-scan.php';

if(isset($_POST['no_rm'])){

$no_rm = $conn->real_escape_string($_POST['no_rm']);

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

// ================= DETAIL TERAKHIR =================
$detail = $conn->query("
SELECT 
    ki.tgl_masuk,
    ki.jam_masuk,
    ki.tgl_keluar,
    ki.jam_keluar,
    ki.diagnosa_awal,
    ki.diagnosa_akhir,
    ki.lama,
    ki.ttl_biaya,
    ki.stts_pulang,
    k.kd_kamar,
    k.kelas,
    b.nm_bangsal
FROM kamar_inap ki
JOIN kamar k ON ki.kd_kamar = k.kd_kamar
JOIN bangsal b ON k.kd_bangsal = b.kd_bangsal
JOIN reg_periksa rp ON ki.no_rawat = rp.no_rawat
WHERE rp.no_rkm_medis = '$no_rm'
ORDER BY ki.tgl_masuk DESC, ki.jam_masuk DESC
LIMIT 1
")->fetch_assoc();


// ================= CEK DATA RAWAT INAP =================
if(!$detail){

echo "
<div style='
    margin:10px 0;
    padding:14px;
    background:#fff;
    border-radius:12px;
    border-left:6px solid #95a5a6;
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
'>

    <b style='color:#2c3e50;font-size:14px;'>🏥 RAWAT INAP</b><br><br>

    <span style='color:#7f8c8d;'>
        📭 Belum ada riwayat rawat inap
    </span>

</div>
";

} else {

// ================= FORMAT =================
$tgl_masuk = $detail['tgl_masuk'] 
    ? date('d-m-Y', strtotime($detail['tgl_masuk'])) . " " . substr($detail['jam_masuk'],0,5)
    : '-';

if(!empty($detail['tgl_keluar'])){
    $tgl_keluar = date('d-m-Y', strtotime($detail['tgl_keluar'])) . " " . substr($detail['jam_keluar'],0,5);
} else {
    $tgl_keluar = "⏳ Belum Pulang";
}

$diagnosa_awal  = $detail['diagnosa_awal'] ?: '-';
$diagnosa_akhir = $detail['diagnosa_akhir'] ?: '-';

$kamar = $detail['nm_bangsal']." | Kelas ".$detail['kelas']." | Kamar ".$detail['kd_kamar'];

// ================= STATUS =================
if($detail['stts_pulang'] == '-' || empty($detail['tgl_keluar'])){
    $status = "🟥 Masih Dirawat";
    $bg = "#e74c3c";
} else {
    $status = "🟩 Sudah Pulang";
    $bg = "#27ae60";
}

// ================= LAMA =================
if(!empty($detail['lama'])){
    $lama = $detail['lama']." hari";
} else {
    $masuk = new DateTime($detail['tgl_masuk']);
    $keluar = !empty($detail['tgl_keluar']) ? new DateTime($detail['tgl_keluar']) : new DateTime();
    $lama = $masuk->diff($keluar)->days." hari";
}

// ================= BIAYA =================
$bill_inap = $conn->query("
SELECT SUM(totalbiaya) as total 
FROM billing 
WHERE no_rawat = (
    SELECT ki.no_rawat 
    FROM kamar_inap ki
    JOIN reg_periksa rp ON ki.no_rawat = rp.no_rawat
    WHERE rp.no_rkm_medis = '$no_rm'
    ORDER BY ki.tgl_masuk DESC, ki.jam_masuk DESC
    LIMIT 1
)
")->fetch_assoc();

$biaya = number_format($bill_inap['total'] ?? 0);


// ================= OUTPUT =================
echo "
<div style='
    margin:10px 0;
    padding:14px;
    background:#fff;
    border-radius:12px;
    border-left:6px solid $bg;
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
'>

    <b style='color:#2c3e50;font-size:14px;'>🏥 RAWAT INAP TERAKHIR</b><br><br>

    <b>📅 Masuk:</b> $tgl_masuk<br>
    <b>📅 Keluar:</b> $tgl_keluar<br>

    <b>🛏 Kamar:</b> $kamar<br>

    <b>🧾 Diagnosa Awal:</b> $diagnosa_awal<br>
    <b>🧾 Diagnosa Akhir:</b> $diagnosa_akhir<br>

    <b>📊 Lama Dirawat:</b> $lama<br>
    <b>💰 Total Biaya:</b> Rp $biaya<br><br>

    <span style='
        padding:6px 12px;
        border-radius:20px;
        background:$bg;
        color:#fff;
        font-size:12px;
        font-weight:bold;
    '>
        $status
    </span>

</div>
";

} // penutup else rawat inap



echo "
<div style='
    margin:20px 0;
    text-align:center;
    font-weight:bold;
    color:#fff;
    background:#2c3e50;
    padding:10px;
    border-radius:8px;
    font-size:14px;
'>
    ===== RIWAYAT RAWAT JALAN =====
</div>
";











$last_tanggal = "";

while($d = $q->fetch_assoc()){

// ================= PEMISAH TANGGAL =================
$tanggal = date('Y-m-d', strtotime($d['tgl_registrasi']));

if($tanggal != $last_tanggal){
    echo "<div style='margin:15px 0;padding:8px;background:#2c3e50;color:#fff;text-align:center;border-radius:6px;font-weight:bold'>
    📅 ".date('d-m-Y', strtotime($d['tgl_registrasi']))."
    </div>";
    $last_tanggal = $tanggal;
}

echo "<div style='margin-bottom:15px;padding:12px;background:#f1faff;border-radius:10px'>";

echo "<b>No Rawat:</b> $d[no_rawat]<br>";
echo "<b>Poli:</b> $d[nm_poli]<br>";
echo "<b>Dokter:</b> $d[nm_dokter]<br>";
echo "<b>Pembayaran:</b> $d[png_jawab]<br>";

$no_rawat = $d['no_rawat'];
/* ================= DIAGNOSA ================= */
$dx = $conn->query("
SELECT p.nm_penyakit 
FROM diagnosa_pasien dp
JOIN penyakit p ON dp.kd_penyakit = p.kd_penyakit
WHERE dp.no_rawat='$no_rawat'
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
WHERE dpo.no_rawat='$no_rawat'
");

echo "<b>Obat:</b><ul>";
while($o = $obat->fetch_assoc()){
    echo "<li>$o[nama_brng] ($o[jml])</li>";
}
echo "</ul>";

/* ================= TINDAKAN (ICON + BADGE) ================= */
$tindakan = $conn->query("
SELECT jp.nm_perawatan AS nama
FROM rawat_jl_dr rjd
JOIN jns_perawatan jp ON rjd.kd_jenis_prw = jp.kd_jenis_prw
WHERE rjd.no_rawat='$no_rawat'

UNION ALL

SELECT jp.nm_perawatan AS nama
FROM rawat_jl_pr rjp
JOIN jns_perawatan jp ON rjp.kd_jenis_prw = jp.kd_jenis_prw
WHERE rjp.no_rawat='$no_rawat'

UNION ALL

SELECT jp.nm_perawatan AS nama
FROM rawat_jl_drpr rjdp
JOIN jns_perawatan jp ON rjdp.kd_jenis_prw = jp.kd_jenis_prw
WHERE rjdp.no_rawat='$no_rawat'
");

echo "<b>Tindakan:</b><br>";

if($tindakan && $tindakan->num_rows > 0){

    while($t = $tindakan->fetch_assoc()){

        $nama = strtolower($t['nama']);
        $icon = "🩺";
        $color = "#7f8c8d";
        $label = $t['nama'];

        if(strpos($nama, 'suntik') !== false || strpos($nama, 'injek') !== false){
            $icon = "💉";
            $color = "#e74c3c";
            $label = "Suntik";
        } 
        elseif(strpos($nama, 'infus') !== false){
            $icon = "💧";
            $color = "#3498db";
            $label = "Infus";
        } 
        elseif(strpos($nama, 'nebul') !== false){
            $icon = "🌫";
            $color = "#9b59b6";
            $label = "Nebulizer";
        } 
        elseif(strpos($nama, 'rawat luka') !== false){
            $icon = "🩹";
            $color = "#f39c12";
            $label = "Perawatan Luka";
        }

        echo "
        <span style='
            display:inline-block;
            padding:4px 10px;
            margin:2px;
            border-radius:20px;
            background:$color;
            color:#fff;
            font-size:12px;
        '>
        $icon $label
        </span>";
    }

} else {
    echo "<i>-</i>";
}

echo "<br>";
/* ================= LAB ================= */
$lab = $conn->query("
SELECT tl.Pemeriksaan, dpl.nilai
FROM detail_periksa_lab dpl
JOIN template_laboratorium tl ON dpl.id_template = tl.id_template
WHERE dpl.no_rawat='$no_rawat'
");

echo "<b>Lab:</b><ul>";
while($l = $lab->fetch_assoc()){
    echo "<li>$l[Pemeriksaan] : $l[nilai]</li>";
}
echo "</ul>";

/* ================= SOAP ================= */
$soap = $conn->query("
SELECT * FROM pemeriksaan_ralan 
WHERE no_rawat = '$no_rawat'
ORDER BY tgl_perawatan DESC, jam_rawat DESC
LIMIT 1
");

if($s = $soap->fetch_assoc()){

echo "<div style='margin-top:10px;padding:8px;background:#fff3e0;border-radius:6px'>";
echo "<b>SOAP:</b><br>";

echo "<b>S:</b> ".($s['keluhan'] ?: '-')."<br><br>";
echo "<b>O:</b> ".($s['pemeriksaan'] ?: '-')."<br>";

echo "<small>
Tensi: $s[tensi] | Nadi: $s[nadi] | Suhu: $s[suhu_tubuh] |
Respirasi: $s[respirasi] | SpO2: $s[spo2]
</small><br><br>";

echo "<b>A:</b> ".($s['penilaian'] ?: '-')."<br><br>";
echo "<b>P:</b> ".($s['rtl'] ?: '-')."<br>";
echo "<b>Instruksi:</b> ".($s['instruksi'] ?: '-')."<br><br>";
echo "<b>Evaluasi:</b> ".($s['evaluasi'] ?: '-');

echo "</div>";
}



/* ================= TOTAL BIAYA ================= */
$bill = $conn->query("
SELECT SUM(totalbiaya) as total 
FROM billing 
WHERE no_rawat='$no_rawat'
")->fetch_assoc();

$total = $bill['total'] ?? 0;

echo "<b>Total Biaya:</b> Rp ".number_format($total)."<br>";
echo "<b>Status Bayar:</b> $d[status_bayar]";
echo "</div>";
}

}
?>
