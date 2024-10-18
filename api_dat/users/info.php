<?php
header("Access-Control-Allow-Origin: *"); // Cho phép tất cả các miền
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Cho phép các phương thức
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Cho phép các header

header('Content-Type: application/json'); // Đặt loại nội dung cho phản hồi
require_once '../../source/models/UserModel.php'; // Đảm bảo đã bao gồm UserModel

$data = json_decode(file_get_contents("php://input"), true);

// Kiểm tra nếu có `user_id` trong dữ liệu đầu vào
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $user_id = intval($_GET['id']); 
    // Tạo một instance của UserModel
    $userModel = new UserModel();

    // Gọi phương thức getUserInfoByUserId để lấy thông tin người dùng
    $userInfo = $userModel->getUserInfoByUserId($user_id);

    if ($userInfo) {
        // Mã hóa thông tin trước khi trả về
       
        $userInfo['gender'] = htmlspecialchars($userInfo['gender'], ENT_QUOTES, 'UTF-8'); // Mã hóa trường 'gender'
        $userInfo['phone'] = htmlspecialchars($userInfo['phone'], ENT_QUOTES, 'UTF-8'); // Mã hóa trường 'phone'

        // Trả về thông tin người dùng
        echo json_encode($userInfo);
    } else {
        echo json_encode([
            'error' => true,
            'message' => 'Không tìm thấy người dùng'
        ]);
    }
} else {
    echo json_encode([
        'error' => true,
        'message' => 'ID người dùng không hợp lệ!'
    ]);
}
