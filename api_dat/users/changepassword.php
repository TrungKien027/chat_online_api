<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

require_once '../../source/config/Database.php';
require_once '../../source/models/UserModel.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['user_id']) && isset($data['old_password']) && isset($data['new_password'])) {
    $userId = intval($data['user_id']);
    $oldPassword = $data['old_password'];
    $newPassword = $data['new_password'];

    $userModel = new UserModel();

    // Thực hiện thay đổi mật khẩu
    if ($userModel->updatePassword($userId, $oldPassword, $newPassword)) {
        echo json_encode(['success' => true, 'message' => 'Mật khẩu đã được thay đổi thành công.']);
    } else {
        echo json_encode(['error' => true, 'message' => 'Mật khẩu cũ không đúng.']);
    }
} else {
    echo json_encode(['error' => true, 'message' => 'Dữ liệu không hợp lệ.']);
}
?>
