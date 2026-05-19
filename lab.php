<?php
include 'koneksi.php';

/* ======================================================
   DETAIL MODE
====================================================== */
$detail_id = isset($_GET['id']) ? $_GET['id'] : "";

if ($detail_id != "") {

    $data = mysqli_fetch_assoc(mysqli_query($koneksi,"
        SELECT * FROM temporary_permintaan_lab WHERE no='$detail_id'
    "));
?>
<!DOCTYPE html>
<html>
<head>
<title>Detail Lab</title>

<style>
body{
    font-family: Arial;
    background:#f4f6f9;
    padding:20px;
}

.paper{
    background:white;
    padding:20px;
    border-radius:10px;
    width:900px;
    margin:auto;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
}

.header{
    text-align:center;
    border-bottom:2px solid #000;
    margin-bottom:15px;
}

table{
    width:100%;
    border-collapse:collapse;
}

td{
    padding:8px;
    border:1px solid #eee;
    font-size:13px;
}

.label{
    background:#f8fafc;
    font-weight:bold;
    width:200px;
}

.btn{
    padding:10px 15px;
    border:none;
    border-radius:8px;
    cursor:pointer;
    margin-bottom:10px;
}

.back{background:#e5e7eb;}
.print{background:#16a34a; color:white;}

@media print{
    .btn{display:none;}
    body{background:white;}
    .paper{box-shadow:none;}
}
</style>

</head>

<body>

<button class="btn back" onclick="window.location='lab.php'">⬅ Kembali</button>
<button class="btn print" onclick="window.print()">🖨 Print</button>

<div class="paper">

<div class="header">
    <h2>HASIL LABORATORIUM</h2>
    <p>No: <?= $data['no']; ?> | <?= $data['temp1']; ?></p>
    <p>Tanggal: <?= $data['temp2']; ?></p>
</div>

<table>

<?php
for ($i=1;$i<=37;$i++){
    echo "<tr>
        <td class='label'>TEMP$i</td>
        <td>".$data["temp$i"]."</td>
    </tr>";
}
?>

</table>

</div>

</body>
</html>

<?php
exit;
}

/* ======================================================
   LIST MODE
====================================================== */

// PAGINATION
$limit = 20;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$start = ($page - 1) * $limit;

// SEARCH SMART
$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : "";

// WHERE
$where = "WHERE 1=1";

if ($search != "") {

    if (is_numeric($search)) {

        // kalau 4 digit → tahun
        if (strlen($search) == 4) {
            $where .= " AND YEAR(temp2) = '$search'";
        } else {
            $where .= " AND (temp1 LIKE '%$search%' OR temp3 LIKE '%$search%')";
        }

    } else {
        $where .= " AND (temp1 LIKE '%$search%' OR temp3 LIKE '%$search%')";
    }
}

// TOTAL
$total_query = mysqli_query($koneksi,"
    SELECT COUNT(*) as total 
    FROM temporary_permintaan_lab 
    $where
");

$total_data = mysqli_fetch_assoc($total_query)['total'];
$total_page = ceil($total_data / $limit);

// DATA (TERBARU DI ATAS)
$query = "
    SELECT * FROM temporary_permintaan_lab
    $where
    ORDER BY no DESC
    LIMIT $start, $limit
";

$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html>
<head>
 <title>myROBOT-V80</title>
  <link href="http://10.10.20.250/dashboard/APPS-ROBOT/BUILDING APLIKASI/@API-GITHUB-V80/ROBOT-GITHUB/ROBOTV80.png" rel="icon" type="image/png" />
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
body{
    font-family: Arial;
    background:#eef2f7;
    padding:20px;
}

.container{
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#4f46e5;
    color:white;
    padding:10px;
}

td{
    padding:10px;
    border-bottom:1px solid #eee;
}

tr:hover{
    background:#f1f5ff;
    cursor:pointer;
}

input{
    padding:10px;
    border:1px solid #ddd;
    border-radius:8px;
    width:250px;
}

button{
    padding:10px 15px;
    background:#4f46e5;
    color:white;
    border:none;
    border-radius:8px;
    cursor:pointer;
}

/* pagination */
.pagination a{
    padding:8px 12px;
    margin:2px;
    background:#e5e7eb;
    text-decoration:none;
    border-radius:6px;
}

.pagination a.active{
    background:#4f46e5;
    color:white;
}
</style>

</head>

<body>

<div class="container">

<h2>E-LAB</h2>

<!-- SEARCH -->
<form method="GET">
    <input type="text" name="search"
           placeholder="Cari (Nama / Tahun 2026 / dll)"
           value="<?= $search ?>">

    <button>Cari</button>
</form>

<br>

<table>
<tr>
    <th>No</th>
    <th>Kode </th>
    <th>No. Rawat</th>
    <th>Tindakan</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>
<tr onclick="window.location='lab.php?id=<?= $row['no']; ?>'">
    <td><?= $row['no']; ?></td>
    <td><?= $row['temp1']; ?></td>
    <td><?= $row['temp2']; ?></td>
    <td><?= $row['temp3']; ?></td>
</tr>
<?php } ?>

</table>

<!-- PAGINATION -->
<div class="pagination">

<?php
$q = $search ? "&search=$search" : "";

// prev
if ($page > 1) {
    echo "<a href='?page=".($page-1).$q."'>Prev</a>";
}

// number
for ($i=1;$i<=$total_page;$i++){
    $active = ($i==$page) ? "active" : "";
    echo "<a class='$active' href='?page=$i$q'>$i</a>";
}

// next
if ($page < $total_page) {
    echo "<a href='?page=".($page+1).$q."'>Next</a>";
}
?>

</div>

</div>

</body>
</html>
