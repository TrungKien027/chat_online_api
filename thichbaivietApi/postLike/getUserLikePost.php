<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../../source/models/Auth.php';
require_once '../../source/models/PostLikeModel.php';
require_once '../../source/models/PostModel.php';

// Khởi tạo đối tượng từ model
$postLikeModel = new PostLikeModel();
$postModel = new Post();

// Nhận dữ liệu từ request
$user_id = $_GET['user_id'] ? $_GET['user_id'] : null;
// Kiểm tra dữ liệu đầu vào
if (!empty($user_id)) {
    $respon = $postLikeModel->getPostsLikedByUser($user_id);
    if ($respon) {
        echo json_encode([
            'success' => true,
            'data' => $respon,
        ]);
    } else {
        echo json_encode(['success' => true, 'data' => []]);
    }
} else {
    echo json_encode(['success' => false, 'message' => "không tìm thấy user"]);
}
