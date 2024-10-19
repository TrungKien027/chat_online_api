<?php
require_once 'BaseModel.php'; // Đảm bảo đã bao gồm file BaseModel
require_once 'TokenModel.php'; // Đảm bảo đã bao gồm file BaseModel
class UserModel extends BaseModel
{
    private $tokenModel;
    public function __construct()
    {
        parent::__construct(); // Gọi đến constructor của lớp cha
        $this->tokenModel = new TokenModel(); // Khởi tạo TokenModel
    }
    public function verifyPassword($inputPassword, $storedPassword)
    {
        // Giải mã hóa mật khẩu base 64
        // Mã hóa mật khẩu đầu vào với khóa bí mật
        $hashedInputPassword = $this->hashPasswordWithBase64($inputPassword);
        // So sánh với mật khẩu đã lưu
        return hash_equals($hashedInputPassword, $storedPassword);
    }
    private function hashPasswordWithBase64($password)
    {
        // Kết hợp mật khẩu với secret key
        $combined = $password . SECRET_KEY;
        // Mã hóa mật khẩu và chuyển đổi sang Base64
        return base64_encode(hash('sha256', $combined, true));
    }
    // Phương thức tạo người dùng
    public function createUser(array $data)
    {
        // Mã hóa mật khẩu
        $passwordHash = $this->hashPasswordWithBase64($data['password']);
        $sql = "INSERT INTO users (email, password, status, name, created_at) 
                VALUES (:email, :password, :status, :name, NOW())";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':email' => $data['email'],
            ':password' => $passwordHash,
            ':status' => 0, // Giả sử trạng thái mặc định là 1 (hoạt động)
            ':name' => htmlspecialchars($data['name']) // Xử lý HTML để ngăn chặn XSS
        ]);
        $userId = $this->conn->lastInsertId(); // Lấy ID người dùng vừa tạo
        // Tạo token cho người dùng
        $token = $this->tokenModel->createToken($userId);
        return [
            'success' => true,
            'user_id' => $userId,
            'token' => $token // Trả về token vừa tạo
        ];
    }
    public function updateUser($id, array $data)
    {
        $user = $this->getUserById($id); // Lấy người dùng theo ID

        // Tạo mảng để lưu các phần cần cập nhật
        $fieldsToUpdate = [];
        $params = [':id' => $id];

        // Cập nhật tên người dùng nếu có
        if (!empty($data['name'])) {
            $fieldsToUpdate[] = 'name = :name';
            $params[':name'] = htmlspecialchars($data['name']); // Xử lý HTML để ngăn chặn XSS
        }

        // Cập nhật email nếu có
        if (!empty($data['email'])) {
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                return ['success' => false, 'message' => 'Email không hợp lệ.'];
            }
            $fieldsToUpdate[] = 'email = :email';
            $params[':email'] = $data['email'];
        }

        // Cập nhật mật khẩu nếu có
        if (!empty($data['password'])) {
            $passwordHash = $this->hashPasswordWithBase64($data['password']);
            $fieldsToUpdate[] = 'password = :password';
            $params[':password'] = $passwordHash;
        }

        // Cập nhật trạng thái nếu có
        if (isset($data['status'])) {
            $fieldsToUpdate[] = 'status = :status';
            $params[':status'] = $data['status'];
        }

        // Nếu không có trường nào để cập nhật, trả về thông báo
        if (empty($fieldsToUpdate)) {
            return ['success' => false, 'message' => 'Không có thông tin nào để cập nhật.'];
        }

        // Tạo chuỗi câu lệnh SQL
        $sql = "UPDATE " . $this->getTable() . " SET " . implode(', ', $fieldsToUpdate) . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return [
            'success' => true,
            'user' => [
                'id' => $id,
                'name' => $data['name'] ?? $user['name'], // Trả về tên cũ nếu không có tên mới
                'email' => $data['email'] ?? $user['email'], // Trả về email cũ nếu không có email mới
            ],
        ];
    }
    // Triển khai phương thức read để đọc dữ liệu từ bảng users
    public function getUserById($id)
    {
        $sql = "SELECT * FROM " . $this->getTable() . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserInfoByUserId($id)
    {
        $sql = "SELECT * FROM user_info WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':user_id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserWithInfo($user_id)
    {
        $sql = "
            SELECT u.id, u.email, u.name, u.status, 
                   ui.age, ui.gender, ui.phone, ui.created_at AS user_info_created_at, ui.updated_at 
            FROM users u
            LEFT JOIN user_info ui ON u.id = ui.user_id
            WHERE u.id = :user_id
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Triển khai phương thức delete để xóa dữ liệu trong bảng users
    public function deleteUser($id)
    {
        $sql = "DELETE FROM " . $this->getTable() . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return true;
    }
    // Phương thức tìm kiếm người dùng theo email
    public function getUserByEmail($email)
    {
        $sql = "SELECT * FROM " . $this->getTable() . " WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Trả về người dùng tìm thấy

    }


    // Tìm kiếm người dùng phân trang
    public function searchUsers($keyword, $offset, $limit)
    {
        $sql = "SELECT * FROM users WHERE name LIKE :keyword LIMIT :offset, :limit";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getFriendship($user_id)
    {
        $sql = "
        SELECT u.id, u.name, u.email, u.status, f.status AS friendship_status
        FROM friendships f
        JOIN users u ON (f.friend_id = u.id OR f.user_id = u.id) 
        WHERE (f.user_id = :user_id OR f.friend_id = :user_id) 
          AND u.id != :user_id
          AND f.status = 'accepted'
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Trả về tên bảng cho model này
    protected function getTable()
    {
        return 'users';
    }

    public function updateUserInfo1($user_id, $name, $age, $gender, $phone)
    {
        // Cập nhật thông tin trong bảng users
        $sqlUpdateUser = "
            UPDATE users
            SET name = :name
            WHERE id = :user_id
        ";

        $stmtUser = $this->conn->prepare($sqlUpdateUser);
        $stmtUser->bindParam(':name', $name);
        $stmtUser->bindParam(':user_id', $user_id);
        $stmtUser->execute();

        // Cập nhật thông tin trong bảng user_info
        $sqlUpdateUserInfo = "
            UPDATE user_info
            SET age = :age, gender = :gender, phone = :phone
            WHERE user_id = :user_id
        ";

        $stmtUserInfo = $this->conn->prepare($sqlUpdateUserInfo);
        $stmtUserInfo->bindParam(':age', $age);
        $stmtUserInfo->bindParam(':gender', $gender);
        $stmtUserInfo->bindParam(':phone', $phone);
        $stmtUserInfo->bindParam(':user_id', $user_id);

        return $stmtUserInfo->execute(); // Trả về kết quả của lần cập nhật thông tin người dùng
    }
}
