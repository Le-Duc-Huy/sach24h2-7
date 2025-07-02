<?php
include 'includes/db.php';

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$title = $_POST['title'] ?? '';
$content = $_POST['content'] ?? '';
$submitted_at = date('Y-m-d H:i:s');

// Xử lý file đính kèm
$attachment = '';
if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
    $attachment = basename($_FILES['attachment']['name']);
    move_uploaded_file($_FILES['attachment']['tmp_name'], 'uploads/' . $attachment);
}

// Lưu vào CSDL
$stmt = $conn->prepare("INSERT INTO submissions (name, email, title, content, attachment, submitted_at) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $name, $email, $title, $content, $attachment, $submitted_at);
$stmt->execute();

echo "<h2>✅ Gửi truyện thành công!</h2><a href='index.php'>Quay lại trang chủ</a>";
?>