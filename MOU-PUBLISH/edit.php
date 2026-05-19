<?php
// koneksi ke database
$conn = mysqli_connect("10.10.20.250", "root", "", "sikdraisyah");

// cek koneksi
if (!$conn) {
  die("Koneksi gagal: " . mysqli_connect_error());
}

// ambil data dari database
$id = $_GET['id'];
$query = "SELECT * FROM robot80_mou WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

// jika ada update
if (isset($_POST['update'])) {
  $nama_pt = $_POST['nama_pt'];
  $alamat_pt = $_POST['alamat_pt'];
  $mulai_mou = $_POST['mulai_mou'];
  $habis_mou = $_POST['habis_mou'];
  $no_mou = $_POST['no_mou'];
  $pj_klinik = $_POST['pj_klinik'];
  $pj_pt = $_POST['pj_pt'];
  $nama_klinik = $_POST['nama_klinik'];

  $query = "UPDATE robot80_mou SET nama_pt = '$nama_pt', alamat_pt = '$alamat_pt', mulai_mou = '$mulai_mou', habis_mou = '$habis_mou', no_mou = '$no_mou', pj_klinik = '$pj_klinik', pj_pt = '$pj_pt', nama_klinik = '$nama_klinik' WHERE id = '$id'";
  mysqli_query($conn, $query);
  header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Data MOU</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h1>Edit Data MOU</h1>
    <form action="" method="post">
      <table>
        <tr>
          <td>Nama PT</td>
          <td><input type="text" name="nama_pt" value="<?php echo $row['nama_pt']; ?>"></td>
        </tr>
        <tr>
          <td>Alamat PT</td>
          <td><textarea name="alamat_pt"><?php echo $row['alamat_pt']; ?></textarea></td>
        </tr>
        <tr>
          <td>Mulai MOU</td>
          <td><input type="date" name="mulai_mou" value="<?php echo $row['mulai_mou']; ?>"></td>
        </tr>
        <tr>
          <td>Habis MOU</td>
          <td><input type="date" name="habis_mou" value="<?php echo $row['habis_mou']; ?>"></td>
        </tr>
        <tr>
          <td>No MOU</td>
          <td><input type="text" name="no_mou" value="<?php echo $row['no_mou']; ?>"></td>
        </tr>
        <tr>
          <td>PJ Klinik</td>
          <td><input type="text" name="pj_klinik" value="<?php echo $row['pj_klinik']; ?>"></td>
        </tr>
        <tr>
          <td>PJ PT</td>
          <td><input type="text" name="pj_pt" value="<?php echo $row['pj_pt']; ?>"></td>
        </tr>
        <tr>
          <td>Nama Klinik</td>
          <td><input type="text" name="nama_klinik" value="<?php echo $row['nama_klinik']; ?>"></td>
        </tr>
        <tr>
          <td></td>
          <td><input type="submit" name="update" value="Update"></td>
        </tr>
      </table>
    </form>
  </div>
</body>
</html>

