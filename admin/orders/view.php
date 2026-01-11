<?php
session_start();
require_once "../../config/database.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../../auth/login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$order_id = (int) $_GET['id'];

/* ===== LẤY ĐƠN HÀNG ===== */
$orderStmt = $pdo->prepare("
    SELECT o.*, u.name AS customer_name, u.email
    FROM orders o
    JOIN users u ON o.user_id = u.id
    WHERE o.id = ?
");
$orderStmt->execute([$order_id]);
$order = $orderStmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    die("Đơn hàng không tồn tại");
}

/* ===== LẤY SẢN PHẨM TRONG ĐƠN ===== */
$itemStmt = $pdo->prepare("
    SELECT *
    FROM order_items
    WHERE order_id = ?
");
$itemStmt->execute([$order_id]);
$items = $itemStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Chi tiết đơn hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-4">
        <a href="index.php" class="btn btn-secondary mb-3">← Quay lại</a>

        <h3>🧾 Đơn hàng #<?= $order['id'] ?></h3>
        <p>
            <strong>Khách hàng:</strong> <?= htmlspecialchars($order['customer_name']) ?><br>
            <strong>Email:</strong> <?= htmlspecialchars($order['email']) ?><br>
            <strong>Trạng thái:</strong> <?= $order['status'] ?><br>
            <strong>Ngày đặt:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
        </p>

        <table class="table table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Sản phẩm</th>
                    <th>Tình trạng</th>
                    <th>Hình ảnh</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                        <td><?= $item['variant_condition'] ?></td>
                        <td>
                            <?php if (!empty($item['product_image'])): ?>
                                <img src="../../uploads/products/<?=$item['product_image'] ?>"
                                    width="80"
                                    style="object-fit:cover">
                            <?php endif; ?>
                        </td>
                        <td><?= number_format($item['price'], 0, ',', '.') ?> đ</td>
                        <td><?= $item['quantity'] ?></td>
                        <td class="text-danger fw-bold">
                            <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?> đ
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h4 class="text-end">
            Tổng tiền: <span class="text-danger">
                <?= number_format($order['total_price'], 0, ',', '.') ?> đ
            </span>
        </h4>
    </div>

</body>

</html>