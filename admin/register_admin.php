<?php

// Kết nối đến cơ sở dữ liệu thông qua file connect.php
include '../components/connect.php';

// Bắt đầu phiên làm việc
session_start();

// Lấy admin_id từ session, nếu không tồn tại thì chuyển hướng đến trang đăng nhập admin
$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}

// Kiểm tra nếu form đã được submit
if (isset($_POST['submit'])) {

    // Lấy thông tin từ form và làm sạch dữ liệu
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    $cpass = sha1($_POST['cpass']);
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

    // Kiểm tra xem username đã tồn tại chưa
    $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE name = ?");
    $select_admin->execute([$name]);

    if ($select_admin->rowCount() > 0) {
        $message[] = 'Tên người dùng đã tồn tại!';
    } else {
        // Nếu tên người dùng chưa tồn tại, kiểm tra xác nhận mật khẩu
        if ($pass != $cpass) {
            $message[] = 'Mật khẩu xác nhận không khớp!';
        } else {
            // Nếu mọi thông tin hợp lệ, thêm admin mới vào cơ sở dữ liệu
            $insert_admin = $conn->prepare("INSERT INTO `admins`(name, password) VALUES(?,?)");
            $insert_admin->execute([$name, $cpass]);
            $message[] = 'Đăng ký admin mới thành công!';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Các thẻ meta và link đến các tệp CSS -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register admin</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <link rel="stylesheet" href="../css/admin_style.css">

</head>

<body>

    <?php include '../components/admin_header.php'; ?>

    <!-- Phần hiển thị form đăng ký -->
    <section class="form-container">
        <form action="" method="post">
            <h3>Đăng ký ngay</h3>
            <input type="text" name="name" required placeholder="Nhập tên đăng nhập" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="pass" required placeholder="Nhập mật khẩu" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="cpass" required placeholder="Xác nhận mật khẩu" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="submit" value="Đăng ký ngay" class="btn" name="submit">
        </form>
    </section>

    <!-- Các thẻ script và đường dẫn đến các tệp script -->
    <script src="../js/admin_script.js"></script>

</body>

</html>
