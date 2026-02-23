<?php
function update_user_activity($user_id) {
    global $mysqli;
    $now = date('Y-m-d H:i:s');
    $query = "UPDATE robot80_user_sessions SET last_activity='$now' WHERE user_id=$user_id AND is_active=1";
    $mysqli->query($query);
}

function get_active_users() {
    global $mysqli;
    $time_threshold = date('Y-m-d H:i:s', strtotime('-5 minutes'));
    $query = "SELECT 
                u.nama, 
                u.posisi, 
                u.alamat, 
                u.username, 
u.alamat,
u.jk,
u.ttl,
u.foto,
u.saldo,
u.gaji,
u.status,
u.cek_status,
u.sip,
u.ket,
u.str,
                us.login_time, 
                us.last_activity 
              FROM robot80_user_sessions us 
              JOIN robot80_data_anggota u ON us.user_id=u.id 
              WHERE us.last_activity > '$time_threshold' AND us.is_active=1";
    $result = $mysqli->query($query);
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    return $users;
}

?>
