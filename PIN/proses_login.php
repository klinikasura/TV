<?php
session_start();

$conn = new mysqli("10.10.20.250", "root", "", "sikdraisyah");
$conn->query("SET time_zone = '+07:00'");

if ($conn->connect_error) {
    die("Koneksi gagal");
}

$pin = $_POST['pin'];

// 🔒 CEK LOCK
if (isset($_SESSION['lock_time'])) {
    $now = time();
    $lockTime = $_SESSION['lock_time'];

    if (($now - $lockTime) < 60) {
        $sisa = 60 - ($now - $lockTime);
        header("Location: index.php?error=locked&time=".$sisa);
        exit;
    } else {
        unset($_SESSION['lock_time']);
        $_SESSION['attempt'] = 0;
    }
}

// default attempt
if (!isset($_SESSION['attempt'])) {
    $_SESSION['attempt'] = 0;
}

// cek PIN
$stmt = $conn->prepare("SELECT * FROM robot80_data_anggota WHERE password=?");
$stmt->bind_param("s", $pin);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $data = $result->fetch_assoc();

    $_SESSION['user_id'] = $data['id'];
$_SESSION['nama'] = $data['nama'];

// ✅ TAMBAHAN INI (PENTING)
date_default_timezone_set('Asia/Jakarta');
$now = date('Y-m-d H:i:s');

$conn->query("INSERT INTO robot80_user_sessions 
(user_id, login_time, last_activity, is_active) 
VALUES (".$data['id'].", '$now', '$now', 1)");

// lanjut redirect
header("Location: http://10.10.20.250/dashboard/APPS-ROBOT/TV/scan.php");
exit;

} else {

    $_SESSION['attempt']++;

    if ($_SESSION['attempt'] >= 3) {
        $_SESSION['lock_time'] = time();
        header("Location: index.php?error=locked&time=60");
    } else {
        header("Location: index.php?error=1");
    }

    exit;
}
?>
