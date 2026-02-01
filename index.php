<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Trang chủ GymFace</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* CSS nội bộ bổ sung cho đẹp */
        .product { border:1px solid #ddd; padding:15px; background:white; border-radius:8px; display:flex; flex-direction:column; justify-content:space-between; }
        .grid-container { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; padding: 20px 0; }
        .badge-admin { background: red; color: white; padding: 3px 8px; border-radius: 4px; font-size: 12px; }
    </style>
</head>
<body>
    <nav>
        <a href="index.php" class="logo">GYMFACE <span style="color:white">STORE</span></a>
        <div>
            <?php if(isset($_SESSION['user_id'])): ?>
                
                <span style="color:white; margin-right:15px">Chào, <b><?php echo $_SESSION['uname']; ?></b></span>
                
                <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                    <a href="admin.php" style="color: #f1c40f;">★ TRANG QUẢN TRỊ</a>
                <?php else: ?>
                    <a href="cart.php">Giỏ hàng</a> 
                    <a href="history.php">Lịch sử</a>
                    <a href="profile.php">Hồ sơ</a>
                <?php endif; ?>

                <a href="logout.php" style="background:#555; padding:5px 10px; border-radius:4px">Thoát</a>

            <?php else: ?>
                <a href="login.php">Đăng nhập</a>
                <a href="register.php">Đăng ký</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container">
        <div style="margin: 20px 0; text-align: center;">
            <form method="GET" style="display:inline-block; width: 60%;">
                <input type="text" name="keyword" placeholder="Tìm gói tập, whey..." value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : '' ?>" style="width: 70%; display:inline-block">
                <button type="submit" style="width: 20%; display:inline-block">Tìm kiếm</button>
            </form>
        </div>

        <h2>DANH SÁCH DỊCH VỤ</h2>

        <div class="grid-container">
            <?php
            // UC 2 & 3: Hiển thị sản phẩm
            $sql = "SELECT s.*, c.name as cat_name FROM Service_package s 
                    JOIN Category c ON s.category_id = c.category_id WHERE 1=1";

            if (isset($_GET['keyword']) && $_GET['keyword'] != '') {
                $kw = $_GET['keyword'];
                $sql .= " AND s.name LIKE '%$kw%'"; 
            }

            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()){
            ?>
                <div class="product">
                    <div>
                        <h3><?php echo $row['name']; ?></h3>
                        <p style="color:gray; font-size:12px">Mã: <?php echo $row['code']; ?></p>
                        <p style="color:#666">Loại: <?php echo $row['cat_name']; ?></p>
                        <p class="price"><?php echo number_format($row['price']); ?> đ</p>
                    </div>
                    
                    <form action="cart.php" method="POST" style="margin-top:10px">
                        <input type="hidden" name="pkg_id" value="<?php echo $row['service_package_id']; ?>">
                        <button type="submit" name="add_db">Thêm vào giỏ</button>
                    </form>
                </div>
            <?php 
                } 
            } else {
                echo "<p>Không tìm thấy sản phẩm nào!</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>