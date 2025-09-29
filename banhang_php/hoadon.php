<?php
session_start();
include 'db.php';

// Xóa hóa đơn nếu có yêu cầu
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    // Xóa chi tiết trước, rồi xóa hóa đơn
    $conn->query("DELETE FROM ChiTietHoaDon WHERE MaHD='$id'");
    $conn->query("DELETE FROM HoaDon WHERE MaHD='$id'");
    header("Location: hoadon.php");
    exit;
}

// Lấy danh sách hóa đơn + khách hàng
$sql = "SELECT hd.MaHD, hd.NgayLap, hd.TongTien, kh.TenKH, kh.SoDienThoai 
        FROM HoaDon hd 
        JOIN KhachHang kh ON hd.MaKH = kh.MaKH
        ORDER BY hd.NgayLap DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Danh sách hóa đơn</title>
<style>
    body { font-family: Arial; background:#f8f9fa; padding:30px; }
    .box { max-width:900px; margin:auto; background:#fff; padding:20px; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.1);}
    h2 { text-align:center; margin-bottom:20px; }
    table { width:100%; border-collapse:collapse; }
    th,td { border:1px solid #ddd; padding:10px; text-align:center; }
    th{ background:#f4f4f4; }
    a.btn { padding:6px 12px; border-radius:4px; text-decoration:none; color:#fff; margin:2px; display:inline-block;}
    .view { background:#007bff; }
    .delete { background:#dc3545; }
    .home { background:#28a745; }
</style>
</head>
<body>

<div class="box">
    <h2>📑 Danh sách hóa đơn</h2>
    <div style="text-align:right; margin-bottom:10px;">
        <a class="btn home" href="index.php">🏠 Trang chủ</a>
    </div>
    <table>
        <tr>
            <th>Mã HĐ</th>
            <th>Ngày lập</th>
            <th>Khách hàng</th>
            <th>SĐT</th>
            <th>Tổng tiền</th>
            <th>Hành động</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['MaHD'] ?></td>
            <td><?= $row['NgayLap'] ?></td>
            <td><?= $row['TenKH'] ?></td>
            <td><?= $row['SoDienThoai'] ?></td>
            <td><?= number_format($row['TongTien']) ?> đ</td>
            <td>
                <a class="btn view" href="chitiethoadon.php?id=<?= $row['MaHD'] ?>">🔍 Xem</a>
                <a class="btn delete" href="?delete=<?= $row['MaHD'] ?>" onclick="return confirm('Bạn có chắc muốn xóa hóa đơn này?')">❌ Xóa</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>