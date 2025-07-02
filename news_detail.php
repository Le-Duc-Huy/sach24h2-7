<?php
include 'includes/db.php';
include 'includes/header.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $news_id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM news WHERE id = ?");
    $stmt->bind_param("i", $news_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo "<div class='container'>";
        echo "<h2>" . htmlspecialchars($row['title']) . "</h2>";
        echo "<p><em>" . htmlspecialchars($row['category']) . " • " . $row['created_at'] . "</em></p>";
        echo "<img src='assets/images/" . $row['image'] . "' style='max-width:100%; margin:20px 0;'>";
        echo "<div style='font-size:1.1rem'>" . nl2br(htmlspecialchars($row['content'])) . "</div>";
        echo "</div>";
    } else {
        echo "<p>Tin tức không tồn tại.</p>";
    }

    $stmt->close();
} else {
    echo "<p>Không tìm thấy tin tức.</p>";
}

include 'includes/footer.php';
?>
