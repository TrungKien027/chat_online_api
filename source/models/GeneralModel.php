<?php
require_once 'BaseModel.php'; // Đảm bảo đã bao gồm file BaseModel
require_once 'TokenModel.php'; // Đảm bảo đã bao gồm file BaseModel
class GeneralModel extends BaseModel
{
    protected function getTable()
    {
        return 'posts'; // Tên bảng
    }
    private $tokenModel;
    public function __construct()
    {
        parent::__construct(); // Gọi đến constructor của lớp cha
        $this->tokenModel = new TokenModel(); // Khởi tạo TokenModel
    }
    function getUserPosts()
    {
        // Câu lệnh SQL để lấy dữ liệu gộp từ bảng users và posts
        $sql = "
            SELECT *
            FROM users
            INNER JOIN posts ON users.id = posts.user_id
            ORDER BY posts.created_at DESC
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        // Lấy tất cả kết quả dưới dạng mảng liên kết
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getPostByIdMerge($id)
    {
        $sql = "SELECT p.*, m.url , u.name
            FROM 
            posts as p
            LEFT JOIN 
            media as m ON m.post_id = p.id
            LEFT JOIN 
            users as u ON u.id = p.user_id
            WHERE p.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Ràng buộc tham số :id
        $stmt->execute();
        return $stmt->fetch();
    }
    public function getUpdatePost($post_id)
    {
        $sql = "SELECT p.*, 
                COUNT(DISTINCT pl.id) AS like_count, 
                COUNT(DISTINCT pc.id) AS comment_count,
                COUNT(DISTINCT ps.id) AS share_count
                FROM posts as p
                LEFT JOIN post_like as pl ON p.id = pl.post_id
                LEFT JOIN post_comments as pc ON p.id = pc.post_id
                LEFT JOIN post_share as ps ON p.id = ps.post_id
                WHERE p.id = :post_id
                GROUP BY p.id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Dùng fetch để lấy một dòng
    }
    function getCommentPostId($post_id)
    {
        $sql = "SELECT 
                    pc.id AS comment_id,
                    pc.post_id,
                    pc.user_cmt_id,
                    pc.content AS comment_content,
                    pc.order AS comment_order,
                    pc.created_at AS comment_created_at,
                    pc.updated_at AS comment_updated_at,
                    pcr.id AS reply_id,
                    pcr.user_id AS reply_user_id,
                    pcr.content AS reply_content,
                    pcr.order AS reply_order,
                    pcr.created_at AS reply_created_at,
                    pcr.updated_at AS reply_updated_at,
                    m.url AS avatar_user 
                FROM 
                    post_comments pc
                LEFT JOIN 
                    post_comment_rep pcr ON pc.id = pcr.cmt_id
                LEFT JOIN 
                    media m ON pc.user_cmt_id = m.user_id
                WHERE 
                    pc.post_id = ?
                ORDER BY 
                    pc.order, pcr.order";
    
        // Chuẩn bị và thực thi câu lệnh SQL
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$post_id]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Tổ chức dữ liệu thành cấu trúc lồng nhau
        $comments = [];
        foreach ($results as $row) {
            $commentId = $row['comment_id'];
    
            // Nếu bình luận chưa được thêm vào danh sách thì thêm vào
            if (!isset($comments[$commentId])) {
                $comments[$commentId] = [
                    'id' => $commentId,
                    'post_id' => $row['post_id'],
                    'user_cmt_id' => $row['user_cmt_id'],
                    'content' => $row['comment_content'],
                    'order' => $row['comment_order'],
                    'url' => $row['avatar_user'], // Đảm bảo sử dụng trường đúng
                    'created_at' => $row['comment_created_at'],
                    'updated_at' => $row['comment_updated_at'],
                    'replies' => [] // Khởi tạo mảng cho các phản hồi
                ];
            }
    
            // Nếu có phản hồi, thêm vào mảng phản hồi
            if ($row['reply_id']) {
                $comments[$commentId]['replies'][] = [
                    'id' => $row['reply_id'],
                    'user_id' => $row['reply_user_id'],
                    'content' => $row['reply_content'],
                    'order' => $row['reply_order'],
                    'url' => $row['avatar_user'], // Đảm bảo sử dụng trường đúng
                    'created_at' => $row['reply_created_at'],
                    'updated_at' => $row['reply_updated_at'],
                ];
            }
        }
    
        // Chuyển đổi lại thành danh sách để dễ xử lý
        return array_values($comments); // Trả về danh sách bình luận
    }
    
    
}
