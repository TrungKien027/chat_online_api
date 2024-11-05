<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '../../source/models/PostModel.php';
$database = new Database();
$postModel = new Post();

$keyword = isset($_GET['keyword']) ? rtrim($_GET['keyword']) : ''; 
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$currentUserId = isset($_GET['currentUserId']) ? (int)$_GET['currentUserId'] : 0; // ID người dùng hiện tại
$postFrom = isset($_GET['postFrom']) ? (int)$_GET['postFrom'] : 3; // Mặc định là tất cả
$orderBy = isset($_GET['orderBy']) ? (int)$_GET['orderBy'] : 1; // Thêm tham số orderBy, mặc định là 1
if (empty($keyword)) {
    echo json_encode(["message" => "Keyword is required."]);
    exit();
}

$results = $postModel->searchPost($keyword, $offset, $limit, $currentUserId, $postFrom, $orderBy);
if (!empty($results)) {
    echo json_encode($results);
} else {
    echo json_encode(["message" => "No post found."]);
}
?>
