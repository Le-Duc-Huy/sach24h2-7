<?php include 'includes/header.php'; ?>
<div class="login-wrapper">
    <div class="login-card">

        <h2><img src="assets/images/open-book.jpg" alt="Book"
                style="height: 24px; vertical-align: middle; margin-right: 5px;"> Đăng nhập
        </h2>

        <form action="process_login.php" method="POST">
            <input type="text" name="username" placeholder="Tên đăng nhập" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>

            <!-- Nút đăng nhập được làm khung đều và hiệu ứng -->
            <button type="submit" class="login-btn">
                <span class="icon">📖</span> Đăng nhập
            </button>
        </form>
        <p>Bạn chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
    </div>
</div>
<?php include 'includes/footer.php'; ?>

