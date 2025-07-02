<?php
session_start();
include 'includes/db.php';

if (isset($_SESSION['user'])) {
    $username = $_SESSION['user'];
    $now = date('Y-m-d H:i:s');

    // ✅ Cập nhật thời gian đăng xuất
    $updateLogout = $conn->prepare("UPDATE users SET last_logout = ? WHERE username = ?");
    $updateLogout->bind_param("ss", $now, $username);
    $updateLogout->execute();
}

// ✅ Hủy phiên đăng nhập
session_unset();
session_destroy();

// ✅ Quay về trang chủ
header("Location: index.php");
exit;
?>

