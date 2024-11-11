<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header("Access-Control-Allow-Headers: Content-Type, Authorization");


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['keyword']) && isset($_GET['offset'])) {
    $keyword = $_GET['keyword'];
    $offset = intval($_GET['offset']);
    $limit = 10; // Số lượng người dùng mỗi lần

    $users = $userModel->searchUsers($keyword, $offset, $limit);

    echo json_encode($users); // Trả về dữ liệu người dùng dưới dạng JSON
    exit;
}
