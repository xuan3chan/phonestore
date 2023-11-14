<?php

// Kết nối đến cơ sở dữ liệu thông qua file connect.php
include 'components/connect.php';

// Bắt đầu phiên làm việc
session_start();

// Kiểm tra xem có user_id trong session không, nếu có lấy giá trị, không thì để trống
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
};

// Kiểm tra nếu form đã được submit
if (isset($_POST['submit'])) {

    // Lấy thông tin từ form và làm sạch dữ liệu
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    $cpass = sha1($_POST['cpass']);
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

    // Truy vấn kiểm tra xem email đã tồn tại chưa
    $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
    $select_user->execute([$email,]);
    $row = $select_user->fetch(PDO::FETCH_ASSOC);

    // Kiểm tra xem email đã tồn tại hay chưa
    if ($select_user->rowCount() > 0) {
        $message[] = 'Email đã tồn tại!';
    } else {
        // Nếu email chưa tồn tại, kiểm tra password và confirm password
        if ($pass != $cpass) {
            $message[] = 'Mật khẩu xác nhận không khớp!';
        } else {
            // Nếu mọi thứ đều hợp lệ, thêm thông tin người dùng vào cơ sở dữ liệu
            $insert_user = $conn->prepare("INSERT INTO `users`(name, email, password) VALUES(?,?,?)");
            $insert_user->execute([$name, $email, $cpass]);
            $message[] = 'Đăng ký thành công, hãy đăng nhập ngay!';
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
    <title>register</title>

    <!-- Link đến Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Link đến tệp CSS tùy chỉnh -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <?php include 'components/user_header.php'; ?>

    <!-- Phần hiển thị form đăng ký -->
    <section class="form-container">

        <form action="" method="post">
            <h3>Đăng ký ngay</h3>
            <input type="text" name="name" required placeholder="Nhập tên người dùng" maxlength="20" class="box">
            <input type="email" name="email" required placeholder="Nhập địa chỉ email" maxlength="50" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="pass" required placeholder="Nhập mật khẩu" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="cpass" required placeholder="Xác nhận mật khẩu" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="submit" value="Đăng ký ngay" class="btn" name="submit">
            <p>Đã có tài khoản?</p>
            <a href="user_login.php" class="option-btn">Đăng nhập ngay</a>
        </form>

    </section>

    <!-- Bao gồm file footer.php -->
    <?php include 'components/footer.php'; ?>

    <!-- Đường dẫn đến tệp script.js -->
    <script src="js/script.js"></script>

</body>

</html>
