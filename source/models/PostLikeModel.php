<?php
require_once 'BaseModel.php';
class PostLikeModel extends BaseModel
{
    protected function getTable()
    {
        return 'post_like';
    }

    // Thêm thích bài viết
    public function createPostLike($postId, $userLikeId)
    {
        $sql = "INSERT INTO " . $this->getTable() . " (post_id, user_like_id, created_at) 
                VALUES (:post_id, :user_like_id, NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':post_id', $postId);
        $stmt->bindParam(':user_like_id', $userLikeId);
        return $stmt->execute();
    }

    // Xóa thích bài viết
    public function deletePostLike($postId, $userLikeId)
    {
        $sql = "DELETE FROM " . $this->getTable() . " WHERE post_id = :post_id AND user_like_id = :user_like_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':post_id', $postId);
        $stmt->bindParam(':user_like_id', $userLikeId);
        return $stmt->execute();
    }

    // Kiểm tra người dùng đã thích bài viết chưa
    public function isPostLiked($postId, $userLikeId)
    {
        $sql = "SELECT * FROM " . $this->getTable() . " WHERE post_id = :post_id AND user_like_id = :user_like_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':post_id', $postId);
        $stmt->bindParam(':user_like_id', $userLikeId);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy tất cả người dùng đã thích bài viết
    public function getLikesByPostId($postId)
    {
        $sql = "SELECT * FROM " . $this->getTable() . " WHERE post_id = :post_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':post_id', $postId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy tất cả bài viết mà người dùng đã thích
    public function getPostsLikedByUser($userLikeId)
    {
        $sql = "SELECT * FROM " . $this->getTable() . " WHERE user_like_id = :user_like_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_like_id', $userLikeId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getPostLikeCount($postId)
    {
        $sql = "SELECT COUNT(*) AS like_count FROM " . $this->getTable() . " WHERE post_id = :post_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':post_id', $postId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
