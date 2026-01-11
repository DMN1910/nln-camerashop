<?php
require_once "../../config/database.php";
require_once "../../config/config.php";

$stmt = $pdo->query("SELECT * FROM categories ORDER BY id DESC");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>📂 Quản lý loại sản phẩm</h2>

<a href="add.php">➕ Thêm loại</a>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Tên loại</th>
        <th>Hành động</th>
    </tr>

    <?php foreach ($categories as $cat): ?>
        <tr>
            <td><?= $cat['id'] ?></td>
            <td><?= htmlspecialchars($cat['name']) ?></td>
            <td>
                <a href="edit.php?id=<?= $cat['id'] ?>">✏️ Sửa</a> |
                <a href="delete.php?id=<?= $cat['id'] ?>"
                   onclick="return confirm('Xóa loại này?')">❌ Xóa</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
