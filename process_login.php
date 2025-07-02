<?php
session_start();
include 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

  $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            // ✅ Lưu session
            $_SESSION['user'] = $username;
            $_SESSION['role'] = $user['role']; 
            // ✅ Cập nhật last_login một lần duy nhất
            $now = date('Y-m-d H:i:s');
            $updateLogin = $conn->prepare("UPDATE users SET last_login = ? WHERE username = ?");
            $updateLogin->bind_param("ss", $now, $username);
            $updateLogin->execute();

            header("Location: index.php");
            exit;
        } else {
            echo "Sai mật khẩu.";
        }
    } else {
        echo "Không tìm thấy tài khoản.";
    }

    $stmt->close();
    $conn->close();
}
?>