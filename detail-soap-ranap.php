<?php
$koneksi = mysqli_connect(
    "10.10.20.250",
    "root",
    "",
    "sikdraisyah"
);

$no_rawat = mysqli_real_escape_string(
    $koneksi,
    $_GET['no_rawat']
);

$query = mysqli_query($koneksi,"
SELECT
    pemeriksaan_ranap.*,
    pasien.nm_pasien,
    pasien.no_rkm_medis,
    dokter.nm_dokter,
    poliklinik.nm_poli
FROM pemeriksaan_ranap
LEFT JOIN reg_periksa
ON reg_periksa.no_rawat=pemeriksaan_ranap.no_rawat
LEFT JOIN pasien
ON pasien.no_rkm_medis=reg_periksa.no_rkm_medis
LEFT JOIN dokter
ON dokter.kd_dokter=reg_periksa.kd_dokter
LEFT JOIN poliklinik
ON poliklinik.kd_poli=reg_periksa.kd_poli
WHERE pemeriksaan_ranap.no_rawat='$no_rawat'
LIMIT 1
");

$data = mysqli_fetch_assoc($query);
?>

<h2>SOAP Pasien</h2>

<button onclick="window.print()" class="btn-print">
    🖨 Cetak SOAP
</button>

<style>
<style>
.btn-print{
    background:#16a34a;
    color:white;
    border:none;
    padding:10px 20px;
    border-radius:8px;
    cursor:pointer;
    font-size:14px;
    margin-bottom:15px;
}

.btn-print:hover{
    background:#15803d;
}

@media print{
    .btn-print{
        display:none;
    }
}
</style>
</style>

<table border="1" cellpadding="10">

<tr>
    <td>Nama Pasien</td>
    <td><?= $data['nm_pasien'] ?></td>
</tr>

<tr>
    <td>No RM</td>
    <td><?= $data['no_rkm_medis'] ?></td>
</tr>

<tr>
    <td>Dokter</td>
    <td><?= $data['nm_dokter'] ?></td>
</tr>

<tr>
    <td>Poli</td>
    <td><?= $data['nm_poli'] ?></td>
</tr>

<tr>
    <td>Suhu</td>
    <td><?= $data['suhu_tubuh'] ?></td>
</tr>

<tr>
    <td>Tensi</td>
    <td><?= $data['tensi'] ?></td>
</tr>

<tr>
    <td>Nadi</td>
    <td><?= $data['nadi'] ?></td>
</tr>

<tr>
    <td>Respirasi</td>
    <td><?= $data['respirasi'] ?></td>
</tr>

<tr>
    <td>Tinggi</td>
    <td><?= $data['tinggi'] ?></td>
</tr>

<tr>
    <td>Berat</td>
    <td><?= $data['berat'] ?></td>
</tr>

<tr>
    <td>SPO2</td>
    <td><?= $data['spo2'] ?></td>
</tr>

<tr>
    <td>GCS</td>
    <td><?= $data['gcs'] ?></td>
</tr>

<tr>
    <td>Kesadaran</td>
    <td><?= $data['kesadaran'] ?></td>
</tr>

<tr>
    <td>Alergi</td>
    <td><?= $data['alergi'] ?></td>
</tr>

<tr>
    <td>Keluhan</td>
    <td><?= nl2br($data['keluhan']) ?></td>
</tr>

<tr>
    <td>Pemeriksaan</td>
    <td><?= nl2br($data['pemeriksaan']) ?></td>
</tr>

<tr>
    <td>Penilaian</td>
    <td><?= nl2br($data['penilaian']) ?></td>
</tr>

<tr>
    <td>RTL</td>
    <td><?= nl2br($data['rtl']) ?></td>
</tr>

<tr>
    <td>Instruksi</td>
    <td><?= nl2br($data['instruksi']) ?></td>
</tr>

<tr>
    <td>Evaluasi</td>
    <td><?= nl2br($data['evaluasi']) ?></td>
</tr>

</table>



<script>
window.onload = function(){
    window.print();
}
</script>
