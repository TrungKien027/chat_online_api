
<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require_once '../../source/models/PostModel.php';
$postModel = new Post();
// Lấy giá trị lastPostId và limit từ yêu cầu GET
$lastPostId = isset($_GET['lastPostId']) ? $_GET['lastPostId'] : 0;
$limit = isset($_GET['limit']) ? $_GET['limit'] : 10;

// Lấy danh sách bài viết
$posts = $postModel->getPaginatedPosts($lastPostId, $limit);

// Trả về kết quả dưới dạng JSON
echo json_encode($posts);
?>
