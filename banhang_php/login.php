<?php
session_start();
include 'db.php';

$msg = "";

// Tạo admin nếu chưa có
$admin_username = "admin";
$admin_password_plain = "admin123";
$admin_role = "admin";

// Kiểm tra admin đã tồn tại chưa
$stmt = $conn->prepare("SELECT * FROM Users WHERE username=?");
$stmt->bind_param("s", $admin_username);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
$stmt->close();

if (!$admin) {
    $hash = password_hash($admin_password_plain, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO Users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $admin_username, $hash, $admin_role);
    $stmt->execute();
    $stmt->close();
    // echo "Admin đã được tạo!";
}

// Xử lý login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM Users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            "id" => $user['id'],
            "username" => $user['username'],
            "role" => strtolower(trim($user['role']))
        ];

        // Redirect dựa role
        if ($_SESSION['user']['role'] === 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: index.php");
        }
        exit;
    } else {
        $msg = "❌ Sai tên đăng nhập hoặc mật khẩu!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Đăng nhập</title>
<style>
body { font-family: Arial; background:#f8f9fa; padding:40px;}
.form { max-width:400px; margin:auto; background:#fff; padding:20px; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.1);}
h2 { text-align:center; }
label { display:block; margin:10px 0 5px; }
input { width:100%; padding:8px; border:1px solid #ccc; border-radius:4px; }
button { margin-top:15px; width:100%; padding:10px; border:none; border-radius:5px; background:#007bff; color:#fff; font-weight:bold; cursor:pointer;}
p { text-align:center; color:#d00; }
a { color:#007bff; text-decoration:none; }
</style>
</head>
<body>
<div class="form">
    <h2>🔐 Đăng nhập</h2>
    <?php if($msg) echo "<p>$msg</p>"; ?>
    <form method="post">
        <label>Tên đăng nhập</label>
        <input type="text" name="username" required>

        <label>Mật khẩu</label>
        <input type="password" name="password" required>

        <button type="submit">Đăng nhập</button>
    </form>
    <p>Chưa có tài khoản? <a href="register.php">Đăng ký</a></p>
</div>
</body>
</html>
