<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');
require_once '../../source/config/Database.php';

require_once '../../source/models/PostShareModel.php';

$data = json_decode(file_get_contents("php://input"));

if (isset($data->post_id) && isset($data->user_share_id)) {
    $postShareModel = new PostShareModel();
    $result = $postShareModel->createPostShare($data->post_id, $data->user_share_id);

    if ($result) {
        echo json_encode(["success" => true, "message" => "Chia sẻ bài viết thành công."]);
    } else {
        echo json_encode(["success" => false, "message" => "Lỗi khi chia sẻ bài viết."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Thiếu dữ liệu."]);
}