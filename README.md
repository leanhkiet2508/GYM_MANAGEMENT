# GymFace - Hệ thống Quản lý & Kinh doanh Gói tập Gym

GymFace là một ứng dụng web được xây dựng bằng **PHP thuần** và **MySQL**, hỗ trợ quản lý phòng tập, bán các gói dịch vụ (Service Packages) và quản lý hội viên.

## 🚀 Tính năng chính

### 1. Dành cho Khách hàng (Member)
* **Tài khoản:** Đăng ký, Đăng nhập, Đăng xuất, Quên mật khẩu (Cấp lại mật khẩu ngẫu nhiên), Cập nhật hồ sơ cá nhân (Profile).
* **Mua sắm:** Xem danh sách gói tập, Tìm kiếm sản phẩm, Thêm vào giỏ hàng.
* **Thanh toán:**
    * Quản lý giỏ hàng (Cập nhật số lượng, Xóa món).
    * Chọn phương thức vận chuyển (Giao nhanh/Tiết kiệm) với tính năng tự động tính tổng tiền.
* **Đơn hàng:** Xem lịch sử đơn hàng, Hủy đơn hàng khi còn ở trạng thái "Mới".

### 2. Dành cho Quản trị viên (Admin)
* **Quản lý Dịch vụ:** Thêm gói tập mới, Xóa gói tập cũ.
* **Quản lý Đơn hàng:** Xem danh sách đơn hàng, Duyệt đơn (Paid), Hủy đơn (Cancelled).
* **Quản lý Hội viên:** Xem danh sách thành viên, Xóa thành viên (Kèm logic xóa dữ liệu ràng buộc).

---

## 🛠️ Công nghệ sử dụng

* **Frontend:** HTML5, CSS3 (Custom Style), JavaScript (Xử lý tính tiền ship động).
* **Backend:** PHP (Native - Không dùng Framework).
* **Database:** MySQL.
* **Môi trường:** XAMPP (Apache Web Server).
* **Editor:** Visual Studio Code.

---

## ⚙️ Hướng dẫn Cài đặt & Chạy (Installation)

Để chạy dự án này trên máy cục bộ (Localhost), vui lòng làm theo các bước sau:

### Bước 1: Chuẩn bị môi trường
* Cài đặt phần mềm **XAMPP**.
* Khởi động **Apache** và **MySQL** trong XAMPP Control Panel.

### Bước 2: Cài đặt Mã nguồn
* Tải source code về.
* Copy thư mục dự án (ví dụ: `GYM`) vào đường dẫn: `C:\xampp\htdocs\` (trên Windows) hoặc `/Applications/XAMPP/xamppfiles/htdocs/` (trên Mac).

### Bước 3: Cài đặt Cơ sở dữ liệu
1.  Truy cập `http://localhost/phpmyadmin`.
2.  Tạo một Database mới tên là: **`GymFace`**.
3.  Chọn Database vừa tạo, vào tab **Import** (Nhập).
4.  Chọn file **`GymFace.sql`** có trong thư mục code và bấm **Go** (Thực hiện).
5.  *(Quan trọng)* Đảm bảo bảng `Shipping` và `Ward` đã có dữ liệu mẫu để chức năng thanh toán hoạt động.

### Bước 4: Chạy Website
* Mở trình duyệt và truy cập: `http://localhost/GYM`

---

## 📂 Cấu trúc thư mục

GYM/
├── admin.php               # Trang quản trị (Admin Dashboard)
├── cart.php                # Trang giỏ hàng & Thanh toán
├── db.php                  # File cấu hình kết nối CSDL
├── GymFace.sql             # Script khởi tạo Database
├── history.php             # Trang lịch sử đơn hàng
├── index.php               # Trang chủ (Danh sách sản phẩm)
├── login.php               # Trang đăng nhập
├── logout.php              # Xử lý đăng xuất
├── profile.php             # Trang thông tin cá nhân
├── register.php            # Trang đăng ký thành viên
├── reset_password.php      # Trang chức năng quên mật khẩu
└── style.css               # File định kiểu giao diện (CSS)

Tài khoản Demo (Để chấm bài)
1. Tài khoản Admin:

Email: admin@gym.com

Pass: 123456

2. Tài khoản Member (Mẫu):

Email: test@gmail.com

Pass: 123456

👨‍💻 Tác giả
Sinh viên: Lê Anh Kiệt

MSSV: 079206041786
