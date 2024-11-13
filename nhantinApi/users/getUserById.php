<?php
header("Access-Control-Allow-Origin: *"); // Cho phép tất cả các miền
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Cho phép các phương thức
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Cho phép các header


header('Content-Type: application/json'); // Đặt loại nội dung cho phản hồi
require_once '../../source/models/UserModel.php'; // Đảm bảo đã bao gồm UserModel

// Lấy ID từ query string
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $user_id = intval($_GET['id']); // Chuyển đổi ID thành số nguyên

    // Tạo một instance của UserModel
    $userModel = new UserModel();

    // Gọi phương thức getUserById để lấy thông tin người dùng
    $user = $userModel->getUserById($user_id);

    if ($user) {
        // Mã hóa thông tin trước khi trả về
        $user['name'] = htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8'); // Ví dụ với trường 'name'
        echo json_encode($user);
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
