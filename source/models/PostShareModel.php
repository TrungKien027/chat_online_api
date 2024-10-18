<?php

class PostShareModel extends BaseModel
{
    protected function getTable()
    {
        return 'post_share';
    }

    // Thêm chia sẻ bài viết
    public function createPostShare($postId, $userShareId)
    {
        $sql = "INSERT INTO " . $this->getTable() . " (post_id, user_share_id, created_at) 
                VALUES (:post_id, :user_share_id, NOW())";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':post_id', $postId);
        $stmt->bindParam(':user_share_id', $userShareId);

        return $stmt->execute();
    }

    // Xóa chia sẻ bài viết
    public function deletePostShare($id)
    {
        $sql = "DELETE FROM " . $this->getTable() . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    // Lấy tất cả chia sẻ của một bài viết
    public function getSharesByPostId($postId)
    {
        $sql = "SELECT * FROM " . $this->getTable() . " WHERE post_id = :post_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':post_id', $postId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy tất cả bài viết đã được chia sẻ bởi một người dùng
    public function getPostsSharedByUser($userShareId)
    {
        $sql = "SELECT * FROM " . $this->getTable() . " WHERE user_share_id = :user_share_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_share_id', $userShareId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
