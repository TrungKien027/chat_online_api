<?php
require_once 'BaseModel.php';
class Post extends BaseModel
{
    protected function getTable()
    {
        return 'posts'; // Tên bảng
    }

    public function create(array $data)
    {
        $stmt = $this->conn->prepare("INSERT INTO " . $this->getTable() . " (user_id, content, created_at, updated_at) VALUES (?, ?, NOW(), NOW())");
        $stmt->execute([$data['user_id'], $data['content']]);
        return $this->conn->lastInsertId(); // Trả về ID của bản ghi vừa thêm
    }

    public function read($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->getTable() . " WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function update($id, array $data)
    {
        $stmt = $this->conn->prepare("UPDATE " . $this->getTable() . " SET content = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$data['content'], $id]);
        return $stmt->rowCount(); // Trả về số bản ghi bị ảnh hưởng
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->getTable() . " WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount(); // Trả về số bản ghi bị xóa
    }
    public function getPaginatedUsers($page, $limit)
    {
        $offset = ($page - 1) * $limit; // Tính toán offset dựa trên số trang và giới hạn
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->getTable() . " LIMIT :limit OFFSET :offset");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAll()
    {
        try {
            $sql = "SELECT * FROM " . $this->getTable();
            $stmt = $this->conn->query($sql); // Sử dụng kết nối đã lưu trữ
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Xử lý lỗi
            echo "Error: " . $e->getMessage();
            return []; // Trả về mảng rỗng nếu có lỗi
        }
    }
    //POst id 
    public function getPostsByUserId($userId)
    {
        // $sql = "SELECT * FROM `posts` INNER JOIN users ON users.id = posts.user_id   WHERE user_id = :id";
        $sql = "SELECT  posts.id,  posts.content, users.name, posts.created_at,  media.url,
COUNT(post_like.post_id) AS like_count, 
COUNT(post_comments.post_id) AS
cmt_count,COUNT(post_share.post_id) AS share_count
        FROM posts
        INNER JOIN users ON users.id = posts.user_id
        LEFT JOIN post_like ON post_like.post_id = posts.id
        LEFT JOIN post_comments ON post_comments.post_id = posts.id
        LEFT JOIN post_share ON post_share.post_id = posts.id
        LEFT JOIN media ON media.post_id = posts.id AND media.is_avatar = 0
        WHERE posts.user_id = :id
        GROUP BY posts.id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
