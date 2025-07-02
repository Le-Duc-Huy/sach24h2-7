<?php
include 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT);

    // Kiểm tra trùng username
    $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "Tên đăng nhập đã tồn tại. Vui lòng chọn tên khác.";
    } else {
        // Thêm người dùng mới
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);

        if ($stmt->execute()) {
            echo "Đăng ký thành công. <a href='login.php'>Đăng nhập tại đây</a>.";
        } else {
            echo "Có lỗi xảy ra khi đăng ký.";
        }

        $stmt->close();
    }

    $check->close();
    $conn->close();
}
?>
