angular-root/
│
├── src/
│   ├── app/
│   │   ├── components/                # Chứa các component Angular
│   │   ├── services/                  # Chứa các service để gọi API
│   │   │   └── api.service.ts         # Service dùng để tương tác với PHP API
│   │   ├── models/                    # Chứa các model TypeScript tương ứng với API
│   │   │   └── user.model.ts          # Model TypeScript cho đối tượng User
│   │   └── app.module.ts              # Module chính của Angular
│   │
│   ├── assets/                        # Thư mục chứa tài nguyên tĩnh (hình ảnh, CSS)
│   ├── environments/                  # Chứa các file cấu hình môi trường
│   └── main.ts                        # File khởi động ứng dụng Angular
