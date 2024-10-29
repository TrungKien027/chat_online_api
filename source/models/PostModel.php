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
    public function getPostsByUserId($userId)
    {
        // $sql = "SELECT * FROM `posts` INNER JOIN users ON users.id = posts.user_id   WHERE user_id = :id";
        $sql = "SELECT 
    posts.id AS post_id, 
    posts.content AS post_content, 
    posts.created_at AS post_created_at,
    users.name , 
    'original' AS post_type,
    media.url   
FROM 
    posts 
INNER JOIN 
    users ON users.id = posts.user_id 
LEFT JOIN 
    media ON media.post_id = posts.id AND media.is_avatar = 0
WHERE 
    posts.user_id = :id 

UNION ALL

SELECT 
    posts.id AS post_id, 
    posts.content AS post_content, 
    post_share.created_at AS post_created_at,
    users.name AS user_name, 
    'shared' AS post_type,
    media.url AS post_image_url 
FROM 
    post_share 
INNER JOIN 
    posts ON post_share.post_id = posts.id 
INNER JOIN 
    users ON users.id = posts.user_id 
LEFT JOIN 
    media ON media.post_id = posts.id AND media.is_avatar = 0  
WHERE 
    post_share.user_share_id = :id;
;  -- Thay thế 4 bằng user_id bạn muốn tìm

";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function searchPost($keyword, $offset, $limit, $currentUserId, $postFrom, $selectedOrder)
{
    try {
        $sql = "SELECT 
            avatar_media.url AS urluser, 
            avatar_post.url AS urlpost, 
            users.name, 
            posts.content, 
            posts.created_at, 
            COUNT(post_like.post_id) AS like_count, 
            COUNT(post_comments.post_id) AS cmt_count, 
            COUNT(post_share.post_id) AS share_count
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        LEFT JOIN media AS avatar_media ON avatar_media.user_id = users.id AND avatar_media.is_avatar = 1 
        LEFT JOIN media AS avatar_post ON avatar_post.post_id = posts.id AND avatar_post.is_avatar = 0 
        LEFT JOIN post_like ON post_like.post_id = posts.id
        LEFT JOIN post_comments ON post_comments.post_id = posts.id
        LEFT JOIN post_share ON post_share.post_id = posts.id
        WHERE (posts.content LIKE :keyword 
               OR MATCH(posts.content) AGAINST (:keyword IN BOOLEAN MODE))";

        // Thêm các điều kiện lọc theo `postFrom`
        if ($postFrom == 1) {
            // Lọc bài viết từ bạn bè
            $sql .= " AND posts.user_id IN (SELECT friend_id FROM friendships WHERE user_id = :currentUserId AND status = 'accepted') ";
        } elseif ($postFrom == 2) {
            // Lọc bài viết từ người khác, không bao gồm bài viết của chính user
            $sql .= " AND posts.user_id NOT IN (
                SELECT friend_id FROM friendships 
                WHERE user_id = :currentUserId AND status = 'accepted'
            ) 
            AND posts.user_id != :currentUserId ";
        }

        $sql .= " GROUP BY posts.id, avatar_media.url, avatar_post.url, users.name, posts.content, posts.created_at ";
        
        // Thêm điều kiện sắp xếp dựa vào `selectedOrder`
        if ($selectedOrder == 1) {
            $sql .= " ORDER BY posts.created_at DESC"; // Sắp xếp theo thời gian mới nhất
        } elseif ($selectedOrder == 2) {
            $sql .= " ORDER BY posts.created_at ASC"; // Sắp xếp theo thời gian cũ nhất
        }

        $sql .= " LIMIT :offset, :limit";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);

        if ($postFrom == 1 || $postFrom == 2) {
            $stmt->bindValue(':currentUserId', $currentUserId, PDO::PARAM_INT);
        }

        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return [];
    }
}

   
    public function getPostsByUserIdInfo($userId)
    {
        // $sql = "SELECT * FROM `posts` INNER JOIN users ON users.id = posts.user_id   WHERE user_id = :id";
        $sql = "SELECT posts.id, 
           posts.content, 
           users.name, 
           posts.created_at, 
           media.url
    FROM posts 
    INNER JOIN users ON users.id = posts.user_id
    LEFT JOIN post_like ON post_like.post_id = posts.id
    LEFT JOIN post_comments ON post_comments.post_id = posts.id
    LEFT JOIN post_share ON post_share.post_id = posts.id
    LEFT JOIN media ON media.post_id = posts.id AND media.is_avatar = 0
    WHERE posts.user_id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
