<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once '../../source/models/UserModel.php'; // Nhúng model
require_once '../../source/models/TokenModel.php'; // Nhúng token model

$tokenModel = new TokenModel(); // Sửa lại tên biến đúng

// Kiểm tra yêu cầu POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy token từ body của yêu cầu
    $data = json_decode(file_get_contents('php://input'), true);
    $token = isset($data['token']) ? $data['token'] : null;

    if ($token) {
        $isValid = $tokenModel->verifyToken($token);
        
        if ($isValid) {
            $userId = $tokenModel->getUserIdFromToken($token); 
    
            if ($userId) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Token hợp lệ',
                    'userId' => $userId
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Không tìm thấy userId'
                ]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Token không hợp lệ']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Không có token']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ']);
}
