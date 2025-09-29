<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ma = $_POST['MaDT'];
    $ten = $_POST['TenDT'];
    $hang = $_POST['ThuongHieu'];
    $cauhinh = $_POST['CauHinh'];
    $gia = $_POST['GiaBan'];

    $stmt = $conn->prepare("INSERT INTO DienThoai (MaDT, TenDT, ThuongHieu, CauHinh, GiaBan) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssd", $ma, $ten, $hang, $cauhinh, $gia);

    if ($stmt->execute()) {
        header("Location: admin.php?msg=added");
        exit;
    } else {
        $error = "Lỗi thêm sản phẩm: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Thêm sản phẩm</title>
<link rel="stylesheet" href="style.css">
<style>
    body { font-family: Arial, sans-serif; background: #f9f9f9; padding: 30px; }
    .container { max-width: 500px; margin: auto; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    h2 { text-align: center; margin-bottom: 20px; color: #333; }
    label { display: block; margin: 10px 0 5px; font-weight: bold; color: #444; }
    input, textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; }
    textarea { resize: vertical; min-height: 60px; }
    .btn { display: inline-block; padding: 10px 20px; border: none; border-radius: 5px; background: #28a745; color: #fff; text-decoration: none; cursor: pointer; margin-top: 15px; }
    .btn:hover { background: #218838; }
    .back { background: #007bff; margin-left: 10px; }
    .back:hover { background: #0056b3; }
    .error { color: red; margin-bottom: 10px; text-align: center; }
</style>
</head>
<body>

<div class="container">
    <h2>➕ Thêm sản phẩm mới</h2>

    <?php if (!empty($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="post">
        <label for="MaDT">Mã sản phẩm</label>
        <input type="text" name="MaDT" id="MaDT" required>

        <label for="TenDT">Tên điện thoại</label>
        <input type="text" name="TenDT" id="TenDT" required>

        <label for="ThuongHieu">Hãng</label>
        <input type="text" name="ThuongHieu" id="ThuongHieu" required>

        <label for="CauHinh">Cấu hình</label>
        <textarea name="CauHinh" id="CauHinh" required></textarea>

        <label for="GiaBan">Giá bán</label>
        <input type="number" name="GiaBan" id="GiaBan" step="1000" required>

        <button type="submit" class="btn">💾 Lưu sản phẩm</button>
        <a href="admin.php" class="btn back">⬅️ Quay lại</a>
    </form>
</div>

</body>
</html>