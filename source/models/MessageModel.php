<?php

require_once 'BaseModel.php'; // Đảm bảo đã bao gồm file BaseModel


class MessageModel extends BaseModel
{
    protected function getTable()
    {
        return 'messages';
    }

   

    // Các phương thức khác của lớp MessageModel...

    // Thêm tin nhắn mới
    public function createMessage($roomId, $userId, $content)
    {

        // Chuẩn bị câu truy vấn SQL
        $sql = "INSERT INTO " . $this->getTable() . " (room_id, user_id, content, created_at) 
                VALUES (:room_id, :user_id, :content, NOW())";

        $stmt = $this->conn->prepare($sql);

        // Ràng buộc các tham số
        $stmt->bindParam(':room_id', $roomId);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':content', $content);


        // Trả về ID của tin nhắn mới được tạo
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

    public function getMessagesByRoomId($roomId)
    {
        $sql = "SELECT 
                    m.*, 
                    u.name AS user_name, 
                    u.status AS user_status, 
                    media.url AS user_avatar 
                FROM 
                    " . $this->getTable() . " AS m
                JOIN 
                    users AS u ON m.user_id = u.id
                LEFT JOIN 
                    media ON m.user_id = media.user_id AND media.is_avatar = 1
                WHERE 
                    m.room_id = :room_id
                ORDER BY 
                    m.created_at ASC";

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
