<?php
session_start();
include 'db.php';

$id = $_GET['id'] ?? '';

// Lấy thông tin hóa đơn + khách hàng
$sql = "SELECT hd.MaHD, hd.NgayLap, kh.TenKH, kh.DiaChi, kh.SoDienThoai, kh.Email, hd.TongTien
        FROM HoaDon hd 
        JOIN KhachHang kh ON hd.MaKH = kh.MaKH
        WHERE hd.MaHD = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id);
$stmt->execute();
$hoadon = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Lấy chi tiết hóa đơn
$sql = "SELECT cthd.*, dt.TenDT, dt.GiaBan 
        FROM ChiTietHoaDon cthd
        JOIN DienThoai dt ON cthd.MaDT = dt.MaDT
        WHERE cthd.MaHD = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id);
$stmt->execute();
$chitiet = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Chi tiết hóa đơn</title>
<style>
    body { font-family: Arial; background:#f8f9fa; padding:30px; }
    .box { max-width:900px; margin:auto; background:#fff; padding:20px; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.1);}
    h2 { text-align:center; margin-bottom:20px; }
    table { width:100%; border-collapse:collapse; margin-top:15px;}
    th,td { border:1px solid #ddd; padding:10px; text-align:center; }
    th{ background:#f4f4f4; }
    .btn { padding:8px 14px; border-radius:4px; text-decoration:none; color:#fff; margin:5px; display:inline-block;}
    .back { background:#007bff; }
    .print { background:#28a745; }
    .home { background:#17a2b8; }
</style>
</head>
<body>

<div class="box">
    <h2>🧾 Chi tiết hóa đơn #<?= $hoadon['MaHD'] ?></h2>
    <p><b>Ngày lập:</b> <?= $hoadon['NgayLap'] ?></p>
    <p><b>Khách hàng:</b> <?= $hoadon['TenKH'] ?> - <?= $hoadon['SoDienThoai'] ?></p>
    <p><b>Địa chỉ:</b> <?= $hoadon['DiaChi'] ?></p>
    <p><b>Email:</b> <?= $hoadon['Email'] ?></p>

    <table>
        <tr><th>Mã ĐT</th><th>Tên ĐT</th><th>Số lượng</th><th>Giá</th><th>Thành tiền</th></tr>
        <?php while($row = $chitiet->fetch_assoc()): 
            $tt = $row['SoLuong'] * $row['GiaBan']; ?>
        <tr>
            <td><?= $row['MaDT'] ?></td>
            <td><?= $row['TenDT'] ?></td>
            <td><?= $row['SoLuong'] ?></td>
            <td><?= number_format($row['GiaBan']) ?> đ</td>
            <td><?= number_format($tt) ?> đ</td>
        </tr>
        <?php endwhile; ?>
    </table>
    <p style="text-align:right; font-weight:bold; margin-top:10px;">Tổng: <?= number_format($hoadon['TongTien']) ?> đ</p>

    <div style="text-align:center;">
        <a class="btn back" href="hoadon.php">⬅️ Danh sách</a>
        <a class="btn home" href="index.php">🏠 Trang chủ</a>
        <button class="btn print" onclick="window.print()">🖨️ In hóa đơn</button>
    </div>
</div>

</body>
</html>