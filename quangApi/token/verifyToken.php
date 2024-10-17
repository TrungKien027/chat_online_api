<?php
header('Content-Type: applicaiton/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
require_once '../../source/models/UserModel.php'; // Nhúng model
require_once '../../source/models/TokenModel.php'; // Nhúng token model

$userModel = new UserModel();
$tokemModel = new TokenModel();

// Kiểm tra yêu cầu POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy token từ body của yêu cầu
    $data = json_decode(file_get_contents('php://input'), true);
    $token = isset($data['token']) ? $data['token'] : null;

    if ($token) {
        // Gọi hàm verifyToken để kiểm tra token
        $isValid = $tokemModel->verifyToken($token);
        if ($isValid) {
            echo json_encode(['success' => true, 'message' => 'Token hợp lệ']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Token không hợp lệ']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Không có token']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ']);
}
