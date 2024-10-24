<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

header('Content-Type: application/json');
require_once '../../source/models/UserModel.php';

if (isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']); 

    $userModel = new UserModel();

    // Lấy danh sách bạn bè
    $friends = $userModel->getFriendship($user_id);

    if (!empty($friends)) {
        // Duyệt qua danh sách và thiết lập trạng thái hoạt động hoặc offline
        foreach ($friends as &$friend) {
            $friend['status'] = $friend['status'] == 1 ? 'Đang hoạt động' : 'Offline';
        }
        echo json_encode($friends); 
    } else {
        echo json_encode([
            'error' => true,
            'message' => 'Không tìm thấy bạn bè cho người dùng này'
        ]);
    }
} else {
    echo json_encode([
        'error' => true,
        'message' => 'ID người dùng không hợp lệ!'
    ]);
}
?>
