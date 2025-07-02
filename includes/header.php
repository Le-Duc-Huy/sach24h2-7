<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>sach24h - Đọc sách online miễn phí</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <header>
        <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
            <!-- Logo -->
            <a href="index.php">
                <img src="assets/images/logo1.jpg" alt="logo" style="height: 50px;">
            </a>

            <!-- Menu -->
            <nav style="display: flex; gap: 30px;">
                <a href="index.php">Trang chủ</a>
                <a href="library.php">Thư viện sách</a>
                <a href="send_story.php">Đăng tải truyện </a>
                <a href="Latest_story.php">Lịch sử đăng truyện</a>
                <a href="contact.php">Liên hệ</a>
            </nav>

            <!-- Đăng nhập -->
                      <?php if (isset($_SESSION['user'])): ?>
            <a href="logout.php"
                style="background-color: #28a745; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none;">Đăng
                xuất</a>
            <?php else: ?>
            <a href="login.php"
                style="background-color: #28a745; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none;">Đăng
                nhập</a>
            <?php endif; ?>


        </div>
    </header>