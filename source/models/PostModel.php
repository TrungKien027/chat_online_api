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
        return $this->conn->lastInsertId();
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
        $sql = "SELECT * FROM " . $this->getTable();
        $stmt = $this->conn->query($sql); // Sử dụng kết nối đã lưu trữ
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    //POst id 
    public function sByUserId($userId)
    {
        // $sql = "SELECT * FROM `posts` INNER JOIN users ON users.id = posts.user_id   WHERE user_id = :id";
        $sql = "(SELECT posts.id, posts.content, users.name, posts.created_at, media.url,
        COUNT(post_like.post_id) AS like_count, 
        COUNT(post_comments.post_id) AS cmt_count,
        COUNT(post_share.post_id) AS share_count
 FROM posts
 INNER JOIN users ON users.id = posts.user_id
 LEFT JOIN post_like ON post_like.post_id = posts.id
 LEFT JOIN post_comments ON post_comments.post_id = posts.id
 LEFT JOIN post_share ON post_share.post_id = posts.id
 LEFT JOIN media ON media.post_id = posts.id AND media.is_avatar = 0
 WHERE posts.user_id = :id
 GROUP BY posts.id)

UNION

(SELECT original_posts.id, original_posts.content, original_users.name, original_posts.created_at, media.url,
        COUNT(post_like.post_id) AS like_count, 
        COUNT(post_comments.post_id) AS cmt_count,
        COUNT(post_share.post_id) AS share_count
 FROM post_share
 INNER JOIN posts AS original_posts ON post_share.post_id = original_posts.id
 INNER JOIN users AS original_users ON original_users.id = original_posts.user_id
 LEFT JOIN post_like ON post_like.post_id = original_posts.id
 LEFT JOIN post_comments ON post_comments.post_id = original_posts.id
 LEFT JOIN media ON media.post_id = original_posts.id AND media.is_avatar = 0
 WHERE post_share.user_share_id = :id
 GROUP BY original_posts.id)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function searchPost($keyword, $offset, $limit)
    {
        try {
            $sql = "SELECT  avatar_media.url as urluser, avatar_post.url as urlpost,users.name, posts.content FROM posts  
JOIN users ON posts.user_id = users.id 
LEFT JOIN media AS avatar_media ON avatar_media.user_id = users.id AND avatar_media.is_avatar = 1 
LEFT JOIN media AS avatar_post ON avatar_post.post_id = posts.id AND avatar_post.is_avatar = 0
WHERE content LIKE :keyword LIMIT :offset, :limit ";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
    public function getPaginatedPosts($lastPostId, $limit)
    {
        $limit = max(1, (int)$limit);
        $lastPostId = (int)$lastPostId; // ID bài viết cuối cùng
        try {
            $stmt = $this->conn->prepare(
                "SELECT 
    p.*,                      
    u.name AS name,               
    u.email AS email,               
    m.url AS media_url,         
    m.media_type,               
    COUNT(DISTINCT pc.id) AS total_comments,  
    COUNT(DISTINCT pl.id) AS total_likes,      
    COUNT(DISTINCT ps.id) AS total_shares       
FROM 
    posts p                   
JOIN 
    users u ON p.user_id = u.id   
LEFT JOIN 
    media m ON p.id = m.post_id  
LEFT JOIN 
    post_comments pc ON p.id = pc.post_id   
LEFT JOIN 
    post_like pl ON p.id = pl.post_id       
LEFT JOIN 
    post_share ps ON p.id = ps.post_id      
WHERE 
    p.id > :lastPostId            
GROUP BY 
    p.id                             
ORDER BY 
    p.id ASC                       
LIMIT 
    :limit                        
                      
"
            );
            $stmt->bindParam(':lastPostId', $lastPostId, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
