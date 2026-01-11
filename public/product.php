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

<!-- Hình ảnh -->
<?php foreach ($images as $img): ?>
    <img src="../uploads/products/<?= htmlspecialchars($img['image_path']) ?>" width="150">
<?php endforeach; ?>

<form action="./cart/add_to_cart.php" method="POST">
    <input type="hidden" name="product_id" value="<?= $p['id'] ?>">

    <h3>Chọn loại sản phẩm</h3>

    <?php foreach ($variants as $v): ?>
        <div style="margin-bottom: 8px;">
            <label>
                <input type="radio"
                    name="variant_id"
                    value="<?= $v['id'] ?>"
                    required>
                <?= htmlspecialchars($v['condition']) ?>
                – <?= number_format($v['sell_price']) ?>đ
                (Còn <?= $v['stock'] ?>)
            </label>
        </div>
    <?php endforeach; ?>

    <h3>Số lượng</h3>
    <input type="number"
        name="quantity"
        value="1"
        min="1"
        style="width: 80px;">

    <br><br>

    <button type="submit">🛒 Thêm vào giỏ hàng</button>
</form>


<?php include "../includes/footer.php"; ?>