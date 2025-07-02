<?php
include 'includes/db.php';
include 'includes/header.php';
?>

<div class="container">
    <h2>📚 Thư Viện Gác Sách</h2>
    <hr>

    <?php
    $sql = "SELECT * FROM books ORDER BY id DESC";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while($book = $result->fetch_assoc()) {
            echo "<a href='book.php?id={$book['id']}' style='text-decoration: none; color: inherit;'>";
            echo "<div style='display: flex; border-bottom: 1px solid #ddd; padding: 15px 0;'>";
            echo "<img class='book-cover' src='assets/images/{$book['image']}' alt='{$book['title']}'>";

            echo "<div>";
            echo "<h3 style='margin: 0 0 8px 0;'>{$book['title']}</h3>";
            echo "<p style='margin: 0;'><em>{$book['category']} • {$book['author']}</em></p>";
            echo "<p style='margin: 5px 0;'>⭐ " . number_format($book['rating'], 1) . "/10</p>";
            echo "<p style='margin: 5px 0; color: gray;'>" . number_format($book['views']) . " views - {$book['status']}</p>";
            echo "<p style='margin: 5px 0;'>" . nl2br(htmlspecialchars(substr($book['description'], 0, 120))) . "...</p>";
            echo "</div></div></a>";
        }
    } else {
        echo "<p>Chưa có sách nào trong thư viện.</p>";
    }
    ?>
</div>

<?php include 'includes/footer.php'; ?>