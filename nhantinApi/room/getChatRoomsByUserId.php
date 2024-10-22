<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../../source/models/Auth.php';
require_once '../../source/models/ChatRoomModel.php';

// Khởi tạo model
$roomModel = new ChatRoomModel();

// Nhận dữ liệu từ client
$data = json_decode(file_get_contents('php://input'), true);

// Kiểm tra xem user_id có được gửi hay không
if (isset($data['user_id'])) {
    $userId = trim($data['user_id'], '"');
    // Lấy tất cả các phòng chat của người dùng
    $rooms = $roomModel->getChatRoomsByUserId($userId);

    // Kiểm tra nếu có phòng chat
    if ($rooms) {
        // Trả về danh sách phòng chat
        echo json_encode([
            'success' => true,
            'data' => $rooms
        ]);
    } else {
        // Trả về thông báo nếu không có phòng chat nào
        echo json_encode([
            'success' => true,
            'data' => [], // Trả về mảng rỗng khi không có phòng chat
            'message' => 'No chat rooms found.' // Thông báo thêm
        ]);
    }
} else {
    // Trả về lỗi nếu không có user_id
    echo json_encode([
        'success' => false,
        'error' => 'Missing user_id'
    ]);
}
