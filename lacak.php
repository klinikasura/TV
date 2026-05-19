<?php
include 'koneksi.php';

// ambil parameter filter
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date   = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// pagination
$limit = 10;
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// status tampil data
$showData = false;
$where = "WHERE 1=1";

// filter tanggal
if ($start_date != '' && $end_date != '') {
    $showData = true;
    $where .= " AND tgl_jurnal BETWEEN '$start_date' AND '$end_date'";
}

// init default
$totalRow = 0;
$totalPage = 0;
$query = null;

// kalau sudah filter baru ambil data
if ($showData) {

    // hitung total data
    $totalData = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM jurnal $where");
    $totalRow = mysqli_fetch_assoc($totalData)['total'];
    $totalPage = ceil($totalRow / $limit);

    // ambil data
    $query = mysqli_query($koneksi, "
        SELECT * FROM jurnal 
        $where 
        ORDER BY tgl_jurnal DESC 
        LIMIT $limit OFFSET $offset
    ");
}
?>

<!DOCTYPE html>
<html>
<head>
 <title>myROBOT-V80</title>
  <link href="http://10.10.20.250/dashboard/APPS-ROBOT/BUILDING APLIKASI/@API-GITHUB-V80/ROBOT-GITHUB/ROBOTV80.png" rel="icon" type="image/png" />
<meta name="viewport" content="width=device-width, initial-scale=1">


<style>
body{
    font-family: 'Segoe UI', sans-serif;
    background: #f4f6f9;
    margin: 20px;
}

h2{
    color: #333;
}

/* FORM */
form{
    background: white;
    padding: 15px;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    display: inline-block;
    margin-bottom: 20px;
}

input[type="date"]{
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 8px;
    margin-right: 10px;
}

button{
    padding: 8px 15px;
    background: #4f46e5;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}

button:hover{
    background: #3730a3;
}

/* TABLE */
table{
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

th{
    background: #4f46e5;
    color: white;
    padding: 12px;
}

td{
    padding: 10px;
    border-bottom: 1px solid #eee;
}

tr:hover{
    background: #f0f4ff;
}

/* EMPTY STATE */
.empty{
    text-align: center;
    color: #888;
    padding: 20px;
}

/* PAGINATION */
.pagination a{
    display: inline-block;
    padding: 6px 12px;
    margin: 5px;
    background: white;
    border-radius: 8px;
    text-decoration: none;
    color: #333;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.pagination a:hover{
    background: #4f46e5;
    color: white;
}
</style>

</head>
<body>

<h2>E-Lacak</h2>

<!-- FILTER -->
<form method="GET">
    Dari Tanggal:
    <input type="date" name="start_date" value="<?= $start_date ?>">

    Sampai:
    <input type="date" name="end_date" value="<?= $end_date ?>">

    <button type="submit">Filter</button>
</form>

<br>

<!-- TABLE -->
<table>
    <tr>
        <th>No Jurnal</th>
        <th>No Bukti</th>
        <th>Tanggal</th>
        <th>Jam</th>
        <th>Jenis</th>
        <th>Keterangan</th>
    </tr>

    <?php if ($showData && $totalRow > 0) { ?>
        <?php while ($row = mysqli_fetch_assoc($query)) { ?>
        <tr>
            <td><?= $row['no_jurnal'] ?></td>
            <td><?= $row['no_bukti'] ?></td>
            <td><?= $row['tgl_jurnal'] ?></td>
            <td><?= $row['jam_jurnal'] ?></td>
            <td><?= $row['jenis'] ?></td>
            <td><?= $row['keterangan'] ?></td>
        </tr>
        <?php } ?>
    <?php } else { ?>
        <tr>
            <td colspan="6" class="empty">
                Silakan pilih tanggal terlebih dahulu untuk menampilkan data
            </td>
        </tr>
    <?php } ?>

</table>

<br>

<!-- PAGINATION -->
<div class="pagination">
<?php if ($showData) { ?>

    <?php if ($page > 1) { ?>
        <a href="?page=<?= $page - 1 ?>&start_date=<?= $start_date ?>&end_date=<?= $end_date ?>">⬅ Prev</a>
    <?php } ?>

    <?php for ($i = 1; $i <= $totalPage; $i++) { ?>
        <a href="?page=<?= $i ?>&start_date=<?= $start_date ?>&end_date=<?= $end_date ?>">
            <?= $i ?>
        </a>
    <?php } ?>

    <?php if ($page < $totalPage) { ?>
        <a href="?page=<?= $page + 1 ?>&start_date=<?= $start_date ?>&end_date=<?= $end_date ?>">Next ➡</a>
    <?php } ?>

<?php } ?>
</div>

</body>
</html>
