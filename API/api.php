<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// ambil path (contoh: /users/123)
$path = isset($_GET['path']) ? trim($_GET['path'], '/') : '';
$parts = explode('/', $path);
$resource = $parts[0] ?? '';
$id       = $parts[1] ?? null;

$method = $_SERVER['REQUEST_METHOD'];

// data dummy (biasanya dari DB)
$data = [
    'users' => [
        1 => ['id'=>1,'nama'=>'Andi','role'=>'admin'],
        2 => ['id'=>2,'nama'=>'Budi','role'=>'user'],
    ]
];

// routing sederhana
$response = ['error' => 'Endpoint tidak ditemukan'];
http_response_code(404);

switch ($resource) {
    case 'users':
        switch ($method) {
            case 'GET':
                if ($id) {
                    $response = $data['users'][$id] ?? ['error'=>'User tidak ditemukan'];
                    http_response_code(isset($data['users'][$id]) ? 200 : 404);
                } else {
                    $response = $data['users'];
                    http_response_code(200);
                }
                break;

            case 'POST':
                $input = json_decode(file_get_contents('php://input'), true);
                $newId = max(array_keys($data['users'])) + 1;
                $data['users'][$newId] = ['id'=>$newId] + $input;
                $response = $data['users'][$newId];
                http_response_code(201);
                break;

            case 'PUT':
                if ($id) {
                    $input = json_decode(file_get_contents('php://input'), true);
                    if (isset($data['users'][$id])) {
                        $data['users'][$id] = array_merge($data['users'][$id], $input);
                        $response = $data['users'][$id];
                        http_response_code(200);
                    } else {
                        $response = ['error'=>'User tidak ditemukan'];
                        http_response_code(404);
                    }
                }
                break;

            case 'DELETE':
                if ($id && isset($data['users'][$id])) {
                    unset($data['users'][$id]);
                    $response = ['message'=>'User dihapus'];
                    http_response_code(200);
                } else {
                    $response = ['error'=>'User tidak ditemukan'];
                    http_response_code(404);
                }
                break;

            default:
                http_response_code(405);
                $response = ['error'=>'Method tidak diizinkan'];
        }
        break;
}

// output JSON
echo json_encode($response);
?>

