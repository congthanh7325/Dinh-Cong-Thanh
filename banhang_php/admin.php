<?php
include 'db.php';
$result = $conn->query("SELECT * FROM DienThoai");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Quản lý sản phẩm</title>
<style>
    body { font-family: Arial, sans-serif; padding: 20px; }
    h2 { margin-bottom: 15px; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
    th { background: #f4f4f4; }
    .btn { padding: 5px 10px; border: none; cursor: pointer; border-radius: 4px; text-decoration: none; }
    .add { background: green; color: white; }
    .edit { background: orange; color: white; }
    .delete { background: red; color: white; }
    .shop { background: #007bff; color: white; }
</style>
</head>
<body>

<h2>📋 Quản lý sản phẩm</h2>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
    <p style="color: green;">✅ Sản phẩm đã được xóa thành công!</p>
<?php endif; ?>

<!-- Liên kết về shop cho khách hàng -->
<a class="btn shop" href="index.php">🏬 Xem cửa hàng</a>
<a class="btn add" href="add.php">➕ Thêm sản phẩm</a>
<br><br>

<table>
<tr>
    <th>Mã</th>
    <th>Tên điện thoại</th>
    <th>Hãng</th>
    <th>Cấu hình</th>
    <th>Giá</th>
    <th>Hành động</th>
</tr>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= htmlspecialchars($row['MaDT']) ?></td>
    <td><?= htmlspecialchars($row['TenDT']) ?></td>
    <td><?= htmlspecialchars($row['ThuongHieu']) ?></td>
    <td><?= htmlspecialchars($row['CauHinh']) ?></td>
    <td><?= number_format($row['GiaBan']) ?> đ</td>
    <td>
        <a class="btn edit" href="edit.php?id=<?= $row['MaDT'] ?>">✏️ Sửa</a>
        <a class="btn delete" href="delete.php?id=<?= $row['MaDT'] ?>" onclick="return confirm('Bạn có chắc muốn xóa?')">❌ Xóa</a>
    </td>
</tr>
<?php endwhile; ?>
</table>

</body>
</html>