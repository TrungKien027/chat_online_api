<?php

class RoleModel extends BaseModel
{
    protected function getTable()
    {
        return 'roles';
    }

    // Thêm vai trò mới
    public function createRole($roleName)
    {
        $sql = "INSERT INTO " . $this->getTable() . " (role_name) VALUES (:role_name)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':role_name', $roleName);

        return $stmt->execute();
    }

    // Cập nhật vai trò
    public function updateRole($id, $roleName)
    {
        $sql = "UPDATE " . $this->getTable() . " SET role_name = :role_name WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':role_name', $roleName);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Xóa vai trò
    public function deleteRole($id)
    {
        $sql = "DELETE FROM " . $this->getTable() . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    // Lấy tất cả vai trò
    public function getAllRoles()
    {
        $sql = "SELECT * FROM " . $this->getTable();
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy thông tin vai trò theo ID
    public function getRoleById($id)
    {
        $sql = "SELECT * FROM " . $this->getTable() . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
