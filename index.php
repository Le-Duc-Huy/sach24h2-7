<?php
session_start();
include 'includes/header.php';
include 'includes/db.php';
?>

<div class="container">
    <h2>Sách nổi bật</h2>
    <div class="book-list">
        <?php
        $sql = "SELECT * FROM books WHERE featured = 1 ORDER BY id DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $link = ($row['require_login'] && !isset($_SESSION['user_id'])) ? 'login.php' : 'book.php?id=' . $row['id'];
                echo "<div class='book'>";
                echo "<img src='assets/images/{$row['image']}' alt='" . htmlspecialchars($row['title']) . "'>";
                echo "<h3><a href='{$link}'>" . htmlspecialchars($row['title']) . "</a></h3>";
                echo "<p><i class='fa fa-pen'></i> <span style='color:orangered'>" . htmlspecialchars($row['author']) . "</span></p>";
$book_id = (int)$row['id'];
$avg_stmt = $conn->prepare("SELECT AVG(rating) AS avg_rating FROM book_reviews WHERE book_id = ?");
$avg_stmt->bind_param("i", $book_id);
$avg_stmt->execute();
$avg_result = $avg_stmt->get_result()->fetch_assoc();
$avg_stmt->close();

$avg_rating = $avg_result['avg_rating'] ? round($avg_result['avg_rating'], 1) : 0;
echo "<p><span style='color:gold'>⭐ {$avg_rating} / 5</span> ";


                echo htmlspecialchars(substr($row['description'], 0, 80)) . "...</p>";
                echo "<p>" . number_format($row['views']) . " views - " . $row['status'] . "</p>";
                echo "<a href='{$link}'>Đọc sách</a>";
                echo "</div>";
            }
        } else {
            echo "<p>Không có sách nổi bật.</p>";
        }
        ?>
    </div>

    <h2 style="margin-top: 40px;">SÁCH TRUYỆN MỚI CẬP NHẬT 📚</h2>
    <div class="chapter-list">
        <?php
        $sql = "SELECT chapters.*, books.title AS book_title, books.image, books.status, books.author
                FROM chapters 
                JOIN books ON chapters.book_id = books.id 
                WHERE books.show_in_latest = 1
                ORDER BY chapters.created_at DESC LIMIT 20";

        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $bookChapters = [];
            while ($row = $result->fetch_assoc()) {
                $bookId = $row['book_id'];
                if (!isset($bookChapters[$bookId])) {
                    $bookChapters[$bookId] = [];
                }
                if (count($bookChapters[$bookId]) < 3) {
                    $bookChapters[$bookId][] = $row;
                }
            }

            foreach ($bookChapters as $chapters) {
                foreach ($chapters as $row) {
                    echo "<div class='chapter-update'>";
                    echo "<img src='assets/images/" . htmlspecialchars($row['image']) . "' 
                            onerror=\"this.onerror=null;this.src='assets/images/default.jpg';\" class='thumb'>";
                    echo "<div>";
                    echo "<strong><a href='chapter.php?id=" . $row['id'] . "'>" . htmlspecialchars($row['book_title']) . " - " . htmlspecialchars($row['chapter_title']) . "</a></strong><br>";
                    echo "<span style='color:gray'>[" . htmlspecialchars($row['status']) . "]</span> - " . htmlspecialchars($row['author']);
                    echo "</div>";
                    echo "</div>";
                }
            }
        } else {
            echo "<p>Không có chương nào.</p>";
        }
        ?>
    </div>

    <!-- Tin tức sách -->
    <h2 style="margin-top: 50px;">Điểm Tin Sách</h2>
    <div class="news-grid">
        <!-- Bạn có thể thay đổi nội dung hoặc tách phần này ra từ CSDL -->
        <a href="news_detail.php?id=1" class="news-card">
            <img src="assets/images/vanhoclam.jpg" alt="Văn học miền Nam">
            <span class="badge">ĐIỂM TIN SÁCH</span>
            <h3>Ra mắt bộ sách 'Văn học miền Nam lục tỉnh'</h3>
            <p>Bộ sách của tác giả Nguyễn Văn Hậu phân tích sự phát triển của văn học miền Nam...</p>
        </a>
        <!-- Các bài viết khác tương tự -->
        <a href="news_detail.php?id=2" class="news-card">
            <img src="assets/images/chuyentinhtre.jpg" alt="Chuyện tình">
            <span class="badge">ĐIỂM TIN SÁCH</span>
            <h3>'Ngày xưa có một chuyện tình' - ký ức tình đầu</h3>
            <p>Phim “Ngày xưa có một chuyện tình” tái hiện mối tình tay ba trong sáng...</p>
        </a>
        <a href="news_detail.php?id=3" class="news-card">
            <img src="assets/images/nhavan.jpg" alt="Nhà văn nổi tiếng">
            <span class="badge">SỰ KIỆN</span>
            <h3>Những câu chuyện thú vị và có thật về các nhà văn nổi tiếng</h3>
            <p>Những khía cạnh ít được biết đến về cuộc đời của các tác giả văn học lớn...</p>
        </a>
        <a href="news_detail.php?id=4" class="news-card">
            <img src="assets/images/hankang.jpg" alt="Han Kang Nobel">
            <span class="badge">ĐIỂM TIN SÁCH</span>
            <h3>Tác giả người Hàn Quốc Han Kang giành giải Nobel Văn học 2024</h3>
            <p>Tác giả cuốn sách Người ăn chay được ngợi ca là "văn xuôi thơ mộng và đau đớn"...</p>
        </a>
    </div>

    <!-- Tổng hợp sách -->
    <h2 style="margin-top: 50px;">Tổng Hợp Sách Hay</h2>
    <div class="book-columns">
        <?php
        $types = [
            'new' => 'Truyện mới',
            'suggested' => 'Đề cử',
            'best' => 'Sách hay'
        ];

        foreach ($types as $type => $title) {
            echo "<div class='book-column'>";
            echo "<h3>$title</h3><ul class='book-list-with-thumb'>";

            $res = $conn->query("SELECT * FROM books WHERE category_type = '{$type}' ORDER BY id DESC LIMIT 10");
            while ($book = $res->fetch_assoc()) {
                $link = ($book['require_login'] && !isset($_SESSION['user_id'])) ? 'login.php' : 'book.php?id=' . $book['id'];
                echo "<li><a href='{$link}'><img src='assets/images/{$book['image']}' style='width:60px;height:90px; margin-right:10px; float:left;'></a>";
                echo "<div><strong><a href='{$link}'>{$book['title']}</a></strong><br>";
                echo "<span>📖 {$book['category']} • <em>{$book['author']}</em></span><br>";
                echo "⭐ {$book['rating']}/10</div><div style='clear:both'></div></li>";
            }

            echo "</ul></div>";
        }
        ?>
    </div>

    <!-- Footer mini -->
    <div
        style="display: flex; justify-content: space-between; flex-wrap: wrap; margin-top: 60px; padding: 30px; border-top: 1px solid #eee;">
        <div>
            <p><strong>Giới thiệu & Điều khoản dịch vụ</strong></p>
            <p><a href="#">Hướng dẫn đăng truyện</a></p>
            <p><a href="#">Chuẩn chính tả khi viết văn</a></p>
            <p><a href="#">Hướng dẫn trả phí</a></p>
            <p><a href="#"><img src="assets/images/dmca.png" alt="DMCA" style="height: 25px;"></a></p>
        </div>
        <div style="text-align: center; padding: 20px 30px; max-width: 250px;">
            <span style="font-size: 1.5rem; color: gray;">❝</span>
            <span style="font-size: 1rem; color: #555;">Sach24h</span>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>