<?php

class ChatRoomModel extends BaseModel
{
    protected function getTable()
    {
        return 'chat_rooms';
    }

    // Tạo phòng chat
    public function createChatRoom($userId1, $userId2)
    {
        $sql = "INSERT INTO " . $this->getTable() . " (user_id_1, user_id_2, created_at) 
                VALUES (:user_id_1, :user_id_2, NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id_1', $userId1);
        $stmt->bindParam(':user_id_2', $userId2);
        return $stmt->execute();
    }

    // Xóa phòng chat
    public function deleteChatRoom($id)
    {
        $sql = "DELETE FROM " . $this->getTable() . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Lấy phòng chat giữa hai người dùng
    public function getChatRoom($userId1, $userId2)
    {
        $sql = "SELECT * FROM " . $this->getTable() . " 
                WHERE (user_id_1 = :user_id_1 AND user_id_2 = :user_id_2) 
                OR (user_id_1 = :user_id_2 AND user_id_2 = :user_id_1)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id_1', $userId1);
        $stmt->bindParam(':user_id_2', $userId2);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy tất cả các phòng chat của một người dùng
    public function getChatRoomsByUserId($userId)
    {
        $sql = "SELECT * FROM " . $this->getTable() . " 
                WHERE user_id_1 = :user_id OR user_id_2 = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
