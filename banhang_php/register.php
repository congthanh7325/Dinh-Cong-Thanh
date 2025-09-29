<?php
session_start();
include 'db.php';

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = 'user'; // mặc định là user

    if ($username && $password) {
        // Mã hóa mật khẩu
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        // Thêm vào CSDL
        $stmt = $conn->prepare("INSERT INTO Users(username,password,role) VALUES (?,?,?)");
        $stmt->bind_param("sss", $username, $hashed, $role);

        if ($stmt->execute()) {
            $msg = "✅ Đăng ký thành công! Bạn có thể đăng nhập.";
        } else {
            $msg = "❌ Lỗi: " . $conn->error;
        }
        $stmt->close();
    } else {
        $msg = "⚠️ Vui lòng nhập đầy đủ thông tin!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Đăng ký</title>
<style>
body { font-family: Arial; background:#f8f9fa; padding:40px;}
.form { max-width:400px; margin:auto; background:#fff; padding:20px; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.1);}
h2 { text-align:center; }
label { display:block; margin:10px 0 5px; }
input { width:100%; padding:8px; border:1px solid #ccc; border-radius:4px; }
button { margin-top:15px; width:100%; padding:10px; border:none; border-radius:5px; background:#28a745; color:#fff; font-weight:bold; cursor:pointer;}
p { text-align:center; color:#d00; }
a { color:#007bff; text-decoration:none; }
</style>
</head>
<body>
<div class="form">
    <h2>📝 Đăng ký</h2>
    <?php if($msg) echo "<p>$msg</p>"; ?>
    <form method="post">
        <label>Tên đăng nhập</label>
        <input type="text" name="username" required>

        <label>Mật khẩu</label>
        <input type="password" name="password" required>

        <button type="submit">Đăng ký</button>
    </form>
    <p>Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
</div>
</body>
</html>