<?php
require_once 'UserModel.php';
require_once 'TokenModel.php';

class Auth
{
    protected $userModel;
    protected $tokenModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->tokenModel = new TokenModel();
    }
    public function login($email, $password)
    {
        // Bước 1: Kiểm tra xem người dùng có tồn tại không
        $user = $this->userModel->getUserByEmail($email);
        if (!$user) {
            return ['success' => false, 'message' => 'Email không hợp lệ.'];
        }
        // Bước 2: Kiểm tra mật khẩu
        if (!$this->userModel->verifyPassword($password, $user['password'])) {
            return ['success' => false, 'message' => 'Mật khẩu không chính xác.'];
        }
        // Bước 3: Tạo hoặc lấy token cho người dùng
        $token = $this->createOrUpdateToken($user['id']);

        // Bước 4: Trả về thông tin người dùng và token
        return [
            'success' => true,
            'token' => $token,
            'user' => [
                'id' => $user['id'],
                'email' => $user['email'],
                'name' => $user['name'],
            ],
        ];
    }
    private function createOrUpdateToken($userId)
    {
        // Lấy token hiện tại
        $token = $this->tokenModel->getTokenByUserId($userId);

        if ($token) {
            // Nếu có token, có thể muốn vô hiệu hóa token cũ trước khi tạo token mới
            // $this->tokenModel->invalidateToken($token['id']); // Nếu bạn có phương thức này
        } else {
            // Nếu không có token, tạo token mới
            $token = $this->tokenModel->createToken($userId);
        }

        // Nếu bạn cần thiết lập thời gian hết hạn cho token, hãy làm điều đó ở đây
        return $token;
    }
    public function logout($token)
    {
        // Bước 1: Kiểm tra xem token có hợp lệ không
        $tokenData = $this->tokenModel->verifyToken($token);
        if (!$tokenData) {
            return ['success' => false, 'message' => 'Token không hợp lệ.'];
        }
        // Bước 2: Xóa token
        $this->tokenModel->deleteToken($token);
        // Bước 3: Trả về phản hồi thành công
        return ['success' => true, 'message' => 'Đăng xuất thành công.'];
    }
}
