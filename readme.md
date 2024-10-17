api-root/
│
├── config/                         # Thư mục chứa cấu hình
│   └── database.php                # Cấu hình kết nối cơ sở dữ liệu
│
├── public/                         # Thư mục công khai (public)
│   └── index.php                   # Điểm vào chính của ứng dụng (API routing)
│
├── src/                            # Thư mục chính chứa toàn bộ logic ứng dụng
│   ├── Controllers/                # Thư mục chứa các controller cho API
│   │   └── UserController.php      # Controller xử lý các yêu cầu liên quan đến users
│   │   └── PostController.php      # Controller xử lý các yêu cầu liên quan đến posts
│   │
│   ├── Models/                     # Thư mục chứa các model
│   │   ├── BaseModel.php           # Abstract class chứa logic chung cho các model
│   │   ├── User.php                # Model cho bảng users
│   │   ├── Post.php                # Model cho bảng posts
│   │
│   ├── Interfaces/                 # Thư mục chứa các interface
│   │   └── ModelInterface.php      # Interface cho các phương thức chung của các model
│   │
│   └── Middleware/                 # Thư mục chứa các middleware (như xác thực người dùng, kiểm tra API token)
│
├── routes/                         # Thư mục chứa file định nghĩa các route cho API
│   └── api.php                     # File định nghĩa các route API
│
├── tests/                          # Thư mục chứa các file test (nếu có)
│
└── vendor/                         # Composer thư viện (nếu sử dụng Composer)
