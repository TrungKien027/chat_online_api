<?php
header('Content-Type: application/json'); // Đặt loại nội dung cho phản hồi
header('Access-Control-Allow-Origin: *'); // Cho phép tất cả các nguồn (hoặc thay * bằng địa chỉ cụ thể của bạn)
header('Access-Control-Allow-Methods: POST'); // Các phương thức được phép
header('Access-Control-Allow-Headers: Content-Type'); // Các header được phép
require_once '../../source/models/UserModel.php'; // Nhúng model
require_once '../../source/models/TokenModel.php'; // Nhúng token model


// Tạo một instance của UserModel
$userModel = new UserModel();

// Lấy dữ liệu JSON từ yêu cầu
$data = json_decode(file_get_contents("php://input"), true);

// Kiểm tra xem dữ liệu có đầy đủ không
if (isset($data['name']) && isset($data['email']) && isset($data['password'])) {
    // Kiểm tra định dạng email
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Email không hợp lệ.']);
        exit;
    }

    // Kiểm tra email đã tồn tại
    if ($userModel->getUserByEmail($data['email'])) {
        echo json_encode(['success' => false, 'message' => 'Email đã tồn tại.']);
        exit;
    }
    // Gọi phương thức createUser để thêm người dùng
    $response = $userModel->createUser($data);

    // Kiểm tra kết quả trả về
    if ($response) {
        // Nếu thành công
        echo json_encode(['success' => true, 'message' => 'Đăng ký thành công!']);
    } else {
        // Nếu không thành công
        echo json_encode(['success' => false, 'message' => 'Đăng ký thất bại.']);
    }
} else {
    // Nếu dữ liệu không hợp lệ
    echo json_encode(['success' => false, 'message' => 'Vui lòng nhập đầy đủ thông tin!']);
}
