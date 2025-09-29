<?php
session_start();
include 'db.php';

// Tạo giỏ hàng nếu chưa có
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

// Thêm sản phẩm
if (isset($_GET['add'])) {
    $id = $_GET['add'];
    $res = $conn->query("SELECT * FROM DienThoai WHERE MaDT='$id'");
    if ($sp = $res->fetch_assoc()) {
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['qty']++;
        } else {
            $_SESSION['cart'][$id] = [
                "MaDT"=>$sp['MaDT'],
                "TenDT"=>$sp['TenDT'],
                "GiaBan"=>$sp['GiaBan'],
                "qty"=>1
            ];
        }
    }
}

// Xóa sản phẩm
if (isset($_GET['remove'])) unset($_SESSION['cart'][$_GET['remove']]);

// Cập nhật số lượng
if (!empty($_POST['qty'])) {
    foreach ($_POST['qty'] as $id=>$q) {
        if ($q<=0) unset($_SESSION['cart'][$id]);
        else $_SESSION['cart'][$id]['qty']=$q;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Giỏ hàng</title>
<style>
    body { font-family: Arial; background:#f8f9fa; padding:30px; }
    .cart { max-width:800px; margin:auto; background:#fff; padding:20px; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.1);}
    h2 { text-align:center; margin-bottom:20px; }
    table { width:100%; border-collapse:collapse; }
    th,td { border:1px solid #ddd; padding:10px; text-align:center; }
    th { background:#f4f4f4; }
    input[type=number]{ width:50px; }
    .btn{ padding:6px 12px; border-radius:4px; text-decoration:none; color:#fff; margin:3px; display:inline-block; }
    .update{ background:#ffc107; }
    .remove{ background:#dc3545; }
    .checkout{ background:#28a745; }
    .back{ background:#007bff; }
    .invoice{ background:#17a2b8; }
    .total{ text-align:right; margin:15px 0; font-weight:bold; color:#d70018; }
</style>
</head>
<body>

<div class="cart">
    <h2>🛒 Giỏ hàng</h2>

    <?php if ($_SESSION['cart']): ?>
    <form method="post">
        <table>
            <tr><th>Mã</th><th>Tên</th><th>Giá</th><th>SL</th><th>Thành tiền</th><th></th></tr>
            <?php $tong=0; foreach($_SESSION['cart'] as $sp): $tt=$sp['GiaBan']*$sp['qty']; $tong+=$tt; ?>
            <tr>
                <td><?= $sp['MaDT'] ?></td>
                <td><?= $sp['TenDT'] ?></td>
                <td><?= number_format($sp['GiaBan']) ?> đ</td>
                <td><input type="number" name="qty[<?= $sp['MaDT'] ?>]" value="<?= $sp['qty'] ?>" min="0"></td>
                <td><?= number_format($tt) ?> đ</td>
                <td><a class="btn remove" href="?remove=<?= $sp['MaDT'] ?>">❌</a></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <div class="total">Tổng cộng: <?= number_format($tong) ?> đ</div>
        <button class="btn update" type="submit">🔄 Cập nhật</button>
        <a class="btn checkout" href="checkout.php">✅ Thanh toán</a>
        <a class="btn back" href="index.php">⬅️ Tiếp tục</a>
        <a class="btn invoice" href="hoadon.php">📑 Xem hóa đơn</a>
    </form>
    <?php else: ?>
        <p style="text-align:center;color:#666;">😢 Giỏ hàng trống</p>
        <div style="text-align:center;">
            <a class="btn back" href="index.php">⬅️ Mua hàng</a>
            <a class="btn invoice" href="hoadon.php">📑 Xem hóa đơn</a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>