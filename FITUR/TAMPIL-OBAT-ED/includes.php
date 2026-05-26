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
    $query = "SELECT u.nama, u.posisi, u.alamat, u.username, u.alamat, u.jk, u.ttl, u.foto, u.saldo, u.gaji, u.status, u.cek_status, u.sip, u.ket, u.str, us.login_time, us.last_activity FROM robot80_user_sessions us JOIN robot80_data_anggota u ON us.user_id=u.id WHERE us.last_activity > '$time_threshold' AND us.is_active=1";
    $result = $mysqli->query($query);
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    return $users;
}

// API untuk mendapatkan pengguna aktif (JSON)
if (isset($_GET['action']) && $_GET['action'] == 'get_active_users') {
    header('Content-Type: application/json');
    $active_users = get_active_users();
    echo json_encode($active_users);
    exit;
}
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    function loadActiveUsers() {
        $.ajax({
            url: '?action=get_active_users',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#active-users').html('');
                $.each(data, function(index, user) {
                    var last_activity = new Date(user.last_activity).toLocaleString('id-ID');
                    var html = '<div class="user">';
                    html += '<p>Nama: ' + user.nama + '</p>';
                    html += '<p>Posisi: ' + user.posisi + '</p>';
                    html += '<p>Aktivitas Terakhir: ' + last_activity + '</p>';
                    html += '<hr>';
                    html += '</div>';
                    $('#active-users').append(html);
                });
            }
        });
    }

    // Update setiap 5 detik
    setInterval(loadActiveUsers, 5000);
    loadActiveUsers(); // Load pertama kali
});
</script>

