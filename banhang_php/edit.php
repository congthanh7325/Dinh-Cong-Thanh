<?php
include 'db.php';

if (!isset($_GET['id'])) {
    die("Thiếu mã sản phẩm.");
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM DienThoai WHERE MaDT = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();

if (!$row = $result->fetch_assoc()) {
    die("Không tìm thấy sản phẩm.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten = $_POST['TenDT'];
    $hang = $_POST['ThuongHieu'];
    $cauhinh = $_POST['CauHinh'];
    $gia = $_POST['GiaBan'];

    $update = $conn->prepare("UPDATE DienThoai SET TenDT=?, ThuongHieu=?, CauHinh=?, GiaBan=? WHERE MaDT=?");
    $update->bind_param("sssdss", $ten, $hang, $cauhinh, $gia, $id);

    if ($update->execute()) {
        header("Location: admin.php?msg=updated");
        exit;
    } else {
        $error = "Lỗi cập nhật: " . $conn->error;
    }
    $update->close();
}
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Sửa sản phẩm</title>
<style>
    body { font-family: Arial, sans-serif; background: #f9f9f9; padding: 30px; }
    .container { max-width: 500px; margin: auto; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    h2 { text-align: center; margin-bottom: 20px; color: #333; }
    label { display: block; margin: 10px 0 5px; font-weight: bold; color: #444; }
    input, textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; }
    textarea { resize: vertical; min-height: 60px; }
    .btn { display: inline-block; padding: 10px 20px; border: none; border-radius: 5px; background: #ffc107; color: #fff; text-decoration: none; cursor: pointer; margin-top: 15px; }
    .btn:hover { background: #e0a800; }
    .back { background: #007bff; margin-left: 10px; }
    .back:hover { background: #0056b3; }
    .error { color: red; margin-bottom: 10px; text-align: center; }
</style>
</head>
<body>

<div class="container">
    <h2>✏️ Sửa sản phẩm</h2>

    <?php if (!empty($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="post">
        <label for="MaDT">Mã sản phẩm</label>
        <input type="text" name="MaDT" id="MaDT" value="<?= htmlspecialchars($row['MaDT']) ?>" disabled>

        <label for="TenDT">Tên điện thoại</label>
        <input type="text" name="TenDT" id="TenDT" value="<?= htmlspecialchars($row['TenDT']) ?>" required>

        <label for="ThuongHieu">Hãng</label>
        <input type="text" name="ThuongHieu" id="ThuongHieu" value="<?= htmlspecialchars($row['ThuongHieu']) ?>" required>

        <label for="CauHinh">Cấu hình</label>
        <textarea name="CauHinh" id="CauHinh" required><?= htmlspecialchars($row['CauHinh']) ?></textarea>

        <label for="GiaBan">Giá bán</label>
        <input type="number" name="GiaBan" id="GiaBan" step="1000" value="<?= htmlspecialchars($row['GiaBan']) ?>" required>

        <button type="submit" class="btn">💾 Cập nhật</button>
        <a href="admin.php" class="btn back">⬅️ Quay lại</a>
    </form>
</div>

</body>
</html>