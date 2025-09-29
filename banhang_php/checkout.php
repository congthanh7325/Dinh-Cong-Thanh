<?php
session_start();
include 'db.php';

// Nếu giỏ hàng trống thì quay lại
if (empty($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
    if (empty($_SESSION['cart'])) {
        header("Location: index.php");
        exit;
    }
}

$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten    = $_POST['TenKH'];
    $diachi = $_POST['DiaChi'];
    $sdt    = $_POST['SoDienThoai'];
    $email  = $_POST['Email'];

    // ===== Tự sinh MaKH kiểu KH001 =====
    $res = $conn->query("SELECT MAX(MaKH) AS maxid FROM KhachHang");
    $row = $res->fetch_assoc();
    $nextId = $row['maxid'] ? (int)substr($row['maxid'], 2) + 1 : 1;
    $maKH = "KH" . str_pad($nextId, 3, "0", STR_PAD_LEFT);

    // Thêm khách hàng
    $stmt = $conn->prepare("INSERT INTO KhachHang(MaKH,TenKH,DiaChi,SoDienThoai,Email) VALUES (?,?,?,?,?)");
    $stmt->bind_param("sssss", $maKH, $ten, $diachi, $sdt, $email);
    $stmt->execute();
    $stmt->close();

    // ===== Tính tổng tiền =====
    $tong = 0;
    foreach ($_SESSION['cart'] as $sp) {
        if (!is_array($sp)) continue;
        $tong += $sp['GiaBan'] * $sp['qty'];
    }

    // ===== Sinh MaHD kiểu HD001 =====
    $res = $conn->query("SELECT MAX(MaHD) AS maxid FROM HoaDon");
    $row = $res->fetch_assoc();
    $nextId = $row['maxid'] ? (int)substr($row['maxid'], 2) + 1 : 1;
    $maHD = "HD" . str_pad($nextId, 3, "0", STR_PAD_LEFT);

    // Thêm hóa đơn
    $stmt = $conn->prepare("INSERT INTO HoaDon(MaHD,NgayLap,MaKH,TongTien) VALUES (?,?,?,?)");
    $ngay = date("Y-m-d");
    $stmt->bind_param("sssi", $maHD, $ngay, $maKH, $tong);
    $stmt->execute();
    $stmt->close();

    // Thêm chi tiết hóa đơn (không có GiaBan)
    $stmt = $conn->prepare("INSERT INTO ChiTietHoaDon(MaHD,MaDT,SoLuong) VALUES (?,?,?)");
    foreach ($_SESSION['cart'] as $sp) {
        if (!is_array($sp)) continue;
        $stmt->bind_param("ssi", $maHD, $sp['MaDT'], $sp['qty']);
        $stmt->execute();
    }
    $stmt->close();

    // Xóa giỏ hàng
    $_SESSION['cart'] = [];
    $msg = "✅ Đặt hàng thành công! Mã hóa đơn: $maHD";
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Thanh toán</title>
<style>
    body { font-family: Arial; background:#f8f9fa; padding:30px; }
    .checkout { max-width:800px; margin:auto; background:#fff; padding:20px; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.1);}
    h2 { text-align:center; margin-bottom:20px; }
    table { width:100%; border-collapse:collapse; margin-top:15px;}
    th,td { border:1px solid #ddd; padding:8px; text-align:center; }
    th{ background:#f4f4f4; }
    label{ display:block; margin:8px 0 3px; font-weight:bold; }
    input{ width:100%; padding:8px; border:1px solid #ccc; border-radius:4px; }
    .btn{ padding:10px 15px; border:none; border-radius:5px; cursor:pointer; margin:5px; text-decoration:none; display:inline-block; }
    .success{ background:#28a745; color:#fff; }
    .back{ background:#007bff; color:#fff; }
    .invoice{ background:#17a2b8; color:#fff; }
    .msg{ text-align:center; color:green; font-weight:bold; margin:15px 0; }
</style>
</head>
<body>

<div class="checkout">
    <h2>🧾 Thanh toán</h2>

    <?php if ($msg): ?>
        <p class="msg"><?= $msg ?></p>
        <div style="text-align:center;">
            <a href="index.php" class="btn back">⬅️ Về trang chủ</a>
            <a href="hoadon.php" class="btn invoice">📑 Xem hóa đơn</a>
        </div>
    <?php else: ?>
    <form method="post">
        <label>Họ tên</label>
        <input type="text" name="TenKH" required>

        <label>Địa chỉ</label>
        <input type="text" name="DiaChi" required>

        <label>Số điện thoại</label>
        <input type="text" name="SoDienThoai" required>

        <label>Email</label>
        <input type="email" name="Email" required>

        <h3>🛒 Giỏ hàng</h3>
        <table>
            <tr><th>Mã</th><th>Tên</th><th>SL</th><th>Thành tiền</th></tr>
            <?php $tong=0; foreach($_SESSION['cart'] as $sp): 
                if (!is_array($sp)) continue;
                $tt=$sp['GiaBan']*$sp['qty']; $tong+=$tt; ?>
            <tr>
                <td><?= $sp['MaDT'] ?></td>
                <td><?= $sp['TenDT'] ?></td>
                <td><?= $sp['qty'] ?></td>
                <td><?= number_format($tt) ?> đ</td>
            </tr>
            <?php endforeach; ?>
        </table>
        <p style="text-align:right; font-weight:bold; margin-top:10px;">Tổng: <?= number_format($tong) ?> đ</p>

        <button class="btn success" type="submit">✅ Xác nhận đặt hàng</button>
    </form>
    <?php endif; ?>
</div>

</body>
</html>