<?php
require_once 'config.php';

class Database {
    private static $host = HOST; // Địa chỉ máy chủ
    private static $db_name = DB_NAME; // Tên cơ sở dữ liệu
    private static $username =DB_USER; // Tên người dùng
    private static $password = DB_PASSWORD; // Mật khẩu
    private static $conn;

    // Kết nối cơ sở dữ liệu
    public static function connect() {
        if (!self::$conn) {
            try {
                self::$conn = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$db_name, self::$username, self::$password);
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Mặc định fetch kiểu associative array
            } catch (PDOException $e) {
                echo "Connection error: " . $e->getMessage();
            }
        }
        return self::$conn;
    }

    // Ngắt kết nối cơ sở dữ liệu
    public static function disconnect() {
        self::$conn = null;
    }
}
