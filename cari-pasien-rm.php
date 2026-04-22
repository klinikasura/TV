<?php
include 'koneksi-scan.php';

if(isset($_POST['keyword'])){

    $keyword = $conn->real_escape_string($_POST['keyword']);

    $query = "
    SELECT 
        no_rkm_medis, 
        nm_pasien, 
        no_ktp,
        alamat,
        nm_ibu,
        kd_pj
    FROM pasien
    WHERE no_rkm_medis LIKE '%$keyword%'
       OR no_ktp LIKE '%$keyword%'
       OR nm_pasien LIKE '%$keyword%'
    LIMIT 10
    ";

    $result = $conn->query($query);

    if($result && $result->num_rows > 0){

        while($d = $result->fetch_assoc()){

            // ambil penjamin dari tabel penjab
            $penjab = "-";
            if(!empty($d['kd_pj'])){
                $pj = $conn->query("SELECT png_jawab FROM penjab WHERE kd_pj='{$d['kd_pj']}'");
                if($p = $pj->fetch_assoc()){
                    $penjab = $p['png_jawab'];
                }
            }

            echo "
            <div class='pasien-item'
                data-rm='{$d['no_rkm_medis']}'
                style='padding:10px;border-bottom:1px solid #ddd;cursor:pointer'>

                <b>{$d['nm_pasien']}</b><br>
                RM: {$d['no_rkm_medis']} | NIK: {$d['no_ktp']}<br>
                Alamat: {$d['alamat']}<br>
                Ibu: {$d['nm_ibu']}<br>
                Penjamin: {$penjab}

            </div>
            ";
        }

    } else {
        echo "<p>Tidak ditemukan</p>";
    }
}
?>
