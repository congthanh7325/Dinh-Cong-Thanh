<?php
session_start();
include 'db.php';

$sql = "SELECT * FROM DienThoai";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Cửa hàng điện thoại</title>
<style>
    body { font-family: Arial, sans-serif; margin: 0; padding: 0; background:#f8f9fa; }
    header { background:#007bff; color:white; padding:20px 0; text-align:center; }
    header h1 { margin:0; font-size:28px; }
    nav { margin-top:10px; }
    nav a, nav span { margin:0 10px; text-decoration:none; color:white; font-weight:bold; }
    nav a:hover { text-decoration:underline; }
    h2 { color:#333; text-align:center; margin:20px 0; }
    table { border-collapse: collapse; width: 90%; margin: auto; background:white; box-shadow:0 2px 6px rgba(0,0,0,0.1); }
    th, td { border: 1px solid #ddd; padding: 12px; text-align: center; }
    th { background: #f4f4f4; }
    .btn { padding: 6px 12px; background: #28a745; color: white; text-decoration: none; border-radius: 4px; }
    .btn:hover { background: #218838; }
    .btn-info { background: #007bff; }
    .btn-info:hover { background: #0056b3; }
</style>
</head>
<body>

<header>
    <h1>📱 SHOP ĐIỆN THOẠI MINI 📱</h1>
    <nav>
        <a href="index.php">🏠 Trang chủ</a>
        <a href="cart.php">🛒 Giỏ hàng</a>
        <?php if(isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
            <a href="admin.php">⚙️ Quản lý sản phẩm</a>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['user'])): ?>
            <span>Xin chào, <b><?= $_SESSION['user']['username'] ?></b></span>
            <a href="logout.php">🚪 Đăng xuất</a>
        <?php else: ?>
            <a href="login.php">🔐 Đăng nhập</a>
            <a href="register.php">📝 Đăng ký</a>
        <?php endif; ?>
    </nav>
</header>

<h2>📋 Danh sách sản phẩm</h2>

<table>
<tr>
    <th>Mã</th>
    <th>Tên điện thoại</th>
    <th>Hãng</th>
    <th>Cấu hình</th>
    <th>Giá bán</th>
    <th>Mua</th>
    <th>Chi tiết</th>
</tr>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= htmlspecialchars($row['MaDT']) ?></td>
    <td><?= htmlspecialchars($row['TenDT']) ?></td>
    <td><?= htmlspecialchars($row['ThuongHieu']) ?></td>
    <td><?= htmlspecialchars($row['CauHinh']) ?></td>
    <td><?= number_format($row['GiaBan']) ?> đ</td>
    <td><a class="btn" href="cart.php?add=<?= urlencode($row['MaDT']) ?>">Mua ngay</a></td>
    <td><a class="btn btn-info" href="detail.php?id=<?= urlencode($row['MaDT']) ?>">Xem</a></td>
</tr>
<?php endwhile; ?>
</table>

</body>
</html>
<?php $conn->close(); ?>