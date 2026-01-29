-- =======================================================
-- PHẦN 1: KHỞI TẠO DATABASE (LÀM SẠCH VÀ TẠO MỚI)
-- =======================================================
DROP DATABASE IF EXISTS GymManagementDB;
CREATE DATABASE GymManagementDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE GymManagementDB;

-- =======================================================
-- PHẦN 2: TẠO BẢNG (SCHEMA)
-- =======================================================
CREATE TABLE Province (
    province_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE District (
    district_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    province_id INT,
    FOREIGN KEY (province_id) REFERENCES Province(province_id)
);

CREATE TABLE Ward (
    ward_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    district_id INT,
    FOREIGN KEY (district_id) REFERENCES District(district_id)
);

CREATE TABLE Category (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE Shipping (
    shipping_id INT PRIMARY KEY AUTO_INCREMENT,
    type VARCHAR(100) NOT NULL,
    cost DECIMAL(18, 2) DEFAULT 0
);

CREATE TABLE Member (
    member_id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    mobile VARCHAR(15),
    address VARCHAR(255),
    dob DATE,
    gender TINYINT(1),
    height FLOAT,
    weight FLOAT
);

CREATE TABLE ServicePackage (
    service_package_id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT,
    code VARCHAR(20) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(18, 2) NOT NULL,
    status VARCHAR(50) DEFAULT 'Active',
    detail TEXT,
    duration INT,
    FOREIGN KEY (category_id) REFERENCES Category(category_id)
);

CREATE TABLE Cart (
    cart_id INT PRIMARY KEY AUTO_INCREMENT,
    member_id INT,
    service_package_id INT,
    quantity INT DEFAULT 1,
    FOREIGN KEY (member_id) REFERENCES Member(member_id),
    FOREIGN KEY (service_package_id) REFERENCES ServicePackage(service_package_id)
);

CREATE TABLE `Order` (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    member_id INT,
    shipping_id INT,
    ward_id INT,
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(18, 2),
    payment_type VARCHAR(50),
    status VARCHAR(50) DEFAULT 'Pending',
    shipping_address VARCHAR(255),
    FOREIGN KEY (member_id) REFERENCES Member(member_id),
    FOREIGN KEY (shipping_id) REFERENCES Shipping(shipping_id),
    FOREIGN KEY (ward_id) REFERENCES Ward(ward_id)
);

CREATE TABLE OrderDetail (
    order_detail_id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT,
    service_package_id INT,
    quantity INT,
    price DECIMAL(18, 2),
    FOREIGN KEY (order_id) REFERENCES `Order`(order_id),
    FOREIGN KEY (service_package_id) REFERENCES ServicePackage(service_package_id)
);

-- =======================================================
-- PHẦN 3: ĐỔ DỮ LIỆU MẪU (SEED DATA)
-- =======================================================

-- 3.1. Địa lý
INSERT INTO Province (name) VALUES ('Hồ Chí Minh'), ('Hà Nội');
INSERT INTO District (name, province_id) VALUES ('Quận 1', 1), ('Quận Bình Thạnh', 1);
INSERT INTO Ward (name, district_id) VALUES ('Phường Bến Nghé', 1), ('Phường 25', 2);

-- 3.2. Danh mục & Ship
INSERT INTO Category (name) VALUES ('Membership (Gói tập)'), ('Personal Trainer (PT)'), ('Supplement (TPBS)');
INSERT INTO Shipping (type, cost) VALUES ('Standard', 30000), ('Express', 50000);

-- 3.3. Hội viên (Member)
INSERT INTO Member (email, password, name, mobile, address, gender, height, weight) VALUES 
('admin@gym.com', '123456', 'Admin System', '0909000111', 'VP Admin', 1, 170, 65),
('hieu@gmail.com', '123456', 'Nguyễn Văn Hiếu', '0909123456', '123 Lê Lợi', 1, 175, 70),
('lan@gmail.com', '123456', 'Trần Thị Lan', '0909999888', '456 Nguyễn Huệ', 0, 160, 48);

-- 3.4. Sản phẩm (ServicePackage)
INSERT INTO ServicePackage (category_id, code, name, price, detail, duration) VALUES 
(1, 'GYM01', 'Classic Gym 1 Month', 500000, 'Tập gym cơ bản 1 tháng', 30),
(1, 'GYM12', 'Gold Gym 1 Year', 5000000, 'Tập full năm + Khăn + Tủ', 365),
(2, 'PT10', 'PT 1:1 (10 Sessions)', 3000000, 'Huấn luyện viên kèm riêng', 45),
(3, 'WHEY01', 'Whey Protein 5Lbs', 1800000, 'Hương Chocolate', 0);

-- 3.5. Đơn hàng mẫu (Order)
-- Ông Hiếu (ID 2) mua gói Gym 1 năm + Ship thường
INSERT INTO `Order` (member_id, shipping_id, ward_id, total, payment_type, status, shipping_address) 
VALUES (2, 1, 1, 5030000, 'MOMO', 'Paid', '123 Lê Lợi, P. Bến Nghé');

-- Chi tiết đơn hàng của ông Hiếu
INSERT INTO OrderDetail (order_id, service_package_id, quantity, price) 
VALUES (1, 2, 1, 5000000); 

-- =======================================================
-- PHẦN 4: SHOW HẾT TẤT CẢ (SELECT ALL)
-- =======================================================

-- Xem danh sách Hội viên
SELECT '--- DANH SACH HOI VIEN ---' AS Title;
SELECT * FROM Member;

-- Xem danh sách Gói tập
SELECT '--- DANH SACH GOI TAP ---' AS Title;
SELECT * FROM ServicePackage;

-- Xem Đơn hàng
SELECT '--- DANH SACH DON HANG ---' AS Title;
SELECT * FROM `Order`;

-- Xem Chi tiết đơn hàng 
SELECT '--- CHI TIET DON HANG ---' AS Title;
SELECT * FROM OrderDetail;