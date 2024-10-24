<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once '../../source/models/Auth.php';
$auth = new Auth();
$model = new UserModel();
$token = new TokenModel();
$data = json_decode(file_get_contents("php://input"), true);

// Xử lý logout
if (isset($data['token'])) {
    // Lấy userId từ token (giả sử bạn có hàm để giải mã token)
    $userId = $token->verifyToken($data['token']);
    
    // Gọi phương thức để cập nhật trạng thái người dùng
    if ($model->userUnActive($userId['user_id'])) {
        // Xóa token (nếu cần thiết, chẳng hạn trong database)
        $response = $auth->logout($data['token']);
        echo json_encode(['success' => true, 'message' => 'Logout successful']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to deactivate user']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Token not provided']);
}

