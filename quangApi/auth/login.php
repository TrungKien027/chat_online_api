<?php
// header('Content-Type: application/json');
// header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Methods: POST');
// header('Access-Control-Allow-Headers: Content-Type');

// require_once '../../source/models/Auth.php';
// require_once '../../source/models/UserModel.php';
// $auth = new Auth();
// $userModel = new UserModel();

// // Kiểm tra yêu cầu POST
// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     // Lấy dữ liệu từ body
//     $data = json_decode(file_get_contents('php://input'), true);
//     $email = isset($data['email']) ? $data['email'] : null;
//     $password = isset($data['password']) ? $data['password'] : null;

//     // Kiểm tra xem email và mật khẩu có được cung cấp không
//     if (empty($email) || empty($password)) {
//         echo json_encode(['success' => false, 'message' => 'Email và mật khẩu là bắt buộc.']);
//         exit;
//     }

//     // Kiểm tra định dạng email
//     if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
//         echo json_encode(['success' => false, 'message' => 'Email không hợp lệ.']);
//         exit;
//     }

//     // Gọi hàm login
//     $response = $auth->login($email, $password);

//     // Kiểm tra kết quả trả về từ hàm login
//     if (!$response['success']) {
//         echo json_encode(['success' => false, 'message' => "Đăng nhập không thành công."]);
//     } else {
//         $userModel->userActive($response['user']['id']);
//         // Nếu đăng nhập thành công, trả về thông tin người dùng
//         echo json_encode($response);
//     }
// } else {
//     // Nếu không phải là yêu cầu POST
//     echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ.']);
// }


session_start(); // Bắt đầu session

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../../source/models/Auth.php';
require_once '../../source/models/UserModel.php';

$auth = new Auth();
$userModel = new UserModel();

// Kiểm tra yêu cầu POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ body
    $data = json_decode(file_get_contents('php://input'), true);
    $email = isset($data['email']) ? $data['email'] : null;
    $password = isset($data['password']) ? $data['password'] : null;

    // Kiểm tra xem email và mật khẩu có được cung cấp không
    if (empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Email và mật khẩu là bắt buộc.']);
        exit;
    }

    // Kiểm tra định dạng email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Email không hợp lệ.']);
        exit;
    }

    // Gọi hàm login
    $response = $auth->login($email, $password);

    // Kiểm tra kết quả trả về từ hàm login
    if (!$response['success']) {
        echo json_encode(['success' => false, 'message' => 'Đăng nhập không thành công.']);
    } else {
        // Thiết lập session nếu đăng nhập thành công
        $_SESSION['user_id'] = $response['user']['id'];
        $_SESSION['logged_in'] = true;

        // Đánh dấu người dùng là đang hoạt động
        $userModel->userActive($response['user']['id']);

        // Nếu đăng nhập thành công, trả về thông tin người dùng
        echo json_encode(['success' => true, 'user' => $response['user']]);
    }
} else {
    // Nếu không phải là yêu cầu POST
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ.']);
}
