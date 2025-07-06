<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo "Vui lòng đăng nhập.";
    exit;
}

$user_id = $_SESSION['user_id'];

echo "<h2>📌 Truyện bạn đã theo dõi</h2>";
$sql = "SELECT b.id, b.title FROM books b
        JOIN follows f ON b.id = f.book_id
        WHERE f.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    echo "<li><a href='book.php?id={$row['id']}'>{$row['title']}</a></li>";
}

echo "<h2>👍 Truyện bạn đã thích</h2>";
$sql = "SELECT b.id, b.title FROM books b
        JOIN likes l ON b.id = l.book_id
        WHERE l.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    echo "<li><a href='book.php?id={$row['id']}'>{$row['title']}</a></li>";
}
?>