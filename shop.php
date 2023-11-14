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

// Bao gồm file chứa các chức năng liên quan đến wishlist và cart
include 'components/wishlist_cart.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Các thẻ meta và link đến các tệp CSS -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>shop</title>

    <!-- Link đến Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Link đến tệp CSS tùy chỉnh -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <?php include 'components/user_header.php'; ?>

    <!-- Phần hiển thị danh sách sản phẩm -->
    <section class="products">

        <h1 class="heading">Sản phẩm mới nhất</h1>

        <div class="box-container">

            <?php
            // Truy vấn để lấy ra tất cả sản phẩm từ bảng products
            $select_products = $conn->prepare("SELECT * FROM `products`");
            $select_products->execute();

            // Kiểm tra xem có sản phẩm nào hay không
            if ($select_products->rowCount() > 0) {
                // Lặp qua các sản phẩm và hiển thị thông tin của mỗi sản phẩm
                while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
            ?>
                    <!-- Mỗi sản phẩm được hiển thị trong một form -->
                    <form action="" method="post" class="box">
                        <!-- Các trường ẩn chứa thông tin sản phẩm -->
                        <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
                        <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
                        <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
                        <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
                        
                        <!-- Nút "Thêm vào Wishlist" -->
                        <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
                        
                        <!-- Nút "Quick View" -->
                        <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a>
                        
                        <!-- Hiển thị ảnh sản phẩm -->
                        <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
                        
                        <!-- Hiển thị tên sản phẩm -->
                        <div class="name"><?= $fetch_product['name']; ?></div>
                        
                        <!-- Hiển thị giá và input chọn số lượng -->
                        <div class="flex">
                            <div class="price"><span>$</span><?= $fetch_product['price']; ?><span>/-</span></div>
                            <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
                        </div>
                        
                        <!-- Nút "Thêm vào giỏ hàng" -->
                        <input type="submit" value="add to cart" class="btn" name="add_to_cart">
                    </form>
            <?php
                }
            } else {
                // Hiển thị thông báo nếu không có sản phẩm nào
                echo '<p class="empty">Không tìm thấy sản phẩm!</p>';
            }
            ?>

        </div>

    </section>

    <!-- Bao gồm file footer.php -->
    <?php include 'components/footer.php'; ?>

    <!-- Đường dẫn đến tệp script.js -->
    <script src="js/script.js"></script>

</body>

</html>
