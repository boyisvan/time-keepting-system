# Hệ thống chấm công Timekeeping System PHP & MYSQL - ducvancoder

## Lịch sử ra đời 

Trong thời đại công nghệ 4.0, việc quản lý nhân sự và chấm công trở nên phức tạp hơn bao giờ hết. Các doanh nghiệp vừa và nhỏ thường gặp khó khăn trong việc theo dõi thời gian làm việc của nhân viên, tính toán lương thưởng, và quản lý các yêu cầu nghỉ phép hay làm thêm giờ. 

Dự án "Hệ thống chấm công Timekeeping System" được tôi phát triển với mục tiêu tạo ra một giải pháp toàn diện, dễ sử dụng và có thể tùy chỉnh cho các doanh nghiệp Việt Nam. Hệ thống được xây dựng dựa trên nhu cầu thực tế của các công ty trong việc quản lý nhân sự hiệu quả, minh bạch và công bằng.

## Tổng quan dự án

**Hệ thống chấm công Timekeeping System** là một ứng dụng web được phát triển bằng PHP thuần, cung cấp đầy đủ các chức năng quản lý nhân sự và chấm công cho doanh nghiệp. Hệ thống được thiết kế với kiến trúc MVC (Model-View-Controller) rõ ràng, giao diện thân thiện và dễ sử dụng.

### Thông tin cơ bản
- **Tên dự án**: Hệ thống chấm công Timekeeping System
- **Phiên bản**: 1.0.0
- **Ngôn ngữ**: PHP 7.4+, MySQL 5.7+
- **Giao diện**: HTML5, CSS3, JavaScript, Bootstrap 5
- **Biểu đồ**: ApexCharts
- **Tác giả**: Hoàng Đức Văn (ducvancoder)

## Công nghệ sử dụng

### Backend
- **PHP 7.4+**: Ngôn ngữ lập trình chính
- **MySQL 5.7+**: Hệ quản trị cơ sở dữ liệu
- **PDO**: Thư viện kết nối cơ sở dữ liệu an toàn
- **MVC Pattern**: Kiến trúc ứng dụng rõ ràng
- **Session Management**: Quản lý phiên đăng nhập
- **File-based Settings**: Lưu trữ cài đặt hệ thống

### Frontend
- **HTML5**: Cấu trúc trang web
- **CSS3**: Styling và responsive design
- **JavaScript (ES6+)**: Tương tác người dùng
- **Bootstrap 5**: Framework CSS responsive
- **jQuery 3.6+**: Thư viện JavaScript
- **ApexCharts**: Thư viện biểu đồ tương tác
- **Perfect Scrollbar**: Thanh cuộn tùy chỉnh

### Thư viện và Framework
- **Bootstrap 5.3+**: UI Framework
- **ApexCharts**: Biểu đồ dữ liệu
- **jQuery**: DOM manipulation
- **Popper.js**: Tooltip và popover
- **Masonry**: Layout grid
- **Highlight.js**: Syntax highlighting

## Cài đặt và cấu hình

### Yêu cầu hệ thống
- **Web Server**: Apache 2.4+ hoặc Nginx 1.18+
- **PHP**: 7.4 trở lên (khuyến nghị PHP 8.0+)
- **MySQL**: 5.7 trở lên (khuyến nghị MySQL 8.0+)
- **Extensions PHP**: PDO, PDO_MySQL, JSON, Session, OpenSSL
- **Disk Space**: Tối thiểu 100MB
- **RAM**: Tối thiểu 512MB

### Hướng dẫn cài đặt

#### Bước 1: Tải mã nguồn
```bash
git clone https://github.com/boyisvan/time-keepting-system.git
cd timekeeping-system
```

#### Bước 2: Cấu hình web server
- Đặt thư mục dự án vào thư mục web root
- Cấu hình DocumentRoot trỏ đến thư mục dự án
- Đảm bảo mod_rewrite được bật (Apache)

#### Bước 3: Cấu hình cơ sở dữ liệu
1. Tạo database mới:
```sql
CREATE DATABASE timekeeping_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Import file SQL:
```bash
mysql -u username -p timekeeping_system < main/database/timekeeping_system.sql
```

#### Bước 4: Cấu hình môi trường
1. Sao chép file cấu hình:
```bash
cp main/env.example main/.env
```

2. Chỉnh sửa file `.env`:
```env
DB_HOST=localhost
DB_PORT=3306
DB_NAME=timekeeping_system
DB_USER=your_username
DB_PASS=your_password
APP_URL=http://localhost
APP_DEBUG=true
```

#### Bước 5: Cấu hình quyền truy cập
```bash
chmod 755 main/
chmod 644 main/config/settings.json
```

#### Bước 6: Truy cập ứng dụng
- Mở trình duyệt và truy cập: `http://localhost/main`
- Đăng nhập với tài khoản mặc định:
  - Username: `admin`
  - Password: `password`

## Cấu trúc dự án

```
timekeeping-system/
├── main/                          # Thư mục ứng dụng chính
│   ├── config/                    # Cấu hình hệ thống
│   │   ├── app.php               # Cấu hình ứng dụng
│   │   ├── database.php          # Cấu hình database
│   │   ├── constants.php         # Hằng số hệ thống
│   │   └── settings.json         # Cài đặt người dùng
│   ├── controllers/              # Controllers (MVC)
│   │   ├── AuthController.php    # Xác thực
│   │   ├── DashboardController.php # Trang chủ
│   │   ├── EmployeeController.php # Quản lý nhân viên
│   │   ├── DepartmentController.php # Quản lý phòng ban
│   │   ├── RoleController.php    # Quản lý vai trò
│   │   ├── ReportsController.php # Báo cáo
│   │   └── SettingsController.php # Cài đặt
│   ├── models/                   # Models (MVC)
│   │   ├── User.php             # Model người dùng
│   │   ├── Department.php       # Model phòng ban
│   │   ├── Role.php             # Model vai trò
│   │   └── Position.php         # Model chức vụ
│   ├── views/                    # Views (MVC)
│   │   ├── layouts/             # Layout chung
│   │   ├── auth/                # Trang đăng nhập
│   │   ├── dashboard.php        # Trang chủ
│   │   ├── employees/           # Quản lý nhân viên
│   │   ├── departments/         # Quản lý phòng ban
│   │   ├── reports/             # Báo cáo
│   │   └── settings/            # Cài đặt
│   ├── core/                    # Core classes
│   │   ├── BaseController.php   # Controller cơ sở
│   │   ├── BaseModel.php        # Model cơ sở
│   │   ├── Database.php         # Kết nối database
│   │   ├── Request.php          # Xử lý request
│   │   ├── Response.php         # Xử lý response
│   │   └── Session.php          # Quản lý session
│   ├── database/                # File SQL
│   │   └── timekeeping_system.sql
│   └── index.php                # Entry point
├── assets/                      # Tài nguyên tĩnh
│   ├── css/                     # Stylesheet
│   ├── js/                      # JavaScript
│   ├── img/                     # Hình ảnh
│   └── vendor/                  # Thư viện bên thứ 3
├── scss/                        # SCSS source files
└── README.md                    # Tài liệu dự án
```

## Chức năng chính

### 1. Quản lý xác thực
- Đăng nhập/đăng xuất
- Quản lý phiên đăng nhập
- Phân quyền người dùng
- Bảo mật mật khẩu

### 2. Quản lý nhân viên
- Thêm/sửa/xóa nhân viên
- Quản lý thông tin cá nhân
- Phân công phòng ban và chức vụ
- Quản lý trạng thái hoạt động

### 3. Quản lý phòng ban
- Tạo/sửa/xóa phòng ban
- Phân cấp phòng ban
- Quản lý trưởng phòng
- Mã phòng ban duy nhất

### 4. Quản lý vai trò
- Tạo/sửa/xóa vai trò
- Phân quyền chi tiết
- Quản lý quyền truy cập

### 5. Báo cáo và thống kê
- Báo cáo chấm công
- Báo cáo làm thêm giờ
- Báo cáo nhân viên
- Báo cáo phòng ban
- Biểu đồ tương tác

### 6. Dashboard tổng quan
- Thống kê tổng quan
- Biểu đồ trực quan
- Hoạt động gần đây
- Thông tin nhanh

### 7. Cài đặt hệ thống
- Cấu hình công ty
- Cài đặt chấm công
- Quản lý múi giờ
- Cài đặt email

## Các lỗi thường gặp và cách khắc phục

### 1. Lỗi kết nối cơ sở dữ liệu
**Triệu chứng**: "Connection failed" hoặc "Database error"
**Nguyên nhân**: Cấu hình database không đúng
**Khắc phục**:
- Kiểm tra thông tin kết nối trong `main/config/database.php`
- Đảm bảo MySQL service đang chạy
- Kiểm tra username/password database
- Kiểm tra quyền truy cập database

### 2. Lỗi 404 Not Found
**Triệu chứng**: Trang không tìm thấy
**Nguyên nhân**: URL rewriting không hoạt động
**Khắc phục**:
- Kiểm tra mod_rewrite đã được bật
- Kiểm tra file `.htaccess` có tồn tại
- Cấu hình lại DocumentRoot

### 3. Lỗi Session
**Triệu chứng**: Không thể đăng nhập, session bị mất
**Nguyên nhân**: Cấu hình session không đúng
**Khắc phục**:
- Kiểm tra quyền ghi thư mục session
- Cấu hình session.save_path
- Kiểm tra session.cookie_secure

### 4. Lỗi JavaScript
**Triệu chứng**: Giao diện không hoạt động, console có lỗi
**Nguyên nhân**: File JS không load được
**Khắc phục**:
- Kiểm tra đường dẫn file JS
- Kiểm tra quyền đọc file
- Kiểm tra cú pháp JavaScript

### 5. Lỗi CSS không load
**Triệu chứng**: Giao diện bị vỡ, không có style
**Nguyên nhân**: File CSS không load được
**Khắc phục**:
- Kiểm tra đường dẫn file CSS
- Kiểm tra quyền đọc file
- Kiểm tra cú pháp CSS

### 6. Lỗi ApexCharts
**Triệu chứng**: Biểu đồ không hiển thị
**Nguyên nhân**: Thư viện ApexCharts không load
**Khắc phục**:
- Kiểm tra file apexcharts.js có tồn tại
- Kiểm tra thứ tự load script
- Kiểm tra dữ liệu truyền vào chart

### 7. Lỗi Upload file
**Triệu chứng**: Không thể upload ảnh đại diện
**Nguyên nhân**: Cấu hình upload không đúng
**Khắc phục**:
- Kiểm tra quyền ghi thư mục upload
- Kiểm tra upload_max_filesize
- Kiểm tra post_max_size

## Hình ảnh về sản phẩm

**Trang chủ** ( đã thu nhỏ về 70%)

<img width="1862" height="909" alt="{CC4BE5A5-B776-4735-B8D9-1EC64D42FF09}" src="https://github.com/user-attachments/assets/6b99d616-0a19-42f6-b84a-10f90d1c8397" />

**Chấm công đầu vào, đầu ra**

<img width="1919" height="890" alt="{7CABC010-9B2C-4C7F-BDD8-2608F1DEA9C5}" src="https://github.com/user-attachments/assets/db098b70-0f5f-436b-9353-a2a0c1155f42" />

<img width="1906" height="843" alt="{3BB3FB17-DF57-4610-87AD-E4AD849C6B63}" src="https://github.com/user-attachments/assets/7a4d4540-6c48-4535-8892-c5ac0824b67a" />

**Lịch sử chấm công**

<img width="1895" height="895" alt="{C97F7374-5FCB-4A50-A92B-A5587C023E26}" src="https://github.com/user-attachments/assets/374e0c1e-2b03-4a76-b7e9-b7936a743c27" />

**Quản lý nhân viên ( thêm, sửa, xóa, tìm kiếm, xem chi tiết, lọc )**

<img width="1907" height="860" alt="{4089EFD9-6270-48F8-8EC2-9419DE0EB8C0}" src="https://github.com/user-attachments/assets/b8e1a4f9-5b9e-4db3-92d2-00cbe150147e" />

**Các biểu đồ quản lý**

<img width="1788" height="1267" alt="image" src="https://github.com/user-attachments/assets/59736034-e5c6-4a20-a966-65aeddc1de92" />

**Cài đặt hệ thống**

<img width="1888" height="752" alt="{8103EF09-DC14-40E3-9379-055DC3E7EA90}" src="https://github.com/user-attachments/assets/fb088470-3009-475d-9c80-58177bd75c97" />

## Hướng phát triển trong tương lai

### Phiên bản 1.1 (Q2 2025)
- **Chức năng chấm công thực tế**
  - Chấm công qua web
  - Chấm công qua mobile app
  - Chấm công qua kiosk
  - Xác thực địa điểm (GPS)

- **Quản lý ca làm việc**
  - Tạo lịch ca làm việc
  - Phân công ca tự động
  - Thông báo lịch làm việc

- **Tính lương tự động**
  - Tính lương theo giờ làm
  - Tính phụ cấp làm thêm
  - Xuất bảng lương

### Phiên bản 1.2 (Q3 2025)
- **Quản lý nghỉ phép**
  - Đăng ký nghỉ phép
  - Duyệt nghỉ phép
  - Quản lý ngày nghỉ lễ

- **Thông báo và báo cáo**
  - Gửi email thông báo
  - Báo cáo định kỳ
  - Xuất báo cáo PDF/Excel

- **API và tích hợp**
  - RESTful API
  - Webhook
  - Tích hợp hệ thống khác

### Phiên bản 2.0 (Q4 2025)
- **Mobile App**
  - Ứng dụng di động
  - Chấm công offline
  - Đồng bộ dữ liệu

- **AI và Machine Learning**
  - Dự đoán xu hướng chấm công
  - Phát hiện bất thường
  - Tối ưu hóa lịch làm việc

- **Multi-tenant**
  - Hỗ trợ nhiều công ty
  - Phân quyền theo tổ chức
  - Cấu hình riêng biệt

## Hạn chế hiện tại

### 1. Hạn chế kỹ thuật
- **Chưa có API**: Không có RESTful API cho tích hợp
- **Chưa có cache**: Hiệu suất chưa tối ưu với dữ liệu lớn
- **Chưa có queue**: Xử lý tác vụ nặng chưa tối ưu
- **Chưa có backup**: Chưa có hệ thống sao lưu tự động

### 2. Hạn chế chức năng
- **Chưa có chấm công thực tế**: Chỉ có giao diện, chưa có logic chấm công
- **Chưa có mobile app**: Chỉ hỗ trợ web
- **Chưa có thông báo**: Chưa có hệ thống thông báo real-time
- **Chưa có báo cáo nâng cao**: Báo cáo còn đơn giản

### 3. Hạn chế bảo mật
- **Chưa có 2FA**: Chưa có xác thực 2 yếu tố
- **Chưa có audit log**: Chưa có nhật ký chi tiết
- **Chưa có rate limiting**: Chưa có giới hạn request
- **Chưa có encryption**: Chưa mã hóa dữ liệu nhạy cảm

### 4. Hạn chế hiệu suất
- **Chưa có CDN**: Chưa có Content Delivery Network
- **Chưa có compression**: Chưa nén dữ liệu
- **Chưa có optimization**: Chưa tối ưu hóa database
- **Chưa có monitoring**: Chưa có giám sát hệ thống

## Giấy phép và sử dụng

### Giấy phép
Dự án này được phát hành dưới giấy phép **MIT License**. Bạn có thể tự do sử dụng, chỉnh sửa, phân phối và sử dụng cho mục đích thương mại.

### Điều khoản sử dụng
1. **Sử dụng miễn phí**: Bạn có thể sử dụng dự án này miễn phí cho mục đích cá nhân và thương mại
2. **Chỉnh sửa**: Bạn có thể chỉnh sửa mã nguồn theo nhu cầu
3. **Phân phối**: Bạn có thể phân phối lại dự án với điều kiện giữ nguyên thông tin tác giả
4. **Không bảo hành**: Tác giả không chịu trách nhiệm về bất kỳ thiệt hại nào
5. **Hỗ trợ**: Hỗ trợ kỹ thuật qua email và Zalo

### Quyền sở hữu trí tuệ
- Mã nguồn được phát hành dưới giấy phép MIT
- Tài liệu và README thuộc bản quyền tác giả
- Các thư viện bên thứ 3 tuân theo giấy phép riêng của chúng

## Thông tin tác giả

**Hoàng Đức Văn (ducvancoder)**

- **Email**: ducvan05102002@gmail.com
- **Số điện thoại/Zalo**: 0587282880
- **GitHub**: [@ducvancoder](https://github.com/boyisvan)
- **Chuyên môn**: Full-stack Developer, PHP, JavaScript, MySQL
- **Kinh nghiệm**: 3+ năm phát triển web application

### Liên hệ hỗ trợ
- **Email hỗ trợ**: ducvan05102002@gmail.com
- **Zalo hỗ trợ**: 0587282880
- **Thời gian hỗ trợ**: 9:00 - 18:00 (T2-T6)
- **Phản hồi**: Trong vòng 24 giờ

### Đóng góp cho dự án
Nếu bạn muốn đóng góp cho dự án, vui lòng:
1. Fork repository
2. Tạo branch mới cho feature
3. Commit thay đổi
4. Tạo Pull Request
5. Mô tả chi tiết thay đổi

## Lời cảm ơn

Cảm ơn tất cả những người đã đóng góp ý kiến, báo lỗi và hỗ trợ trong quá trình phát triển dự án. Đặc biệt cảm ơn cộng đồng PHP Việt Nam và các developer đã chia sẻ kiến thức quý báu.

---

**Lưu ý**: Dự án này đang trong giai đoạn phát triển và có thể có những thay đổi không tương thích ngược. Vui lòng theo dõi changelog để cập nhật thông tin mới nhất.

**Cập nhật lần cuối**: Tháng 9/2025
**Phiên bản tài liệu**: 1.0.0
