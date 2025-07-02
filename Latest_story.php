<?php
include 'includes/db.php';
include 'includes/header.php';
?>

<h2 style="text-align: center;">📚 Danh sách truyện đã gửi</h2>

<table border="1" cellpadding="10" cellspacing="0" style="width: 90%; margin: auto; border-collapse: collapse;">
    <thead>
        <tr style="background-color: #f2f2f2;">
            <th>ID</th>
            <th>Tên</th>
            <th>Email</th>
            <th>Tiêu đề</th>
            <th>Nội dung</th>
            <th>File</th>
            <th>Thời gian gửi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $result = $conn->query("SELECT * FROM submissions ORDER BY submitted_at DESC");
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= nl2br(htmlspecialchars($row['content'])) ?></td>
            <td>
                <?php if ($row['attachment']): ?>
                <a href="uploads/<?= $row['attachment'] ?>" target="_blank">📎 <?= $row['attachment'] ?></a>
                <?php else: ?>
                Không có
                <?php endif; ?>
            </td>
            <td><?= $row['submitted_at'] ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>