<?php
require_once "../config/database.php";
require_once "../config/config.php";
include "../includes/header.php";
include "../includes/navbar.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Sản phẩm không tồn tại.");
}

$id = (int)$_GET['id'];

$product = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$product->execute([$id]);
$p = $product->fetch(PDO::FETCH_ASSOC);

if (!$p) {
    die("Không tìm thấy sản phẩm.");
}

$variants = $pdo->prepare(
    "SELECT * FROM product_variants WHERE product_id = ?"
);
$variants->execute([$id]);

$images = $pdo->prepare(
    "SELECT * FROM product_images WHERE product_id = ?"
);
$images->execute([$id]);
?>

<h2><?= htmlspecialchars($p['name']) ?></h2>

<?php foreach ($images as $img): ?>
    <img src="../uploads/products/<?= htmlspecialchars($img['image_path']) ?>" width="150">

<?php endforeach; ?>

<h3>Giá</h3>
<ul>
    <?php foreach ($variants as $v): ?>
        <li>
            <?= htmlspecialchars($v['condition']) ?> –
            <?= number_format($v['sell_price']) ?>đ
        </li>
    <?php endforeach; ?>
</ul>

<?php include "../includes/footer.php"; ?>