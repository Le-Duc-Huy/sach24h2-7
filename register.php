<?php include 'includes/header.php'; ?>
<div class="register-wrapper">
    <div class="register-card">
        <h2>📚 Đăng ký tài khoản</h2>
        <form action="process_register.php" method="POST">
            <input type="text" name="fullname" placeholder="📖 Họ tên" required>
            <input type="text" name="username" placeholder="✏️ Tên đăng nhập" required>
            <input type="password" name="password" placeholder="🔒 Mật khẩu" required>
            <input type="email" name="email" placeholder="📧 Email" required>
            <button type="submit">📝 Đăng ký</button>
        </form>
        <p>Đã có tài khoản? <a href="login.php">Đăng nhập ngay</a></p>
    </div>
</div>
<?php include 'includes/footer.php'; ?>