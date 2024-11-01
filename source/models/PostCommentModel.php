<?php
require_once 'BaseModel.php';
class PostCommentModel extends BaseModel
{
    protected function getTable()
    {
        return 'post_comments';
    }

    // Thêm bình luận mới
    public function createComment($postId, $userCmtId, $content, $order)
    {
        $sql = "INSERT INTO " . $this->getTable() . " (post_id, user_cmt_id, content, `order`, created_at, updated_at) 
                VALUES (:post_id, :user_cmt_id, :content, :order, NOW(), NOW())";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':post_id', $postId);
        $stmt->bindParam(':user_cmt_id', $userCmtId);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':order', $order);

        return $stmt->execute();
    }

    // Cập nhật bình luận
    public function updateComment($id, $content)
    {
        $sql = "UPDATE " . $this->getTable() . " SET content = :content, updated_at = NOW() WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    // Xóa bình luận
    public function deleteComment($id)
    {
        $sql = "DELETE FROM " . $this->getTable() . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    // Lấy tất cả bình luận của một bài viết
    public function getCommentsByPostId($postId)
    {
        $sql = "SELECT * FROM " . $this->getTable() . " WHERE post_id = :post_id ORDER BY `order` ASC, created_at ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':post_id', $postId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy bình luận theo ID
    public function getCommentById($id)
    {
        $sql = "SELECT * FROM " . $this->getTable() . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
