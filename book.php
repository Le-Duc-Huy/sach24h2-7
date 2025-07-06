<?php
session_start();
include 'includes/header.php';
include 'includes/db.php';

// Xử lý gửi đánh giá
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    if (!isset($_SESSION['user_id'])) {
        // Chuyển sang trang đăng nhập và quay lại book.php sau khi đăng nhập
        $redirect_url = 'login.php?redirect=book.php?id=' . urlencode($_GET['id']);
        header("Location: $redirect_url");
        exit;
    }


    $book_id = (int)$_GET['id'];
    $rating = (int)$_POST['rating'];
    $comment = trim($_POST['comment']);

    if ($rating >= 1 && $rating <= 5 && !empty($comment)) {
        $stmt = $conn->prepare("INSERT INTO book_reviews (book_id, rating, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $book_id, $rating, $comment);
        $stmt->execute();
        $stmt->close();

        header("Location: book.php?id=$book_id");
        exit;
    } else {
        echo "<p style='color:red'>Vui lòng chọn số sao và nhập bình luận.</p>";
    }
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $book_id = (int)$_GET['id'];

    $conn->query("UPDATE books SET views = views + 1 WHERE id = $book_id");

    $stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
// Cập nhật lại rating trung bình vào bảng books
$update_stmt = $conn->prepare("UPDATE books SET rating = (SELECT AVG(rating) FROM book_reviews WHERE book_id = ?) WHERE id = ?");
$update_stmt->bind_param("ii", $book_id, $book_id);
$update_stmt->execute();
$update_stmt->close();

    if ($row = $result->fetch_assoc()) {
        echo "<div class='container'>";
        echo "<h2>" . htmlspecialchars($row['title']) . "</h2>";
        echo "<div style='display:flex; gap:30px; margin-top:20px'>";
        echo "<img class='book-detail-image' src='assets/images/" . htmlspecialchars($row['image']) . "' onerror=\"this.onerror=null;this.src='assets/images/default.jpg';\">";

        echo "<div>";
        echo "<p><strong>Tác giả:</strong> " . htmlspecialchars($row['author']) . "</p>";
        echo "<p><strong>Thể loại:</strong> " . htmlspecialchars($row['category']) . "</p>";
        echo "<p><strong>Trạng thái:</strong> " . htmlspecialchars($row['status']) . "</p>";
        echo "<p><strong>Lượt xem:</strong> " . number_format($row['views']) . "</p>";
        echo "<p><strong>Mô tả:</strong><br>" . nl2br(htmlspecialchars($row['description'])) . "</p>";
        echo "</div></div>";

// Hiển thị 3 nút hành động
echo "<div style='margin-top: 20px; display: flex; gap: 10px;'>";

echo "<a href='read.php?book_id=" . $row['id'] . "' 
        style='background-color: #8bc34a; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: bold; display: inline-flex; align-items: center; gap: 6px;'>
        <i class='fa-solid fa-book'></i> Đọc từ đầu
      </a>";

echo "<a href='follow.php?book_id=" . $row['id'] . "' 
        style='background-color: #ff4f72; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: bold; display: inline-flex; align-items: center; gap: 6px;'>
        <i class='fa-solid fa-heart'></i> Theo dõi
      </a>";

echo "<a href='like.php?book_id=" . $row['id'] . "' 
        style='background-color: #cc00ff; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: bold; display: inline-flex; align-items: center; gap: 6px;'>
        <i class='fa-solid fa-thumbs-up'></i> Thích
      </a>";

echo "</div>";

        // Hiển thị đánh giá
        $rating_stmt = $conn->prepare("SELECT AVG(rating) AS avg_rating, COUNT(*) AS total_reviews FROM book_reviews WHERE book_id = ?");
        $rating_stmt->bind_param("i", $book_id);
        $rating_stmt->execute();
        $rating_data = $rating_stmt->get_result()->fetch_assoc();

        echo "<h3>⭐ Đánh giá trung bình: " . round($rating_data['avg_rating'], 1) . " / 5 ({$rating_data['total_reviews']} đánh giá)</h3>";
        $rating_stmt->close();

        // Bình luận
        $comment_stmt = $conn->prepare("SELECT comment, rating, created_at FROM book_reviews WHERE book_id = ? ORDER BY id DESC");
        $comment_stmt->bind_param("i", $book_id);
        $comment_stmt->execute();
        $comment_result = $comment_stmt->get_result();

  echo "<h4>🗨️ Bình luận</h4>";
echo "<div style='max-height: 250px; overflow-y: auto; scrollbar-width: none; -ms-overflow-style: none; border: 1px solid #ddd; padding: 10px; background:rgb(240, 169, 220); border-radius: 20px; margin-bottom: 20px; margin-left:2px'>
<style>
    div::-webkit-scrollbar {
        display: none;
    }
</style>";

if ($comment_result->num_rows > 0) {
    while ($cmt = $comment_result->fetch_assoc()) {
        echo "<div style='margin-bottom: 10px; border-bottom: 1px solid #ccc; padding-bottom: 5px'>";
        echo "<strong>⭐ " . (int)$cmt['rating'] . "</strong><br>";
        echo "<em>" . htmlspecialchars($cmt['comment']) . "</em><br>";
        echo "<small>" . $cmt['created_at'] . "</small>";
        echo "</div>";
    }
} else {
    echo "<p>Chưa có bình luận.</p>";
}
echo "</div>"; // Đóng div scroll

echo "</div>"; // Đóng div scroll

        // Form gửi đánh giá
       echo '
    <h4>📩 Gửi đánh giá của bạn</h4>
    <div style="padding: 20px; background: rgba(255, 255, 255, 0.7); border-radius: 12px; max-width: 600px; margin: 20px; border: 1px solid #ccc;">
        <form method="POST" action="">
            <label>Số sao (1 - 5):</label><br>
            <select name="rating" required>
                <option value="">--Chọn--</option>
                <option value="5">5 ⭐</option>
                <option value="4">4 ⭐</option>
                <option value="3">3 ⭐</option>
                <option value="2">2 ⭐</option>
                <option value="1">1 ⭐</option>
            </select><br><br>

            <label>Bình luận:</label><br>
            <textarea name="comment" rows="4" cols="40" required></textarea><br><br>

            <button type="submit" name="submit_review">Gửi đánh giá</button>
        </form>
    </div>';

        // Danh sách chương
        $chapter_stmt = $conn->prepare("SELECT id, chapter_title FROM chapters WHERE book_id = ? ORDER BY id ASC");
        $chapter_stmt->bind_param("i", $book_id);
        $chapter_stmt->execute();
        $chapters = $chapter_stmt->get_result();

        echo "<h3 style='margin-top:40px;'>📚 Danh sách chương</h3><ul style='padding-left: 20px;'>";
        if ($chapters->num_rows > 0) {
            while ($chapter = $chapters->fetch_assoc()) {
                echo "<li><a href='chapter.php?id=" . (int)$chapter['id'] . "'>" . htmlspecialchars($chapter['chapter_title']) . "</a></li>";
            }
        } else {
            echo "<li>Chưa có chương nào.</li>";
        }
        echo "</ul></div>";
    } else {
        echo "<p>Sách không tồn tại.</p>";
    }
    $stmt->close();
} else {
    echo "<p>Không tìm thấy sách.</p>";
}

include 'includes/footer.php';
?>