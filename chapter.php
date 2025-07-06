<?php
include 'includes/db.php';
include 'includes/header.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $chapter_id = $_GET['id'];

    // Truy vấn chương và tên sách
    $stmt = $conn->prepare("
        SELECT chapters.*, books.title AS book_title 
        FROM chapters 
        JOIN books ON chapters.book_id = books.id 
        WHERE chapters.id = ?
    ");
    $stmt->bind_param("i", $chapter_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {

        // ✅ Kiểm tra đăng nhập nếu chương yêu cầu
        if ($row['require_login'] == 1 && !isset($_SESSION['user_id'])) {
            echo "<div class='container'>";
            echo "<h2>" . htmlspecialchars($row['book_title']) . " - " . htmlspecialchars($row['chapter_title']) . "</h2>";
            echo "<p style='font-size: 1.2rem;'>Chương này yêu cầu đăng nhập. Vui lòng <a href='login.php'>Đăng nhập</a> hoặc <a href='register.php'>Đăng ký</a> để đọc nội dung.</p>";
            echo "</div>";
        } else {
            // ✅ Cho phép đọc nếu đã đăng nhập hoặc không yêu cầu
            echo "<div class='container'>";
            echo "<h2>" . htmlspecialchars($row['book_title']) . " - " . htmlspecialchars($row['chapter_title']) . "</h2>";
            echo "<p><em>Tác giả: " . htmlspecialchars($row['author']) . " • " . htmlspecialchars($row['status']) . "</em></p>";
            echo "<hr>";
            echo "<div style='white-space: pre-line; font-size: 1.1rem;'>" . nl2br(htmlspecialchars($row['content'])) . "</div>";
            echo "</div>";
        }
    } else {
        echo "<p>Không tìm thấy chương.</p>";
    }

    $stmt->close();
} else {
    echo "<p>Không có ID chương hợp lệ.</p>";
}

include 'includes/footer.php';
?>