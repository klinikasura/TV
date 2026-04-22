<?php
include 'koneksi-scan.php';

if(isset($_POST['no_rm'])){

$no_rm = $conn->real_escape_string($_POST['no_rm']);

$q = $conn->query("
SELECT pasien.*, penjab.png_jawab
FROM pasien
LEFT JOIN penjab ON pasien.kd_pj = penjab.kd_pj
WHERE pasien.no_rkm_medis='$no_rm'
");

if($d = $q->fetch_assoc()){

function e($str){
    return htmlspecialchars($str ?? '-', ENT_QUOTES, 'UTF-8');
}

echo "
<div id='data_pasien' data-norm='{$d['no_rkm_medis']}'>

<h3>Detail Pasien</h3>

<hr>

<h4>Identitas</h4>
<p><b>No RM:</b> ".e($d['no_rkm_medis'])."</p>
<p><b>Nama:</b> ".e($d['nm_pasien'])."</p>
<p><b>NIK:</b> ".e($d['no_ktp'])."</p>
<p><b>Jenis Kelamin:</b> ".e($d['jk'])."</p>
<p><b>Tempat Lahir:</b> ".e($d['tmp_lahir'])."</p>
<p><b>Tanggal Lahir:</b> ".e($d['tgl_lahir'])."</p>
<p><b>Umur:</b> ".e($d['umur'])."</p>

<hr>

<h4>Kontak</h4>
<p><b>Alamat:</b> ".e($d['alamat'])."</p>
<p><b>No HP:</b> ".e($d['no_tlp'])."</p>
<p><b>Email:</b> ".e($d['email'])."</p>

<hr>

<h4>Sosial</h4>
<p><b>Agama:</b> ".e($d['agama'])."</p>
<p><b>Status Nikah:</b> ".e($d['stts_nikah'])."</p>
<p><b>Pekerjaan:</b> ".e($d['pekerjaan'])."</p>
<p><b>Pendidikan:</b> ".e($d['pnd'])."</p>
<p><b>Gol Darah:</b> ".e($d['gol_darah'])."</p>

<hr>

<h4>Penjamin</h4>
<p><b>No Peserta:</b> ".e($d['no_peserta'])."</p>
<p><b>Penjamin:</b> ".e($d['png_jawab'])."</p>

<hr>

<h4>Penanggung Jawab</h4>
<p><b>Nama:</b> ".e($d['namakeluarga'])."</p>
<p><b>Hubungan:</b> ".e($d['keluarga'])."</p>
<p><b>Pekerjaan:</b> ".e($d['pekerjaanpj'])."</p>
<p><b>Alamat:</b> ".e($d['alamatpj'])."</p>

</div>
";

}
}
?>
