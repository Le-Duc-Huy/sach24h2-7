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
    $uploadDir = 'uploads/';
    
    // Tạo tên file mới tránh trùng và có dấu cách
    $safeName = preg_replace("/[^a-zA-Z0-9\.]/", "_", $_FILES['attachment']['name']);
    $attachment = time() . '_' . $safeName;
    $targetPath = $uploadDir . $attachment;

    // Tạo thư mục nếu chưa có
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Di chuyển file
    if (!move_uploaded_file($_FILES['attachment']['tmp_name'], $targetPath)) {
        echo "<p style='color:red;'>❌ Không thể upload file. Kiểm tra quyền thư mục 'uploads/'</p>";
        exit;
    }
} else {
    echo "<p style='color:red;'>❌ Lỗi file hoặc không chọn file đính kèm.</p>";
    echo "<pre>"; print_r($_FILES); echo "</pre>";
    exit;
}

// Lưu vào CSDL
$stmt = $conn->prepare("INSERT INTO submissions (name, email, title, content, attachment, submitted_at) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $name, $email, $title, $content, $attachment, $submitted_at);
$stmt->execute();

echo "<h2>✅ Gửi truyện thành công!</h2><a href='index.php'>Quay lại trang chủ</a>";
?>