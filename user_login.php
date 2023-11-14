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
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);

    // Truy vấn kiểm tra thông tin đăng nhập
    $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
    $select_user->execute([$email, $pass]);
    $row = $select_user->fetch(PDO::FETCH_ASSOC);

    // Kiểm tra xem thông tin đăng nhập có hợp lệ không
    if ($select_user->rowCount() > 0) {
        // Nếu hợp lệ, lưu user_id vào session và chuyển hướng đến trang home.php
        $_SESSION['user_id'] = $row['id'];
        header('location:home.php');
    } else {
        // Nếu không hợp lệ, thông báo lỗi
        $message[] = 'Tên người dùng hoặc mật khẩu không đúng!';
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
    <title>login</title>

    <!-- Link đến Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Link đến tệp CSS tùy chỉnh -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <?php include 'components/user_header.php'; ?>

    <!-- Phần hiển thị form đăng nhập -->
    <section class="form-container">

        <form action="" method="post">
            <h3>Đăng nhập ngay</h3>
            <input type="email" name="email" required placeholder="Nhập địa chỉ email" maxlength="50" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="pass" required placeholder="Nhập mật khẩu" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="submit" value="Đăng nhập ngay" class="btn" name="submit">
            <p>Chưa có tài khoản?</p>
            <a href="user_register.php" class="option-btn">Đăng ký ngay</a>
        </form>

    </section>

    <!-- Bao gồm file footer.php -->
    <?php include 'components/footer.php'; ?>

    <!-- Đường dẫn đến tệp script.js -->
    <script src="js/script.js"></script>

</body>

</html>
