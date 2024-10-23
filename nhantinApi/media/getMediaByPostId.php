<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../../source/models/Auth.php';
require_once '../../source/models/MediaModel.php';

// Khởi tạo model
$mediaModel = new MediaModel();

// Nhận dữ liệu từ client
$data = json_decode(file_get_contents('php://input'), true);

// Kiểm tra xem user_id có được gửi hay không
if (isset($data['post_id'])) {
    $post_id = $data['post_id'];
    // Lấy tất cả các phòng chat của người dùng
    $post_media = $mediaModel->getMediaByPostId($post_id);
    if ($post_media) {
        echo json_encode([
            'success' => true,
            'data' => $post_media
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'data' => [],
            'message' => 'No media post found.'
        ]);
    }
} else {
    // Trả về lỗi nếu không có user_id
    echo json_encode([
        'success' => false,
        'error' => 'Missing post id'
    ]);
}
