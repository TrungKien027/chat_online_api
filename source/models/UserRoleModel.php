<?php

class UserRoleModel extends BaseModel
{
    protected function getTable()
    {
        return 'user_roles';
    }

    // Thêm vai trò cho người dùng
    public function createUserRole($userId, $roleId)
    {
        $sql = "INSERT INTO " . $this->getTable() . " (user_id, role_id) VALUES (:user_id, :role_id)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':role_id', $roleId);

        return $stmt->execute();
    }

    // Xóa vai trò của người dùng
    public function deleteUserRole($userId, $roleId)
    {
        $sql = "DELETE FROM " . $this->getTable() . " WHERE user_id = :user_id AND role_id = :role_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':role_id', $roleId);

        return $stmt->execute();
    }

    // Lấy tất cả vai trò của một người dùng
    public function getRolesByUserId($userId)
    {
        $sql = "SELECT role_id FROM " . $this->getTable() . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Kiểm tra vai trò của người dùng
    public function hasRole($userId, $roleId)
    {
        $sql = "SELECT COUNT(*) FROM " . $this->getTable() . " WHERE user_id = :user_id AND role_id = :role_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':role_id', $roleId);
        $stmt->execute();

        return $stmt->fetchColumn() > 0; // Trả về true nếu người dùng có vai trò này
    }
}
