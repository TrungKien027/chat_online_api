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
$post_id = $_GET['post_id'] ? $_GET['post_id'] : null;
// Kiểm tra dữ liệu đầu vào
if (!empty($post_id)) {
    $respon = $postLikeModel->getPostLikeCount($post_id);
    if ($respon) {
        echo json_encode([
            'success' => true,
            'data' => $respon,

        ]);
    } else {
        echo json_encode(['success' => true, 'data' => []]);
    }
} else {
    echo json_encode(['success' => true, 'message' => "không tìm thấy post"]);
}
