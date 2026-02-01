<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Trang chủ GymFace</title>
    <style>
        .product { border:1px solid #ddd; padding:10px; margin:10px; width:300px; display:inline-block; vertical-align:top; }
        .nav { background:#333; color:white; padding:10px; }
        .nav a { color:white; margin-right:15px; text-decoration:none; }
    </style>
</head>
<body>
    <div class="nav">
    <?php if(isset($_SESSION['uid'])): ?>
        
        Xin chào, <b><?php echo $_SESSION['uname']; ?></b> 
        
        <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
            
            | <a href="admin.php" style="color: yellow; font-weight:bold;">★ VÀO TRANG QUẢN TRỊ</a>
            <?php else: ?>
            
            | <a href="cart.php">Giỏ hàng</a> 
            | <a href="history.php">Lịch sử đơn hàng</a>
            | <a href="profile.php">Hồ sơ cá nhân</a>

        <?php endif; ?>

        | <a href="logout.php">Đăng xuất</a>

    <?php else: ?>
        <a href="login.php">Đăng nhập</a> | <a href="register.php">Đăng ký</a>
    <?php endif; ?>
</div>

    <div style="margin: 20px 0;">
        <form method="GET">
            <input type="text" name="keyword" placeholder="Tìm gói tập, whey..." value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : '' ?>">
            <button type="submit">Tìm kiếm</button>
        </form>
    </div>

    <h2>DANH SÁCH DỊCH VỤ</h2>

    <?php
    // UC 2 & 3: Xử lý tìm kiếm và hiển thị
    $sql = "SELECT s.*, c.name as cat_name FROM Service_package s 
            JOIN Category c ON s.category_id = c.category_id WHERE 1=1";

    if (isset($_GET['keyword']) && $_GET['keyword'] != '') {
        $kw = $_GET['keyword'];
        $sql .= " AND s.name LIKE '%$kw%'"; // Tìm theo tên chứa từ khóa
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()){
    ?>
        <div class="product">
            <h3><?php echo $row['name']; ?></h3>
            <p style="color:gray">Mã: <?php echo $row['code']; ?></p>
            <p><b>Giá: <?php echo number_format($row['price']); ?> đ</b></p>
            <p>Loại: <?php echo $row['cat_name']; ?></p>
            <hr>
            
            <form action="cart.php" method="POST">
                <input type="hidden" name="pkg_id" value="<?php echo $row['service_package_id']; ?>">
                <button type="submit" name="add_db" style="background:orange; cursor:pointer">Thêm vào giỏ</button>
            </form>
        </div>
    <?php 
        } 
    } else {
        echo "<p>Không tìm thấy sản phẩm nào!</p>";
    }
    ?>
</body>
</html>