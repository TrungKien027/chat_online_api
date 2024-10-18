<?php

class NotificationModel extends BaseModel
{
    protected function getTable()
    {
        return 'notifications';
    }

    // Thêm thông báo mới
    public function createNotification($userId, $content, $type)
    {
        $sql = "INSERT INTO " . $this->getTable() . " (user_id, content, type, is_read, created_at) 
                VALUES (:user_id, :content, :type, 0, NOW())";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':type', $type);

        return $stmt->execute();
    }

    // Cập nhật trạng thái đã đọc của thông báo
    public function markAsRead($id)
    {
        $sql = "UPDATE " . $this->getTable() . " SET is_read = 1 WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    // Xóa thông báo
    public function deleteNotification($id)
    {
        $sql = "DELETE FROM " . $this->getTable() . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    // Lấy tất cả thông báo của một người dùng
    public function getNotificationsByUserId($userId)
    {
        $sql = "SELECT * FROM " . $this->getTable() . " WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy thông báo theo ID
    public function getNotificationById($id)
    {
        $sql = "SELECT * FROM " . $this->getTable() . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
