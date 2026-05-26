<?php

$conn = mysqli_connect("10.10.20.250", "root", "", "sikdraisyah");

$dari = $_GET['dari'];
$sampai = $_GET['sampai'];

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=REKAP-EXPIRED-OBAT.xls");

?>

<h2>REKAP DATA OBAT EXPIRED</h2>

<p>
Tanggal :
<?php echo $dari; ?>
s/d
<?php echo $sampai; ?>
</p>

<table border="1" cellpadding="8">

<tr>

<th>No</th>
<th>Kode</th>
<th>Nama Obat</th>
<th>Expired</th>
<th>Asal Stok</th>
<th>Jumlah</th>

</tr>

<?php

$sql = "

SELECT

d.kode_brng,
d.nama_brng,
d.expire,

gb.stok,
b.nm_bangsal AS asal_stok

FROM databarang d

LEFT JOIN gudangbarang gb
ON d.kode_brng = gb.kode_brng

LEFT JOIN bangsal b
ON gb.kd_bangsal = b.kd_bangsal

WHERE

d.status='1'

AND d.expire != '0000-00-00'
AND d.expire != ''
AND d.expire IS NOT NULL

AND d.expire BETWEEN '$dari' AND '$sampai'

ORDER BY d.expire ASC

";

$query = mysqli_query($conn,$sql);

$no = 1;

while($r = mysqli_fetch_assoc($query)){

echo "

<tr>

<td>".$no++."</td>

<td>".$r['kode_brng']."</td>

<td>".$r['nama_brng']."</td>

<td>".$r['expire']."</td>

<td>".$r['asal_stok']."</td>

<td>".$r['stok']."</td>

</tr>

";

}

?>

</table>
