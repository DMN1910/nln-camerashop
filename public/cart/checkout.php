<?php
session_start();
require_once __DIR__ . "/../../config/database.php";

/* Check đăng nhập theo AuthController */
if (
    !isset($_SESSION['user']) ||
    !isset($_SESSION['user']['id'])
) {
    die("Bạn cần đăng nhập để thanh toán.");
}

$user_id = (int)$_SESSION['user']['id'];

$pdo->beginTransaction();

try {

    /* Lấy giỏ hàng (lock để tránh race condition) */
    $stmt = $pdo->prepare("
    SELECT 
        c.quantity,
        pv.id AS variant_id,
        pv.condition,
        pv.sell_price,
        pv.stock,
        p.name,
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
    FOR UPDATE
    ");
    $stmt->execute([$user_id]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$items) {
        throw new Exception("Giỏ hàng trống.");
    }

    $total = 0;
    foreach ($items as $item) {
        if ($item['quantity'] > $item['stock']) {
            throw new Exception("Sản phẩm {$item['name']} không đủ hàng.");
        }
        $total += $item['sell_price'] * $item['quantity'];
    }

    /* Tạo đơn hàng */
    $pdo->prepare(
        "INSERT INTO orders (user_id, total_price)
         VALUES (?, ?)"
    )->execute([$user_id, $total]);

    $order_id = $pdo->lastInsertId();

    /* Thêm order_items + trừ kho */
    foreach ($items as $item) {

        $pdo->prepare(
            "INSERT INTO order_items
            (order_id, product_name, variant_condition, product_image, price, quantity)
            VALUES (?, ?, ?, ?, ?, ?)"
        )->execute([
            $order_id,
            $item['name'],
            $item['condition'],
            $item['image'],
            $item['sell_price'],
            $item['quantity']
        ]);

        $pdo->prepare(
            "UPDATE product_variants
             SET stock = stock - ?
             WHERE id = ?"
        )->execute([
            $item['quantity'],
            $item['variant_id']
        ]);
    }

    /* Xóa giỏ */
    $pdo->prepare(
        "DELETE FROM cart WHERE user_id = ?"
    )->execute([$user_id]);

    $pdo->commit();

    echo "✅ Đặt hàng thành công! Mã đơn: #$order_id";

} catch (Exception $e) {
    $pdo->rollBack();
    die("❌ Lỗi: " . $e->getMessage());
}
