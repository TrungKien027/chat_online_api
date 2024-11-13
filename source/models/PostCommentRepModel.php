<?php
require_once 'BaseModel.php';   
class PostCommentRepModel extends BaseModel
{
    protected function getTable()
    {
        return 'post_comment_rep';
    }

    // Thêm phản hồi cho bình luận
    public function createCommentRep($cmtId, $userId, $content, $order)
    {
        $sql = "INSERT INTO " . $this->getTable() . " (cmt_id, user_id, content, `order`, created_at, updated_at) 
                VALUES (:cmt_id, :user_id, :content, :order, NOW(), NOW())";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':cmt_id', $cmtId);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':order', $order);

        return $stmt->execute();
    }

    // Cập nhật phản hồi
    public function updateCommentRep($id, $content)
    {
        $sql = "UPDATE " . $this->getTable() . " SET content = :content, updated_at = NOW() WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    // Xóa phản hồi
    public function deleteCommentRep($id)
    {
        $sql = "DELETE FROM " . $this->getTable() . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    // Lấy tất cả phản hồi của một bình luận
    public function getRepsByCommentId($cmtId)
    {
        $sql = "SELECT * FROM " . $this->getTable() . " WHERE cmt_id = :cmt_id ORDER BY `order`";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':cmt_id', $cmtId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
