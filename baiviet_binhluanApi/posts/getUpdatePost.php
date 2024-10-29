<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Cho phép tất cả các nguồn (hoặc thay * bằng địa chỉ cụ thể của bạn)
header('Access-Control-Allow-Methods: GET, POST'); // Các phương thức được phép
header('Access-Control-Allow-Headers: Content-Type'); // Các header được phép
require_once '../../source/models/PostModel.php';
require_once '../../source/models/GeneralModel.php';

$postModel = new Post();
$generalModel = new GeneralModel();
if ($_GET['post_id']) {
    $post_id = $_GET['post_id'] ? $_GET['post_id'] : null;
    $response = $generalModel->getUpdatePost($post_id);
    if ($response) {
        echo json_encode(['success' => true, 'data' => $response, 'message' => "Bài viết id " . $post_id]);
    } else {
        echo json_encode(['success' => false, 'data' => [], 'message' => "Không tìm tháy."]);
    }
}
