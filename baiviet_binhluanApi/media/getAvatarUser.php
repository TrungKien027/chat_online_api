<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Cho phép tất cả các nguồn (hoặc thay * bằng địa chỉ cụ thể của bạn)
header('Access-Control-Allow-Methods: GET'); // Các phương thức được phép
header('Access-Control-Allow-Headers: Content-Type'); // Các header được phép
require_once '../../source/models/MediaModel.php';

$mediaModel = new MediaModel();
if ($_GET['user_id']) {
    $id = $_GET['user_id'] ? $_GET['user_id'] : null;
    $response = $mediaModel->getAvatarUser($id);
    if ($response) {
        echo json_encode(['success' => true, 'data' => $response, 'message' => "Tìm thấy " . $id]);
    } else {
        echo json_encode(['success' => false, 'data' => [], 'message' => "Không tìm tháy."]);
    }
}
