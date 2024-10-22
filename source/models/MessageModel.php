<?php

class MessageModel extends BaseModel
{
    protected function getTable()
    {
        return 'messages';
    }

    // Thêm tin nhắn mới
    public function createMessage($roomId, $userId, $content)
    {
        $sql = "INSERT INTO " . $this->getTable() . " (room_id, user_id, content, created_at) 
                VALUES (:room_id, :user_id, :content, NOW())";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':room_id', $roomId);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':content', $content);

        return $stmt->execute();
    }

    // Cập nhật tin nhắn (nếu cần)
    public function updateMessage($id, $content)
    {
        $sql = "UPDATE " . $this->getTable() . " SET content = :content, created_at = NOW() WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    // Xóa tin nhắn
    public function deleteMessage($id)
    {
        $sql = "DELETE FROM " . $this->getTable() . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    // Lấy tất cả tin nhắn trong một phòng
    public function getMessagesByRoomId($roomId)
    {
        // $sql = "SELECT * FROM " . $this->getTable() . " WHERE room_id = :room_id ORDER BY created_at ASC";
        $sql = "SELECT m.*, u.name AS user_name, u.status AS user_status 
        FROM " . $this->getTable() . " AS m
        JOIN users AS u ON m.user_id = u.id
        WHERE m.room_id = :room_id
        ORDER BY m.created_at ASC
    ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':room_id', $roomId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy tin nhắn theo ID
    public function getMessageById($id)
    {
        $sql = "SELECT * FROM " . $this->getTable() . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
