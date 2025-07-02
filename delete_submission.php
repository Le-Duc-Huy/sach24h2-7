<?php
include 'includes/db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM submissions WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        // ✅ Sau khi xóa, quay về trang chủ (hoặc admin_submissions.php)
        header("Location:index.php"); // hoặc index.php nếu bạn muốn
        exit;
    } else {
        echo "Lỗi khi xóa truyện: " . $conn->error;
    }
} else {
    echo "Thiếu ID để xóa.";
}
?>