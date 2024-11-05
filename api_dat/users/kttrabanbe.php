<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

require_once '../../source/models/UserModel.php';

if (isset($_GET['user_id']) && isset($_GET['friend_id']) && is_numeric($_GET['user_id']) && is_numeric($_GET['friend_id'])) {
    $user_id = intval($_GET['user_id']);
    $friend_id = intval($_GET['friend_id']);
    
    $userModel = new UserModel();
    $status = $userModel->checkFriendshipStatus($user_id, $friend_id);

    if ($status == 'accepted') {
        echo json_encode(['isFriend' => true]);
    } else {
        echo json_encode(['isFriend' => false]);
    }
} else {
    echo json_encode(['error' => true, 'message' => 'ID người dùng không hợp lệ!']);
}
