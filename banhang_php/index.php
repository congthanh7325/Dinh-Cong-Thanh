<?php
session_start();
include 'db.php';

$sql = "SELECT * FROM DienThoai";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cửa hàng điện thoại</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        header { margin-bottom: 20px; }
        nav a { margin-right: 15px; text-decoration: none; color: #007BFF; }
        h1 { color: #333; }
        table { border-collapse: collapse; width: 100%; margin-top: 15px; }
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
        <a href="index.php">Trang chủ</a>
        <a href="cart.php">Giỏ hàng 🛒</a>
        <a href="admin.php">Quản lý sản phẩm ⚙️</a>
    </nav>
</header>

<h2>Danh sách sản phẩm</h2>

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