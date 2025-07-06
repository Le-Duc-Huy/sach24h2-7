<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$database = "sach24h";
$port = 3307;

$conn = new mysqli($servername, $username, $password, $database, $port);

// Thiết lập charset (giúp tránh lỗi authentication với MariaDB 10.4+)
$conn->set_charset("utf8mb4");

// Kiểm tra kết nối+
if ($conn->connect_error) {
    die("Lỗi kết nối: " . $conn->connect_error);
}

?>