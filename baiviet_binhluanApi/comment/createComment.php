<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Cho phép tất cả các nguồn (hoặc thay * bằng địa chỉ cụ thể của bạn)
header('Access-Control-Allow-Methods: POST'); // Các phương thức được phép
header('Access-Control-Allow-Headers: Content-Type'); // Các header được phép
require_once '../../source/models/PostCommentModel.php';
require_once '../../source/models/GeneralModel.php';

$commentModel = new PostCommentModel();
$genaralModel = new GeneralModel();
if ($_GET['post_id']) {
    $id = $_GET['post_id'] ? $_GET['post_id'] : null;
    $response = $genaralModel->getCommentPostId($id);
    if ($response) {
        echo json_encode(['success' => true, 'data' => $response, 'message' => "Tìm thấy " . $id]);
    } else {
        echo json_encode(['success' => false, 'data' => [], 'message' => "Không tìm tháy."]);
    }
}
