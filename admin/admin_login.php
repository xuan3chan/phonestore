<?php

// Kết nối đến cơ sở dữ liệu
include '../components/connect.php';

// Bắt đầu phiên làm việc
session_start();

// Xử lý khi người dùng submit form đăng nhập
if(isset($_POST['submit'])){

   // Lấy giá trị tên đăng nhập và mật khẩu từ form
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   // Tìm kiếm thông tin admin trong cơ sở dữ liệu
   $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE name = ? AND password = ?");
   $select_admin->execute([$name, $pass]);
   $row = $select_admin->fetch(PDO::FETCH_ASSOC);

   // Nếu tìm thấy thông tin admin, lưu session và chuyển hướng đến trang dashboard
   if($select_admin->rowCount() > 0){
      $_SESSION['admin_id'] = $row['id'];
      header('location:dashboard.php');
   }else{
      // Nếu không tìm thấy thông tin admin, hiển thị thông báo lỗi
      $message[] = 'Tên đăng nhập hoặc mật khẩu không đúng!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Đăng nhập</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php
   // Hiển thị thông báo lỗi nếu có
   if(isset($message)){
      foreach($message as $message){
         echo '
         <div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }
?>

<section class="form-container">

   <form action="" method="post">
      <h3>Đăng nhập</h3>
      <p>Tên đăng nhập mặc định = <span>admin</span> & mật khẩu mặc định = <span>111</span></p>
      <input type="text" name="name" required placeholder="Nhập tên đăng nhập" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="Nhập mật khẩu" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Đăng nhập" class="btn" name="submit">
   </form>

</section>
   
</body>
</html>