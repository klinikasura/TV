<?php
include "koneksi2.php";

$id=$_GET['id'];

mysqli_query($koneksi,"
DELETE FROM robotv80_obat_keluar
WHERE id='$id'
");

echo "
<script>
alert('Data berhasil dihapus');
location='cek-so.php';
</script>
";
?>
