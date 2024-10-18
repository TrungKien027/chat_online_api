<?php
header("Access-Control-Allow-Origin: *"); // Cho phép tất cả các miền
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Cho phép các phương thức
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Cho phép các header


header('Content-Type: application/json'); // Đặt loại nội dung cho phản hồi
require_once '../../source/models/UserModel.php'; // Đảm bảo đã bao gồm UserModel



if (isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']); // Chuyển đổi user_id thành số nguyên


    $userModel = new UserModel();

    // Gọi phương thức getFriends để lấy danh sách bạn bè
    $friends = $userModel->getFriendship($user_id);

    if (!empty($friends)) {
        echo json_encode($friends); // Xuất dữ liệu danh sách bạn bè
    } else {
        echo json_encode([
            'error' => true,
            'message' => 'Không tìm thấy bạn bè cho người dùng này'
        ]);
    }
} else {
    echo json_encode([
        'error' => true,
        'message' => 'ID người dùng không hợp lệ!'
    ]);
}
