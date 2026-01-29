-- 1. Tạo Database
CREATE DATABASE IF NOT EXISTS GymManagementDB;
USE GymManagementDB;

-- =============================================
-- NHÓM 1: CÁC BẢNG DANH MỤC & ĐỊA LÝ
-- =============================================

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

-- =============================================
-- NHÓM 2: CÁC BẢNG CHÍNH (Member, Product)
-- =============================================

CREATE TABLE Member (
    member_id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    mobile VARCHAR(15),
    address VARCHAR(255),
    dob DATE,
    gender TINYINT(1), -- 1: Nam, 0: Nữ
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
    detail TEXT, -- MySQL dùng TEXT thay vì NTEXT/NVARCHAR(MAX)
    duration INT,
    FOREIGN KEY (category_id) REFERENCES Category(category_id)
);

-- =============================================
-- NHÓM 3: CÁC BẢNG NGHIỆP VỤ (Order, Cart)
-- =============================================

CREATE TABLE Cart (
    cart_id INT PRIMARY KEY AUTO_INCREMENT,
    member_id INT,
    service_package_id INT,
    quantity INT DEFAULT 1,
    FOREIGN KEY (member_id) REFERENCES Member(member_id),
    FOREIGN KEY (service_package_id) REFERENCES ServicePackage(service_package_id)
);

-- Trong MySQL, Order là từ khóa, phải dùng dấu huyền ` ` để bao quanh
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