<?php 
require_once __DIR__.'/../config/database.php';
require_once __DIR__.'/../interface/ModelInterface.php';

abstract class BaseModel extends Database implements ModelInterface
{
    protected $conn; // Thuộc tính kết nối

    public function __construct() {
        $this->conn = Database::connect(); // Kết nối cơ sở dữ liệu
    }

    // Phương thức trừu tượng, lớp con sẽ phải triển khai
    // abstract public function create(array $data);
    // abstract public function read($id);
    // abstract public function update($id, array $data);
    // abstract public function delete($id);

    // Phương thức chung, có thể sử dụng trong các model con
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

    // Mỗi model con sẽ trả về tên bảng của nó
    abstract protected function getTable();
}
