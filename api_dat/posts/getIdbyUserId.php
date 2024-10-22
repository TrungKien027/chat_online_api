<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

// // Bao gồm kết nối cơ sở dữ liệu
// require_once '../../source/config/Database.php';

// // Bao gồm model PostModel
// require_once '../../source/models/PostModel.php';

// // Kiểm tra nếu có `user_id` trong request
// if (isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
//     $user_id = intval($_GET['user_id']); 

//     // Tạo một instance của PostModel
//     $postModel = new Post();

//     // Gọi phương thức getIdByUserId để lấy ID bài post của người dùng
//     $postIds = $postModel->getIdByUserId($user_id);

//     if ($postIds) {
//         // Trả về danh sách ID bài post dưới dạng JSON
//         echo json_encode($postIds);
//     } else {
//         echo json_encode([
//             'error' => true,
//             'message' => 'Không có bài post nào cho người dùng này'
//         ]);
//     }
// } else {
//     echo json_encode([
//         'error' => true,
//         'message' => 'ID người dùng không hợp lệ!'
//     ]);
// }

