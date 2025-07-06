<?php
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    // Nếu chưa đăng nhập thì chuyển về trang login
    echo "<script>alert('⚠️ Vui lòng đăng nhập trước khi gửi truyện'); window.location.href = 'login.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Gửi Truyện</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .container {
        max-width: 700px;
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        border: 1px solid;
        margin-top: 20px;
    }

    .form-control,
    .form-select {
        border: 1px solid;
    }

    .headerGuiTruyen {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }

    body {
        font-family: Arial, sans-serif;
        margin: 0;

        /* ảnh nền */
        background-size: cover;
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-image: url("/SACH24H/assets/images/background.jpg");
        padding: 0px;
    }

    header {
        background: linear-gradient(270deg, #1a1a1a, #2d2d2d, #1a1a1a);
        background-size: 600% 600%;
        animation: gradientFlow 12s ease infinite;
        color: white;
        padding: 10px 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    @keyframes gradientFlow {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    nav a {
        position: relative;
        color: white;
        margin-left: 20px;
        text-decoration: none;
        transition: color 0.3s;
    }

    nav a::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: -4px;
        width: 100%;
        height: 2px;
        background: limegreen;
        transform: scaleX(0);
        transform-origin: right;
        transition: transform 0.4s ease;
    }

    nav a:hover::after {
        transform: scaleX(1);
        transform-origin: left;
    }

    .container {
        padding: 20px;
    }
    </style>
</head>

<body>


    <div class="container">
        <h2 class="mb-4 text-center">📚 Gửi Truyện Của Bạn</h2>
        <form action="submit_story.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Tên truyện</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Thể loại</label>
                <select name="category" class="form-select" required>
                    <option value="">-- Chọn thể loại --</option>
                    <option>Ngôn tình</option>
                    <option>Trinh thám</option>
                    <option>Khoa học viễn tưởng</option>
                    <option>Văn học Việt Nam</option>
                    <option>Khác</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Nội dung</label>
                <textarea name="content" class="form-control" rows="5" placeholder="Tóm tắt hoặc chương 1..."
                    required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Email người gửi</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Đính kèm file (PDF/Docx)</label>
                <input type="file" name="attachment" class="form-control" required>

            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Gửi truyện 📤</button>
            </div>
        </form>
    </div>

</body>

</html>
<?php

include 'includes/footer.php';


?>