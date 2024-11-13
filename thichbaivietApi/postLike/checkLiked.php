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
$data = json_decode(file_get_contents("php://input"));
// Kiểm tra dữ liệu đầu vào
if (!empty($data->post_id) && !empty($data->user_id)) {
    $post_id = $data->post_id;
    $user_id = $data->user_id;
    // Gọi phương thức để tạo "like" cho bài đăng
    $liked = $postLikeModel->isPostLiked($post_id, $user_id);
    if ($liked) {
        echo json_encode([
            "success" => true,
            "liked" => true
        ]);
    } else {
        echo json_encode([
            "success" => true,
            "liked" => false
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Đã có lỗi xảy ra."
    ]);
}
