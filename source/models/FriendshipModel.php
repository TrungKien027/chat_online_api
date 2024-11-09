<?php

class FriendshipModel extends BaseModel
{
    protected function getTable()
    {
        return 'friendships';
    }

    // Thêm mối quan hệ bạn bè
    public function createFriendship($userId, $friendId, $status = 'pending')
    {
        $sql = "INSERT INTO " . $this->getTable() . " (user_id, friend_id, status, created_at) 
                VALUES (:user_id, :friend_id, :status, NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':friend_id', $friendId);
        $stmt->bindParam(':status', $status);

        return $stmt->execute();
    }

    // Cập nhật trạng thái mối quan hệ bạn bè
    public function updateFriendship($id, $status)
    {
        $sql = "UPDATE " . $this->getTable() . " SET status = :status, created_at = NOW() WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Xóa mối quan hệ bạn bè
    public function deleteFriendship($id)
    {
        $sql = "DELETE FROM " . $this->getTable() . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Lấy tất cả bạn bè của một người dùng
    public function getFriendsByUserId($userId)
    {
        $sql = "SELECT * FROM " . $this->getTable() . " WHERE user_id = :user_id AND status = 'accepted'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy tất cả yêu cầu kết bạn của một người dùng
    public function getPendingFriendRequests($userId)
    {
        $sql = "SELECT * FROM " . $this->getTable() . " WHERE friend_id = :user_id AND status = 'pending'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Kiểm tra mối quan hệ bạn bè
    public function checkFriendship($userId, $friendId)
    {
        $sql = "SELECT * FROM " . $this->getTable() . " WHERE (user_id = :user_id AND friend_id = :friend_id) 
                OR (user_id = :friend_id AND friend_id = :user_id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':friend_id', $friendId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    function blockUser($user_id, $friend_id)
    {
        // Kiểm tra quan hệ bạn bè hiện có
        $query = "SELECT * FROM friendships WHERE user_id = ? AND friend_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$user_id, $friend_id]);

        if ($stmt->rowCount() > 0) {
            // Nếu quan hệ tồn tại, cập nhật trạng thái thành 'blocked'
            $updateQuery = "UPDATE friendships SET status = 'blocked' WHERE user_id = ? AND friend_id = ?";
            $updateStmt = $this->conn->prepare($updateQuery);
            $updateStmt->execute([$user_id, $friend_id]);
            return ['success' =>  true, 'message' => 'blocked', 'state' => 'update'];
        } else {
            // Nếu chưa có quan hệ, tạo mới với trạng thái 'blocked'
            $insertQuery = "INSERT INTO friendships (user_id, friend_id, status) VALUES (?, ?, 'blocked')";
            $insertStmt = $this->conn->prepare($insertQuery);
            $insertStmt->execute([$user_id, $friend_id]);
            return ['success' =>  true, 'message' => 'blocked friend', 'state' => 'create'];
        }
    }
}
