<?php

require_once 'BaseModel.php'; // Nhúng file kết nối cơ sở dữ liệu

class UserInfoModel extends BaseModel
{
    protected function getTable()
    {
        return 'user_info';
    }
    public function createUserInfo($user_id, array $data)
    {
        // Gán user_id vào tham số
        $params = [':user_id' => $user_id];
        // Tạo câu lệnh SQL và tham số động
        $fields = [];
        if (isset($data['age'])) {
            $fields[] = 'age = :age';
            $params[':age'] = $data['age'];
        }
        if (isset($data['gender'])) {
            $fields[] = 'gender = :gender';
            $params[':gender'] = $data['gender'];
        }
        if (isset($data['phone'])) {
            $fields[] = 'phone = :phone';
            $params[':phone'] = $data['phone'];
        }

        // Xây dựng câu lệnh SQL
        $sql = "INSERT INTO user_info (user_id, " . implode(', ', array_map(fn($field) => explode(' = ', $field)[0], $fields)) . ", created_at, updated_at) 
                VALUES (:user_id, " . implode(', ', array_map(fn($field) => explode(' = ', $field)[0], $fields)) . ", NOW(), NOW())";

        // Chuẩn bị và thực thi câu lệnh
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindParam($key, $value);
        }
        return $stmt->execute();
    }
    public function createUserInfoDefault($user_id)
{
    // Xây dựng câu lệnh SQL với đầy đủ các cột
    $sql = "INSERT INTO user_info (user_id, created_at, updated_at) VALUES (:user_id, NOW(), NOW())";

    // Chuẩn bị câu lệnh
    $stmt = $this->conn->prepare($sql);

    // Gán giá trị cho tham số
    $stmt->bindParam(':user_id', $user_id);

    // Thực thi câu lệnh và trả về kết quả
    return $stmt->execute();
}

    // Lấy thông tin người dùng theo ID
    public function getUserInfoById($userId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM user_info WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Cập nhật thông tin người dùng
    public function updateUserInfo($userId, array $data)
    {
        $fields = [];
        $params = [':user_id' => $userId];

        // Kiểm tra các trường có trong dữ liệu để cập nhật
        if (isset($data['age'])) {
            $fields[] = 'age = :age';
            $params[':age'] = $data['age'];
        }
        if (isset($data['gender'])) {
            $fields[] = 'gender = :gender';
            $params[':gender'] = $data['gender'];
        }
        if (isset($data['phone'])) {
            $fields[] = 'phone = :phone';
            $params[':phone'] = $data['phone'];
        }

        // Nếu có trường nào được thêm vào, thực hiện cập nhật
        if (!empty($fields)) {
            $sql = "UPDATE user_info SET " . implode(', ', $fields) . ", updated_at = NOW() WHERE user_id = :user_id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute($params);
        }

        return false; // Không có trường nào được cập nhật
    }

    // Xóa thông tin người dùng
    public function deleteUserInfo($userId)
    {
        $stmt = $this->conn->prepare("DELETE FROM user_info WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId);
        return $stmt->execute();
    }
}
