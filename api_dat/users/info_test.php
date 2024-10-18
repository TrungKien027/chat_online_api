<?php
// Đặt các header CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json'); // Đặt loại nội dung cho phản hồi JSON

require_once '../../source/config/Database.php'; // Gọi file kết nối cơ sở dữ liệu
require_once '../../source/models/UserModel.php'; // Gọi file UserModel

// Kiểm tra nếu user_id có trong query string
if (isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']); // Chuyển đổi user_id thành số nguyên
    
    // Tạo kết nối tới cơ sở dữ liệu
    $userModel = new UserModel();

    // Gọi phương thức getUserWithInfo từ UserModel để lấy thông tin người dùng và thông tin chi tiết
    $userInfo = $userModel->getUserWithInfo($user_id);

    // Kiểm tra nếu thông tin người dùng tồn tại
    if ($userInfo) {
        echo json_encode($userInfo); // Trả về thông tin người dùng dưới dạng JSON
    } else {
        // Nếu không tìm thấy người dùng
        echo json_encode(['error' => true, 'message' => 'Không tìm thấy người dùng']);
    }
} else {
    // Nếu user_id không hợp lệ hoặc không được cung cấp
    echo json_encode(['error' => true, 'message' => 'ID người dùng không hợp lệ!']);
}
?>
