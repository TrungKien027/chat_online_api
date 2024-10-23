<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../../source/models/Auth.php';
require_once '../../source/models/MessageModel.php';

// Khởi tạo model
$messageModel = new MessageModel();

// Nhận dữ liệu từ client
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['room_id'])) {
    $room_id = $data['room_id'];
    $messages = $messageModel->getMessagesByRoomId($room_id);
    if ($messages) {
        echo json_encode([
            'success' => true,
            'data' => $messages
        ]);
    } else {

        echo json_encode([
            'success' => true,
            'data' => [],
            'message' => 'No chat rooms found.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Missing user_id1'
    ]);
}
