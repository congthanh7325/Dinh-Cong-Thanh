<?php
include 'db.php';

if (!isset($_GET['id'])) {
    die("Thiếu mã sản phẩm cần xóa.");
}

$ma = $_GET['id'];

// Xóa sản phẩm theo MaDT
$stmt = $conn->prepare("DELETE FROM DienThoai WHERE MaDT = ?");
$stmt->bind_param("s", $ma);

if ($stmt->execute()) {
    // Quay về admin sau khi xóa thành công
    header("Location: admin.php?msg=deleted");
    exit;
} else {
    echo "Lỗi khi xóa sản phẩm: " . $conn->error;
}

$stmt->close();
$conn->close();
?>