<?php
// --------------------------------------------------
// Konfigurasi cache
$cacheFile = __DIR__ . '/cache/articles.json';
$cacheTime = 5 * 60; // 5 menit

// --------------------------------------------------
// 1. Cek cache
if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTime)) {
    $articles = json_decode(file_get_contents($cacheFile), true);
    $fromCache = true;
} else {
    // 2. Jika cache expired atau belum ada, ambil dari DB / API
    $articles = getArticlesFromDatabase(); // fungsi buatan Anda
    // Simpan ke file cache
    file_put_contents($cacheFile, json_encode($articles));
    $fromCache = false;
}

// --------------------------------------------------
// 3. Output (bisa pakai header untuk kontrol browser)
header('Content-Type: application/json');
if ($fromCache) {
    header('X-Cache: HIT');
} else {
    header('X-Cache: MISS');
}
echo json_encode($articles);

// --------------------------------------------------
// Fungsi dummy: ganti dengan query ke DB
function getArticlesFromDatabase() {
    // Contoh data statis
    return [
        ['id'=>1,'title'=>'Artikel 1','content'=>'...'],
        ['id'=>2,'title'=>'Artikel 2','content'=>'...'],
        ['id'=>3,'title'=>'Artikel 3','content'=>'...'],
    ];
}
?>

