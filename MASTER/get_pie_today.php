<?php
$conn = new mysqli("10.10.20.250", "root", "", "sikdraisyah");

$query = "
SELECT stts, COUNT(*) as jumlah
FROM reg_periksa
WHERE tgl_registrasi = CURDATE()
GROUP BY stts
";

$result = $conn->query($query);

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
