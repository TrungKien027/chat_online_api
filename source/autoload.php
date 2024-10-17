<?php 

// Hàm autoload
function AutoloaderFile($className) {
    $file = __DIR__ . '/classes/' . $className . '.php'; // Đường dẫn đến thư mục chứa class

    // Kiểm tra xem file có tồn tại không
    if (file_exists($file)) {
        require_once $file;
    } else {
        // Thông báo lỗi nếu không tìm thấy file
        throw new Exception("Unable to load class: $className");
    }
}

// Đăng ký hàm autoload
// spl_autoload_register('AutoloaderFile');
