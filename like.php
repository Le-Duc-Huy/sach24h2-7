<?php
session_start();
require 'includes/db.php';

$book_id = $_GET['book_id'] ?? 0;

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=book.php?id=$book_id");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($book_id > 0) {
    $stmt = $conn->prepare("SELECT id FROM likes WHERE user_id = ? AND book_id = ?");
    $stmt->bind_param("ii", $user_id, $book_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $insert = $conn->prepare("INSERT INTO likes (user_id, book_id) VALUES (?, ?)");
        $insert->bind_param("ii", $user_id, $book_id);
        $insert->execute();
    }

    // Quay về lại trang chi tiết sách
    header("Location: book.php?id=$book_id");
    exit;
} else {
    echo "❌ Thiếu thông tin truyện.";
}
?>