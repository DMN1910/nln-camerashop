<?php
session_start();
require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../config/config.php";
include __DIR__ . "/../../includes/header.php";
include __DIR__ . "/../../includes/navbar.php";

/* Check đăng nhập theo AuthController */
if (
    !isset($_SESSION['user']) ||
    !isset($_SESSION['user']['id'])
) {
    die("Bạn cần đăng nhập.");
}

$user_id = (int)$_SESSION['user']['id'];

$sql = "
SELECT 
    c.id AS cart_id,
    c.quantity,
    pv.id AS variant_id,
    pv.condition,
    pv.sell_price,
    pv.stock,
    p.name AS product_name,
    (
        SELECT image_path 
        FROM product_images 
        WHERE product_id = p.id 
        LIMIT 1
    ) AS image
FROM cart c
JOIN product_variants pv ON c.product_variant_id = pv.id
JOIN products p ON pv.product_id = p.id
WHERE c.user_id = ?
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
?>

<h2>🛒 Giỏ hàng</h2>

<?php if (!$items): ?>
    <p>Giỏ hàng trống</p>
<?php else: ?>
    <div style="display: flex">
        <div class="col-7">
            <form action="update_cart.php" method="POST">
                <table border="1" cellpadding="8">
                    <tr>
                        <th>Ảnh</th>
                        <th>Sản phẩm</th>
                        <th>Loại</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th>Xóa</th>
                    </tr>

                    <?php foreach ($items as $item):
                        $subtotal = $item['sell_price'] * $item['quantity'];
                        $total += $subtotal;
                    ?>
                        <tr>
                            <td>
                                <img src="../../uploads/products/<?= htmlspecialchars($item['image']) ?>" width="80">
                            </td>
                            <td><?= htmlspecialchars($item['product_name']) ?></td>
                            <td><?= htmlspecialchars($item['condition']) ?></td>
                            <td><?= number_format($item['sell_price']) ?>đ</td>
                            <td>
                                <input type="number"
                                    name="qty[<?= $item['cart_id'] ?>]"
                                    value="<?= $item['quantity'] ?>"
                                    min="1"
                                    max="<?= $item['stock'] ?>">
                            </td>
                            <td><?= number_format($subtotal) ?>đ</td>
                            <td>
                                <a href="remove_cart.php?id=<?= $item['cart_id'] ?>">❌</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                </table>

                <h3>Tổng tiền: <?= number_format($total) ?>đ</h3>

                <div style="display: flex;">
                    <a href="../index.php">Tiếp tục xem sản phẩm</a>
                    <button type="submit">🔄 Cập nhật giỏ</button>

                </div>
            </form>
        </div>
        <div class="col-5">
            <form action="checkout.php" method="POST">
                <button type="submit">✅ Thanh toán</button>

            </form>
        </div>
    </div>


    <br>



<?php endif; ?>

<?php include __DIR__ . "/../../includes/footer.php"; ?>