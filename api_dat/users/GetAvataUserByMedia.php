<?php
header("Access-Control-Allow-Origin: *"); // Cho phép tất cả các miền
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Cho phép các phương thức
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Cho phép các header

header('Content-Type: application/json'); // Đặt loại nội dung cho phản hồi
require_once '../../source/models/UserModel.php'; // Đảm bảo đã bao gồm UserModel

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $user_id = intval($_GET['id']); // Chuyển đổi ID thành số nguyên

    // Tạo một instance của UserModel
    $userModel = new UserModel();

    $user = $userModel->GetAvatarUserByMedia($user_id);
    

    if ( $user) {
        // Trả về danh sách bài post dưới dạng JSON
        echo json_encode($user);
    } else {
        echo json_encode([
            'error' => true,
            'message' => 'Avatar default'
        ]);
    }
} else {
    echo json_encode([
        'error' => true,
        'message' => 'Media của người dùng không hợp lệ!'
    ]);
}
