<?php
// Set header để cho phép yêu cầu từ các nguồn khác và định dạng JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '../../source/models/UserModel.php'; // Đảm bảo đã bao gồm UserModel
$database = new Database();
$userModel = new UserModel();

$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : ''; // Loại bỏ khoảng trắng thừa
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0; // Mặc định là 0
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;   // Mặc định là 10

// Kiểm tra nếu từ khóa tìm kiếm rỗng
if (empty($keyword)) {
    // Trả về thông điệp JSON mà không sử dụng mã phản hồi
    echo json_encode(["message" => "Keyword is required."]);
    exit();
}

// Tìm kiếm người dùng
$results = $userModel->searchUsers($keyword, $offset, $limit);

// Kiểm tra kết quả và trả về phản hồi JSON
if (!empty($results)) {
    echo json_encode($results); // Nếu tìm thấy người dùng
} else {
    echo json_encode(["message" => "No users found."]); // Nếu không tìm thấy người dùng
}
?>
