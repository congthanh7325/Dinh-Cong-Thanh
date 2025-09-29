<?php
include 'db.php';

if (!isset($_GET['id'])) {
    die("Không có sản phẩm được chọn.");
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM DienThoai WHERE MaDT = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()):
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Chi tiết sản phẩm</title>
<style>
    body { font-family: Arial, sans-serif; background: #f9f9f9; padding: 30px; }
    .detail { max-width: 600px; margin: auto; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); text-align: center; }
    .detail img { width: 300px; height: 300px; object-fit: cover; border-radius: 8px; margin-bottom: 20px; }
    h2 { color: #333; margin-bottom: 10px; }
    p { margin: 8px 0; color: #444; }
    .price { font-size: 20px; font-weight: bold; color: #d70018; margin: 15px 0; }
    .btn { background: #28a745; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px; display: inline-block; }
    .btn:hover { background: #218838; }
    .back { background: #007bff; }
    .back:hover { background: #0056b3; }
</style>
</head>
<body>

<div class="detail">
    <?php 
    $img = "images/".$row['MaDT'].".jpg"; 
    if (!file_exists($img)) $img = "images/default.jpg";
    ?>
    <img src="<?= $img ?>" alt="<?= htmlspecialchars($row['TenDT']) ?>">

    <h2><?= htmlspecialchars($row['TenDT']) ?></h2>
    <p><b>Mã:</b> <?= htmlspecialchars($row['MaDT']) ?></p>
    <p><b>Hãng:</b> <?= htmlspecialchars($row['ThuongHieu']) ?></p>
    <p><b>Cấu hình:</b> <?= htmlspecialchars($row['CauHinh']) ?></p>
    <p class="price"><?= number_format($row['GiaBan']) ?> đ</p>

    <a class="btn" href="cart.php?add=<?= urlencode($row['MaDT']) ?>">🛒 Thêm vào giỏ</a>
    <a class="btn back" href="index.php">⬅️ Quay lại</a>
</div>

</body>
</html>
<?php
else:
    echo "Không tìm thấy sản phẩm.";
endif;

$stmt->close();
$conn->close();
?>