<?php
include 'koneksi-scan.php';

if(isset($_POST['keyword1']) || isset($_POST['keyword2'])){

    $keyword1 = $conn->real_escape_string($_POST['keyword1']);
    $keyword2 = $conn->real_escape_string($_POST['keyword2']);

    // =========================
    // FUNCTION HIGHLIGHT
    // =========================
    function highlight($text, $keyword){
        if(empty($keyword)) return $text;

        return preg_replace(
            "/(" . preg_quote($keyword, '/') . ")/i",
            "<span style='color:red;font-weight:bold;'>$1</span>",
            $text
        );
    }

    // =========================
    // WHERE UTAMA (KOLOM 1)
    // =========================
    $where1 = "
        p.no_rkm_medis LIKE '%$keyword1%'
        OR p.no_ktp LIKE '%$keyword1%'
        OR p.nm_pasien LIKE '%$keyword1%'
        OR p.alamat LIKE '%$keyword1%'
        OR p.nm_ibu LIKE '%$keyword1%'
        OR rp.p_jawab LIKE '%$keyword1%'
    ";

    // =========================
    // WHERE FILTER (KOLOM 2)
    // =========================
    $where2 = "";
    if(!empty($keyword2)){
        $where2 = "AND (
            p.alamat LIKE '%$keyword2%'
            OR rp.p_jawab LIKE '%$keyword2%'
        )";
    }

    // =========================
    // QUERY PASIEN
    // =========================
    $query = "
    SELECT DISTINCT
        p.no_rkm_medis, 
        p.nm_pasien, 
        p.no_ktp,
        p.alamat,
        p.nm_ibu,
        p.kd_pj
    FROM pasien p
    LEFT JOIN reg_periksa rp ON p.no_rkm_medis = rp.no_rkm_medis
    WHERE ($where1)
    $where2
    ORDER BY p.nm_pasien ASC
    LIMIT 100
    ";

    $result = $conn->query($query);

    if($result && $result->num_rows > 0){

        while($d = $result->fetch_assoc()){

            // =========================
            // STATUS DAFTAR
            // =========================
            $status_daftar = "Baru";
            $warna_status = "#e53935";

            $qstatus = $conn->query("
                SELECT stts_daftar 
                FROM reg_periksa 
                WHERE no_rkm_medis='{$d['no_rkm_medis']}'
                ORDER BY tgl_registrasi DESC
                LIMIT 1
            ");

            if($qstatus && $s = $qstatus->fetch_assoc()){
                if($s['stts_daftar'] == "Lama"){
                    $status_daftar = "Lama";
                    $warna_status = "#43a047";
                }
            }

            // =========================
            // PENJAMIN
            // =========================
            $penjab = "-";

            if(!empty($d['kd_pj'])){
                $pj = $conn->query("
                    SELECT png_jawab 
                    FROM penjab 
                    WHERE kd_pj='{$d['kd_pj']}'
                    LIMIT 1
                ");
                if($p = $pj->fetch_assoc()){
                    $penjab = $p['png_jawab'];
                }
            }

            $warna_pj = "#6c757d";
            $bg_pj = "#f1f1f1";

            if(strpos(strtoupper($penjab), "BPJS") !== false){
                $warna_pj = "#fff";
                $bg_pj = "#43a047";
            } elseif(strtoupper($penjab) == "UMUM"){
                $warna_pj = "#fff";
                $bg_pj = "#1e88e5";
            }

            // =========================
            // PENANGGUNG JAWAB
            // =========================
            $pjawab = "-";
            $status_pj = "-";

            $qpj = $conn->query("
                SELECT p_jawab, hubunganpj
                FROM reg_periksa 
                WHERE no_rkm_medis='{$d['no_rkm_medis']}'
                ORDER BY tgl_registrasi DESC
                LIMIT 1
            ");

            if($qpj && $p = $qpj->fetch_assoc()){
                if(!empty($p['p_jawab'])){
                    $pjawab = $p['p_jawab'];
                }
                if(!empty($p['hubunganpj'])){
                    $status_pj = $p['hubunganpj'];
                }
            } else {
                $pjawab = $d['nm_ibu'];
                $status_pj = "Ibu";
            }

            // =========================
            // OUTPUT
            // =========================
            echo "
            <div class='pasien-item'
                data-rm='{$d['no_rkm_medis']}'
                style='padding:12px;border-bottom:1px solid #ddd;cursor:pointer'>

                <b style='color:#2a7da8'>" . highlight($d['nm_pasien'], $keyword1) . "</b>

                <span style='
                    float:right;
                    background:$warna_status;
                    color:white;
                    padding:3px 8px;
                    border-radius:8px;
                    font-size:11px;
                '>
                    $status_daftar
                </span>

                <br>

                RM: {$d['no_rkm_medis']} | NIK: {$d['no_ktp']}<br>
                Alamat: " . highlight($d['alamat'], $keyword1) . "<br>
                Ibu: " . highlight($d['nm_ibu'], $keyword1) . "<br>

                <b>Penanggung Jawab:</b> " . highlight($pjawab, $keyword1) . "<br>
                <b>Status PJ:</b> {$status_pj}<br>

                Penjamin: 
                <span style='
                    background:$bg_pj;
                    color:$warna_pj;
                    padding:3px 8px;
                    border-radius:8px;
                    font-size:11px;
                    font-weight:bold;
                '>
                    $penjab
                </span>

            </div>
            ";
        }

    } else {
        echo "<p style='padding:10px'>Tidak ditemukan</p>";
    }
}
?>
