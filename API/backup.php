<?php
// Konfigurasi
$dbHost = '10.10.20.250';
$dbUser = 'root';
$dbPass = '';
$dbName = 'sikdraisyah';               // ganti sesuai kebutuhan
$backupDir = __DIR__ . 'http://10.10.20.250/dashboard/APPS-ROBOT/TV/API/backup';

// Pastikan folder ada
if (!is_dir($backupDir)) mkdir($backupDir,0755,true);

// Nama file
$file = $backupDir . $dbName . '_' . date('Ymd_His') . '.sql.gz';

// Jalankan mysqldump (gunakan output buffering agar bisa dipantau)
$cmd = "mysqldump -h$dbHost -u$dbUser -p$dbPass $dbName | gzip -c > $file";
exec($cmd, $out, $ret);

// Kirim status ke client
echo $ret === 0
    ? "OK|Backup berhasil: " . basename($file)
    : "ERR|Gagal backup (kode $ret)";
?>
