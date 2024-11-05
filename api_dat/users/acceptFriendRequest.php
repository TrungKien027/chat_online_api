<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once '../../source/models/UserModel.php';

// Kết nối đến database (giả sử bạn đã có mã kết nối)

$friendshipModel = new UserModel();

$data = json_decode(file_get_contents("php://input"), true);

$response = ['success' => false, 'message' => ''];

if (isset($data['user_id']) && isset($data['friend_id'])) {
    $userId = $data['user_id'];
    $friendId = $data['friend_id'];

    if ($friendshipModel->acceptFriendRequest($userId, $friendId)) {
        $response['success'] = true;
        $response['message'] = 'Friend request accepted.';
    } else {
        $response['message'] = 'Failed to accept friend request.';
    }
} else {
    $response['message'] = 'User ID and Friend ID are required.';
}

echo json_encode($response);
?>
