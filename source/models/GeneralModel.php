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
}
