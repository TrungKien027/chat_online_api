<?php

class MediaModel extends BaseModel
{

    // Trả về tên bảng cho model này
    protected function getTable()
    {
        return 'media';
    }
    // Thêm thông tin media
    public function createMedia(array $data)
    {
        $sql = "INSERT INTO media (post_id, user_id, url, is_avatar, media_type, created_at) 
                VALUES (:post_id, :user_id, :url, :is_avatar, :media_type, NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':post_id', $data['post_id']);
        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':url', $data['url']);
        $stmt->bindParam(':is_avatar', $data['is_avatar']);
        $stmt->bindParam(':media_type', $data['media_type']);
        return $stmt->execute();
    }

    // Cập nhật thông tin media
    public function updateMedia($id, array $data)
    {
        $fields = [];
        $params = [':id' => $id]; // Khởi tạo mảng chứa ID
        // Kiểm tra và thêm các trường vào mảng $fields
        if (isset($data['post_id'])) {
            $fields[] = 'post_id = :post_id';
            $params[':post_id'] = $data['post_id'];
        }
        if (isset($data['user_id'])) {
            $fields[] = 'user_id = :user_id';
            $params[':user_id'] = $data['user_id'];
        }
        if (isset($data['url'])) {
            $fields[] = 'url = :url';
            $params[':url'] = $data['url'];
        }
        if (isset($data['is_avatar'])) {
            $fields[] = 'is_avatar = :is_avatar';
            $params[':is_avatar'] = $data['is_avatar'];
        }
        if (isset($data['media_type'])) {
            $fields[] = 'media_type = :media_type';
            $params[':media_type'] = $data['media_type'];
        }
        $sql = "UPDATE media SET " . implode(', ', $fields) . ", created_at = NOW() WHERE id = :id";
        $stmt = $this->conn->prepare($sql);

        // Gán giá trị cho các tham số
        foreach ($params as $key => $value) {
            $stmt->bindParam($key, $params[$key]);
        }

        return $stmt->execute();
    }

    // Xóa media
    public function deleteMedia($id)
    {
        $sql = "DELETE FROM media WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Lấy thông tin media theo ID
    public function getMediaById($id)
    {
        $sql = "SELECT * FROM media WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy tất cả media theo post_id
    public function getMediaByPostId($post_id)
    {
        $sql = "SELECT * FROM media WHERE post_id = :post_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':post_id', $post_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
