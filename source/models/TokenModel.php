<?php
require_once 'BaseModel.php';
class TokenModel extends BaseModel
{
    // Phương thức tạo token ngẫu nhiên
    private function generateToken()
    {
        return bin2hex(random_bytes(16)); // Tạo token ngẫu nhiên dài 32 ký tự
    }

    // Phương thức thêm token mới cho người dùng
    public function createToken($userId)
    {
        try {
            $token = $this->generateToken();
            $expiredAt = date('Y-m-d H:i:s', strtotime('+1 month')); // Token hết hạn sau 1 tháng

            $sql = "INSERT INTO token (user_id, token, created_at, expired_at) 
                    VALUES (:user_id, :token, NOW(), :expired_at)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':user_id' => $userId,
                ':token' => $token,
                ':expired_at' => $expiredAt
            ]);

            return $token; // Trả về token vừa tạo
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Phương thức kiểm tra token
    public function verifyToken($token)
    {
        try {
            $sql = "SELECT * FROM token WHERE token = :token AND expired_at > NOW()";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':token' => $token]);
            return $stmt->fetch(PDO::FETCH_ASSOC); // Trả về bản ghi token hợp lệ
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }
    // Xóa token (ví dụ sau khi xác thực)
    public function deleteToken($token)
    {
        try {
            $sql = "DELETE FROM token WHERE token = :token";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':token' => $token]);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    // Phương thức lấy token của người dùng
    public function getTokenByUserId($userId)
    {
        try {
            $sql = "SELECT token FROM token WHERE user_id = :user_id AND expired_at > NOW()";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':user_id' => $userId]);

            // Kiểm tra xem có token nào hợp lệ không
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['token'] : null; // Trả về token nếu tìm thấy, ngược lại trả về null
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }
    // Phương thức trả về tên bảng
    protected function getTable()
    {
        return 'token'; // Tên bảng token
    }
}
