=============================================================================
             HỆ THỐNG QUẢN LÝ PHÒNG GYM (GYM MANAGEMENT SYSTEM)
                   ỨNG DỤNG CONSOLE C++ - PHIÊN BẢN 1.0
=============================================================================

1. GIỚI THIỆU
-------------
Đây là ứng dụng mô phỏng hệ thống quản lý backend cho phòng Gym được viết bằng 
ngôn ngữ C++. Ứng dụng chạy trên nền tảng Console (màn hình đen), sử dụng các 
cấu trúc dữ liệu trong bộ nhớ (Vector, Struct) để mô phỏng Cơ sở dữ liệu.

Dự án mô phỏng đầy đủ quy trình nghiệp vụ từ việc Khách xem gói tập, Đăng ký 
thành viên, Mua hàng (Thêm vào giỏ), Thanh toán cho đến việc Admin quản lý 
đơn hàng và dịch vụ.

2. CÔNG NGHỆ SỬ DỤNG
--------------------
- Ngôn ngữ: C++ (Standard 11 trở lên).
- Thư viện chuẩn: <iostream>, <vector>, <string>, <algorithm>, <iomanip>.
- Cơ sở dữ liệu: In-memory (Lưu trữ tạm thời trên RAM bằng Vector).
- IDE Khuyên dùng: Visual Studio Code, Dev-C++, Visual Studio, CLion.

3. CÁC CHỨC NĂNG CHÍNH (FEATURE LIST)
-------------------------------------
Hệ thống phân quyền thành 3 vai trò: Guest (Khách), Member (Hội viên), Admin.

[A] Guest (Khách vãng lai)
    - Xem danh sách gói tập, sản phẩm (Browse).
    - Tìm kiếm sản phẩm theo tên (Search).
    - Đăng ký tài khoản mới (Register).
    - Đăng nhập (Login).
    - Khôi phục mật khẩu (Reset Password).

[B] Member (Hội viên đã đăng nhập)
    - Bao gồm các chức năng của Guest.
    - Thêm sản phẩm/gói tập vào giỏ hàng (Add to Cart).
    - Quản lý giỏ hàng: Xem, Xóa món (View Cart).
    - Thanh toán: Hỗ trợ mô phỏng COD và Online Payment (Checkout).
    - Xem lịch sử đơn hàng đã mua (Order History).
    - Hủy đơn hàng khi chưa xử lý (Cancel Order).
    - Cập nhật thông tin cá nhân (Update Profile).

[C] Admin (Quản trị viên)
    - Quản lý Dịch vụ: Xem, Thêm mới, Xóa gói tập/sản phẩm.
    - Quản lý Hội viên: Xem danh sách hội viên đăng ký.
    - Quản lý Đơn hàng: Xem đơn hàng, Cập nhật trạng thái (Paid/Active).

4. HƯỚNG DẪN CÀI ĐẶT VÀ CHẠY
----------------------------
Cách 1: Sử dụng Visual Studio Code (Khuyên dùng)
    1. Mở thư mục chứa file code (ví dụ: main.cpp).
    2. Mở Terminal (Ctrl + `).
    3. Gõ lệnh biên dịch: 
       g++ main.cpp -o gym_app
    4. Chạy chương trình:
       ./gym_app    (trên Mac/Linux)
       gym_app.exe  (trên Windows)

Cách 2: Sử dụng Dev-C++ hoặc Code::Blocks
    1. Tạo Project mới hoặc File mới.
    2. Copy toàn bộ code vào.
    3. Nhấn F11 (Compile & Run).

5. TÀI KHOẢN DÙNG THỬ (SEED DATA)
---------------------------------
Để thuận tiện cho việc kiểm thử (Test), hệ thống đã tạo sẵn dữ liệu mẫu:

(*) TÀI KHOẢN ADMIN:
    - Username: admin
    - Password: 123456

(*) TÀI KHOẢN HỘI VIÊN (MEMBER):
    - Email:    xxx@gmail.com
    - Password: xxxxxx

Lưu ý: Bạn cũng có thể tự Đăng ký (Register) một tài khoản mới từ menu chính.

6. CẤU TRÚC MÃ NGUỒN
--------------------
File nguồn được tổ chức theo mô hình đơn giản hóa (Monolithic):

1. DATA STRUCTURES:
   - Các struct: ServicePackage, Member, Order, CartItem... đại diện cho các bảng dữ liệu.

2. GLOBAL DATABASE:
   - Các vector: vector<Member>, vector<Order>... đóng vai trò như các bảng trong Database.

3. UTILITY FUNCTIONS:
   - seedData(): Tạo dữ liệu giả lập ban đầu.
   - clearScreen(), pause(): Các hàm hỗ trợ giao diện console.

4. USE-CASE IMPLEMENTATIONS:
   - Các hàm xử lý logic nghiệp vụ như: login(), checkout(), viewHistory(), addToCart()...

5. MENUS:
   - guestMenu(), memberMenu(), adminMenu(): Điều hướng người dùng theo vai trò.

=============================================================================
Cảm ơn đã sử dụng ứng dụng!
=============================================================================
