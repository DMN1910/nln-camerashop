<?php
require_once "../../includes/admin_auth.php";
require_once "../../config/database.php";
require_once "../../models/Product.php";
require_once "../../models/ProductVariant.php";
require_once "../../models/Brand.php";
require_once "../../models/Category.php";

$id = $_GET['id'];

$productModel = new Product($pdo);
$variantModel = new ProductVariant($pdo);

$product = $productModel->find($id);
$variants = $variantModel->byProduct($id);
$brands = (new Brand($pdo))->all();
$categories = (new Category($pdo))->all();
?>

<h3>Sửa sản phẩm</h3>

<form action="update.php" method="post">
<input type="hidden" name="id" value="<?= $product['id'] ?>">

<input name="name" value="<?= $product['name'] ?>" required><br>

<select name="brand_id">
<?php foreach ($brands as $b): ?>
<option value="<?= $b['id'] ?>" <?= $b['id']==$product['brand_id']?'selected':'' ?>>
    <?= $b['name'] ?>
</option>
<?php endforeach ?>
</select>

<select name="category_id">
<?php foreach ($categories as $c): ?>
<option value="<?= $c['id'] ?>" <?= $c['id']==$product['category_id']?'selected':'' ?>>
    <?= $c['name'] ?>
</option>
<?php endforeach ?>
</select>

<textarea name="description"><?= $product['description'] ?></textarea>

<h4>Biến thể</h4>

<?php foreach ($variants as $v): ?>
<input type="hidden" name="variant_id[]" value="<?= $v['id'] ?>">
<input name="condition[]" value="<?= $v['condition'] ?>">
<input name="cost_price[]" value="<?= $v['cost_price'] ?>">
<input name="sell_price[]" value="<?= $v['sell_price'] ?>">
<input name="stock[]" value="<?= $v['stock'] ?>">
<br>
<?php endforeach ?>

<button>Cập nhật</button>
</form>
