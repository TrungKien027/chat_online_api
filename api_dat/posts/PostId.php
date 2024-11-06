<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');
require_once '../../source/config/Database.php';

require_once '../../source/models/PostModel.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $postModel = new Post();
    $post = $postModel->getPostById($id);

    if ($post) {
        echo json_encode([
            "success" => true,
            "data" => $post
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Bài viết không tồn tại"
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Thiếu ID"
    ]);
}
?>
