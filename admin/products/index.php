<?php
require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../includes/admin_auth.php";
require_once __DIR__ . "/../../models/Product.php";

$product = new Product($pdo);
$products = $product->all();
?>

<h2>Danh sách sản phẩm</h2>
<a href="create.php">+ Thêm sản phẩm</a>

<table border="1" cellpadding="8">
<tr>
    <th>ID</th><th>Tên</th><th>Hãng</th><th>Loại</th><th>Hành động</th>
</tr>
<?php foreach ($products as $p): ?>
<tr>
    <td><?= $p['id'] ?></td>
    <td><?= $p['name'] ?></td>
    <td><?= $p['brand'] ?></td>
    <td><?= $p['category'] ?></td>
    <td>
        <a href="edit.php?id=<?= $p['id'] ?>">Sửa</a> |
        <a href="delete.php?id=<?= $p['id'] ?>" onclick="return confirm('Xóa?')">Xóa</a>
    </td>
</tr>
<?php endforeach ?>
</table>
