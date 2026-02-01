-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost
-- Thời gian đã tạo: Th2 01, 2026 lúc 10:38 AM
-- Phiên bản máy phục vụ: 10.4.28-MariaDB
-- Phiên bản PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `GymFace`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Cart`
--

CREATE TABLE `Cart` (
  `cart_id` int(11) NOT NULL,
  `member_id` int(11) DEFAULT NULL,
  `service_package_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Category`
--

CREATE TABLE `Category` (
  `category_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `Category`
--

INSERT INTO `Category` (`category_id`, `name`) VALUES
(1, 'Goi Tap'),
(2, 'PT'),
(3, 'Thuc Pham Bo Sung');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `District`
--

CREATE TABLE `District` (
  `district_id` int(11) NOT NULL,
  `province_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `District`
--

INSERT INTO `District` (`district_id`, `province_id`, `name`) VALUES
(1, 1, 'Quan 1'),
(2, 1, 'Quan 3');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Member`
--

CREATE TABLE `Member` (
  `member_id` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `DOB` date DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `height` float DEFAULT NULL,
  `weight` float DEFAULT NULL,
  `role` varchar(20) DEFAULT 'member'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `Member`
--

INSERT INTO `Member` (`member_id`, `email`, `password`, `name`, `mobile`, `address`, `DOB`, `gender`, `height`, `weight`, `role`) VALUES
(1, 'admin@gym.com', '123456', 'Quan Tri Vien', '0912345678', 'Phong Admin', NULL, NULL, NULL, NULL, 'admin'),
(3, 'leanhkiet2508@gmail.com', '123', 'Lê Anh Kiệt', '0918608977', '1 abc', NULL, NULL, NULL, NULL, 'member');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Order`
--

CREATE TABLE `Order` (
  `order_id` int(11) NOT NULL,
  `member_id` int(11) DEFAULT NULL,
  `shipping_id` int(11) DEFAULT NULL,
  `ward_id` int(11) DEFAULT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `total` decimal(18,2) DEFAULT NULL,
  `payment_type` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `Order`
--

INSERT INTO `Order` (`order_id`, `member_id`, `shipping_id`, `ward_id`, `order_date`, `total`, `payment_type`, `status`) VALUES
(1, 1, 1, 1, '2026-02-01 13:58:07', 10000.00, NULL, 'Cancelled'),
(2, 1, 1, 1, '2026-02-01 14:37:42', 500000.00, NULL, 'Paid'),
(3, 1, 1, 1, '2026-02-01 14:38:57', 500000.00, NULL, 'Cancelled'),
(6, 3, 1, 1, '2026-02-01 16:29:56', 500000.00, NULL, 'Paid');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Order_detail`
--

CREATE TABLE `Order_detail` (
  `order_detail_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `service_package_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(18,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `Order_detail`
--

INSERT INTO `Order_detail` (`order_detail_id`, `order_id`, `service_package_id`, `quantity`, `price`) VALUES
(1, 1, 4, 1, 10000.00),
(2, 2, 1, 1, 500000.00),
(3, 3, 1, 1, 500000.00),
(7, 6, 1, 1, 500000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Province`
--

CREATE TABLE `Province` (
  `province_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `Province`
--

INSERT INTO `Province` (`province_id`, `name`) VALUES
(1, 'TP. Ho Chi Minh'),
(2, 'Ha Noi');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Service_package`
--

CREATE TABLE `Service_package` (
  `service_package_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `code` varchar(20) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `price` decimal(18,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `detail` text DEFAULT NULL,
  `duration` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `Service_package`
--

INSERT INTO `Service_package` (`service_package_id`, `category_id`, `code`, `name`, `price`, `status`, `detail`, `duration`) VALUES
(1, 1, 'SV001', 'Goi 1 Thang', 500000.00, NULL, 'Tap full time', 30),
(2, 2, 'PT01', 'PT 1 kem 1', 3000000.00, NULL, '10 buoi tap', 10),
(4, 3, 'TP02', 'Nước Suối', 10000.00, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Shipping`
--

CREATE TABLE `Shipping` (
  `shipping_id` int(11) NOT NULL,
  `type` varchar(100) DEFAULT NULL,
  `cost` decimal(18,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `Shipping`
--

INSERT INTO `Shipping` (`shipping_id`, `type`, `cost`) VALUES
(1, 'Giao nhanh', 30000.00),
(2, 'Giao tiet kiem', 15000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Ward`
--

CREATE TABLE `Ward` (
  `ward_id` int(11) NOT NULL,
  `district_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `Ward`
--

INSERT INTO `Ward` (`ward_id`, `district_id`, `name`) VALUES
(1, 1, 'Ben Nghe'),
(2, 1, 'Da Kao');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `Cart`
--
ALTER TABLE `Cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `service_package_id` (`service_package_id`);

--
-- Chỉ mục cho bảng `Category`
--
ALTER TABLE `Category`
  ADD PRIMARY KEY (`category_id`);

--
-- Chỉ mục cho bảng `District`
--
ALTER TABLE `District`
  ADD PRIMARY KEY (`district_id`),
  ADD KEY `province_id` (`province_id`);

--
-- Chỉ mục cho bảng `Member`
--
ALTER TABLE `Member`
  ADD PRIMARY KEY (`member_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `Order`
--
ALTER TABLE `Order`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `shipping_id` (`shipping_id`),
  ADD KEY `ward_id` (`ward_id`);

--
-- Chỉ mục cho bảng `Order_detail`
--
ALTER TABLE `Order_detail`
  ADD PRIMARY KEY (`order_detail_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `service_package_id` (`service_package_id`);

--
-- Chỉ mục cho bảng `Province`
--
ALTER TABLE `Province`
  ADD PRIMARY KEY (`province_id`);

--
-- Chỉ mục cho bảng `Service_package`
--
ALTER TABLE `Service_package`
  ADD PRIMARY KEY (`service_package_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `Shipping`
--
ALTER TABLE `Shipping`
  ADD PRIMARY KEY (`shipping_id`);

--
-- Chỉ mục cho bảng `Ward`
--
ALTER TABLE `Ward`
  ADD PRIMARY KEY (`ward_id`),
  ADD KEY `district_id` (`district_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `Cart`
--
ALTER TABLE `Cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `Category`
--
ALTER TABLE `Category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `District`
--
ALTER TABLE `District`
  MODIFY `district_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `Member`
--
ALTER TABLE `Member`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `Order`
--
ALTER TABLE `Order`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `Order_detail`
--
ALTER TABLE `Order_detail`
  MODIFY `order_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `Province`
--
ALTER TABLE `Province`
  MODIFY `province_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `Service_package`
--
ALTER TABLE `Service_package`
  MODIFY `service_package_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `Shipping`
--
ALTER TABLE `Shipping`
  MODIFY `shipping_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `Ward`
--
ALTER TABLE `Ward`
  MODIFY `ward_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `Cart`
--
ALTER TABLE `Cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `Member` (`member_id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`service_package_id`) REFERENCES `Service_package` (`service_package_id`);

--
-- Các ràng buộc cho bảng `District`
--
ALTER TABLE `District`
  ADD CONSTRAINT `district_ibfk_1` FOREIGN KEY (`province_id`) REFERENCES `Province` (`province_id`);

--
-- Các ràng buộc cho bảng `Order`
--
ALTER TABLE `Order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `Member` (`member_id`),
  ADD CONSTRAINT `order_ibfk_2` FOREIGN KEY (`shipping_id`) REFERENCES `Shipping` (`shipping_id`),
  ADD CONSTRAINT `order_ibfk_3` FOREIGN KEY (`ward_id`) REFERENCES `Ward` (`ward_id`);

--
-- Các ràng buộc cho bảng `Order_detail`
--
ALTER TABLE `Order_detail`
  ADD CONSTRAINT `order_detail_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `Order` (`order_id`),
  ADD CONSTRAINT `order_detail_ibfk_2` FOREIGN KEY (`service_package_id`) REFERENCES `Service_package` (`service_package_id`);

--
-- Các ràng buộc cho bảng `Service_package`
--
ALTER TABLE `Service_package`
  ADD CONSTRAINT `service_package_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `Category` (`category_id`);

--
-- Các ràng buộc cho bảng `Ward`
--
ALTER TABLE `Ward`
  ADD CONSTRAINT `ward_ibfk_1` FOREIGN KEY (`district_id`) REFERENCES `District` (`district_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
