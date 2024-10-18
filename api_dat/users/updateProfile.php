<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

require_once '../../source/config/Database.php';
require_once '../../source/models/UserModel.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['user_id']) && is_numeric($data['user_id'])) {
    $user_id = intval($data['user_id']);
    $name = $data['name'] ?? null;
    $age = $data['age'] ?? null;
    $gender = $data['gender'] ?? null;
    $phone = $data['phone'] ?? null;

    $userModel = new UserModel();
    $result = $userModel->updateUserInfo1($user_id, $name, $age, $gender, $phone);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Thông tin cá nhân đã được cập nhật thành công']);
    } else {
        echo json_encode(['error' => true, 'message' => 'Cập nhật thông tin thất bại']);
    }
} else {
    echo json_encode(['error' => true, 'message' => 'Dữ liệu không hợp lệ']);
}
?>
