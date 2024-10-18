<?php
header("Access-Control-Allow-Origin: *"); // Cho phép tất cả các miền
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Cho phép các phương thức
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Cho phép các header

header('Content-Type: application/json'); // Đặt loại nội dung cho phản hồi
require_once '../../source/models/UserModel.php'; // Đảm bảo đã bao gồm UserModel

// Tạo đối tượng UserModel
$userModel = new UserModel();

try {
    // Lấy tất cả người dùng
    $users = $userModel->getAll();

    // Kiểm tra xem có người dùng nào không
    if (count($users) > 0) {
        echo json_encode($users); // Trả về dữ liệu người dùng dưới dạng JSON
    } else {
        echo json_encode([]); // Trả về mảng rỗng nếu không có người dùng
    }
} catch (Exception $e) {
    // Xử lý lỗi
    echo json_encode(["error" => $e->getMessage()]);
}
