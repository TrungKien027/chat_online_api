<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');
require_once '../../source/config/Database.php';

// Bao gồm model PostModel
require_once '../../source/models/PostModel.php';

$data = json_decode(file_get_contents("php://input"), true);

// Kiểm tra nếu có `user_id` trong request
if (isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']); 

    // Tạo một instance của PostModel
    $postModel = new Post();

    // Gọi phương thức getPostsByUserId để lấy bài viết của người dùng
    $posts = $postModel->getPostsByUserId($user_id);

    if ($posts) {
        // Trả về danh sách bài post dưới dạng JSON
        echo json_encode($posts);
    } else {
        echo json_encode([
            'error' => true,
            'message' => 'Không có bài post nào'
        ]);
    }
} else {
    echo json_encode([
        'error' => true,
        'message' => 'ID người dùng không hợp lệ!'
    ]);
}
