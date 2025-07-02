<?php
$servername = "localhost";
$username = "root";
$password = ""; // Thay bằng mật khẩu thực tế
$dbname = "page_views"; // Thay bằng tên database

// Kết nối MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Lỗi kết nối: " . $conn->connect_error);
}

// Tăng lượt xem
$conn->query("UPDATE page_views SET views = views + 1 WHERE id = 1");

// Lấy lượt xem hiện tại
$result = $conn->query("SELECT views FROM page_views WHERE id = 1");
$row = $result->fetch_assoc();

// Hiển thị
echo "Lượt xem: " . $row['views'];

$conn->close();
?>
