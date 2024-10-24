<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../../source/models/ChatRoomModel.php';

// Khởi tạo model
$roomModel = new ChatRoomModel();

if (!isset($_GET['room_id'])) {
    echo json_encode(['success' => false, 'error' => 'room_id is required']);
    exit;
}

$room_id = intval($_GET['room_id']);
$respon = $roomModel->getChatRoomById($room_id);
if ($respon) {
    echo json_encode(['success' => true, 'data' => $respon]);
} else {
    echo json_encode(['success' => false, 'error' => 'Room not found']);
}
