<?php
session_start();
require 'includes/db.php';

// Lấy book_id từ URL
$book_id = $_GET['book_id'] ?? 0;

// Nếu chưa đăng nhập thì chuyển sang trang login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?next=read.php?book_id=$book_id");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($book_id > 0) {
    // Lưu lịch sử đọc nếu chưa có
    $stmt = $conn->prepare("SELECT id FROM `reads` WHERE user_id = ? AND book_id = ?");

    $stmt->bind_param("ii", $user_id, $book_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $insert = $conn->prepare("INSERT INTO `reads` (user_id, book_id) VALUES (?, ?)");

        $insert->bind_param("ii", $user_id, $book_id);
        $insert->execute();
    }

    // ✅ Quay lại trang danh sách chương
    header("Location: book.php?id=$book_id");
    exit;
} else {
    echo "Thiếu thông tin truyện.";
}