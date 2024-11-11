<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Cho phép tất cả các nguồn (hoặc thay * bằng địa chỉ cụ thể của bạn)
header('Access-Control-Allow-Methods: GET, POST'); // Các phương thức được phép
header('Access-Control-Allow-Headers: Content-Type'); // Các header được phép
require_once '../../source/models/PostModel.php';
require_once '../../source/models/GeneralModel.php';
require_once '../../source/models/PostLikeModel.php';

$postModel = new Post();
$generalModel = new GeneralModel();
$postLikeModel = new PostLikeModel();

if ($_GET['user_id'] && $_GET['post_id']) {
    $user_id = $_GET['user_id'] ? $_GET['user_id'] : null;
    $post_id = $_GET['post_id'] ? $_GET['post_id'] : null;
    $response = $postLikeModel->isPostLiked($post_id, $user_id);
    if ($response) {
        echo json_encode(['success' => true, "status" => true, 'message' => "Đã thích.", 'user_id' => $user_id]);
    } else {
        echo json_encode(['success' => true, "status" => false, 'message' => "Chưa thích", 'user_id' => $user_id]);
    }
} else {
    echo json_encode(['success' => false, 'message' => "Không tìm thấy id"]);
}
