<?php
session_start();
require 'includes/db.php';

$book_id = $_GET['book_id'] ?? 0;

// Nếu chưa đăng nhập → chuyển đến login.php kèm theo redirect trở lại book.php
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=book.php?id=$book_id");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($book_id > 0) {
    // Kiểm tra đã theo dõi chưa
    $stmt = $conn->prepare("SELECT id FROM follows WHERE user_id = ? AND book_id = ?");
    $stmt->bind_param("ii", $user_id, $book_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        // Nếu chưa theo dõi thì thêm
        $insert = $conn->prepare("INSERT INTO follows (user_id, book_id) VALUES (?, ?)");
        $insert->bind_param("ii", $user_id, $book_id);
        $insert->execute();
    }

    // Quay lại trang chi tiết truyện
    header("Location: book.php?id=$book_id");
    exit;
} else {
    echo "❌ Thiếu thông tin truyện.";
}
?>