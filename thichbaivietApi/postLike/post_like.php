<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../../source/models/Auth.php';
require_once '../../source/models/TokenModel.php';
require_once '../../source/models/PostLikeModel.php';
require_once '../../source/models/PostModel.php';

// Khởi tạo đối tượng từ model
$postLikeModel = new PostLikeModel();
$postModel = new Post();
$tokenModel = new TokenModel();

// Nhận dữ liệu từ request
$data = json_decode(file_get_contents("php://input"));
// Kiểm tra dữ liệu đầu vào
if (!empty($data->post_id) && !empty($data->user_id)) {
    $post_id = $data->post_id;
    $user_id = $data->user_id;
    $authToken = $data->authToken;
    $auth = $tokenModel->verifyToken($authToken);
    // Gọi phương thức để tạo "like" cho bài đăng
    if ($auth) {
        if ($postModel->read($post_id)) {
            $liked = $postLikeModel->isPostLiked($post_id, $user_id);
            if (!$liked) {
                $isCreated = $postLikeModel->createPostLike($post_id, $user_id);
                if ($isCreated) {
                    // Trả về phản hồi thành công
                    echo json_encode([
                        "success" => true,
                        "message" => "Like added successfully.",
                        "like" => 1
                    ]);
                } else {
                    // Trả về phản hồi thất bại
                    echo json_encode([
                        "success" => false,
                        "message" => "Failed to add like."
                    ]);
                }
            } else {
                $isUnlike = $postLikeModel->deletePostLike($post_id, $user_id);
                if ($isUnlike) {
                    // Trả về phản hồi thành công
                    echo json_encode([
                        "success" => true,
                        "message" => "Unlike successfully.",
                        "like" => 0
                    ]);
                } else {
                    // Trả về phản hồi thất bại
                    echo json_encode([
                        "success" => false,
                        "message" => "Failed to unlike like."
                    ]);
                }
            }
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Không tìm thấy bài viết."
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Xác thực thất bại. Vui lòng đăng nhập lại."
        ]);
    }
} else {
    // Trả về phản hồi khi dữ liệu không đầy đủ
    echo json_encode([
        "success" => false,
        "message" => "Incomplete data."
    ]);
}
