<?php
session_start();
require_once "../../config/database.php";
require_once "../../config/config.php";

/* CHỈ ADMIN */
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die("Bạn không có quyền truy cập");
}

/* 1. Tổng doanh thu */
$totalRevenue = $pdo->query("
    SELECT COALESCE(SUM(total_price),0)
    FROM orders
    WHERE status != 'Đã hủy'
")->fetchColumn();

/* 2. Tổng số đơn */
$totalOrders = $pdo->query("
    SELECT COUNT(*)
    FROM orders
")->fetchColumn();

/* 3. Tổng sản phẩm đã bán */
$totalSold = $pdo->query("
    SELECT COALESCE(SUM(quantity),0)
    FROM order_items
")->fetchColumn();

/* 4. Doanh thu theo ngày (7 ngày gần nhất) */
$dailyRevenueStmt = $pdo->query("
    SELECT DATE(created_at) AS order_date,
           SUM(total_price) AS revenue
    FROM orders
    WHERE status != 'Đã hủy'
    GROUP BY DATE(created_at)
    ORDER BY order_date DESC
    LIMIT 7
");
$dailyRevenues = $dailyRevenueStmt->fetchAll(PDO::FETCH_ASSOC);

/* 5. Đơn hàng gần nhất */
$recentOrders = $pdo->query("
    SELECT o.id, u.name, o.total_price, o.status, o.created_at
    FROM orders o
    JOIN users u ON o.user_id = u.id
    ORDER BY o.created_at DESC
    LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>📊 Theo dõi doanh thu</h2>

<hr>

<!-- TỔNG QUAN -->
<div style="display:flex; gap:20px">
    <div>
        <h3>💰 Tổng doanh thu</h3>
        <strong><?= number_format($totalRevenue) ?> đ</strong>
    </div>

    <div>
        <h3>📦 Tổng đơn hàng</h3>
        <strong><?= $totalOrders ?></strong>
    </div>

    <div>
        <h3>🛒 Sản phẩm đã bán</h3>
        <strong><?= $totalSold ?></strong>
    </div>
</div>

<hr>

<!-- DOANH THU THEO NGÀY -->
<h3>📈 Doanh thu 7 ngày gần nhất</h3>

<table border="1" cellpadding="8">
    <tr>
        <th>Ngày</th>
        <th>Doanh thu</th>
    </tr>

    <?php foreach ($dailyRevenues as $row): ?>
        <tr>
            <td><?= $row['order_date'] ?></td>
            <td><?= number_format($row['revenue']) ?> đ</td>
        </tr>
    <?php endforeach; ?>
</table>

<hr>

<!-- ĐƠN HÀNG GẦN NHẤT -->
<h3>📋 Đơn hàng gần nhất</h3>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Khách hàng</th>
        <th>Tổng tiền</th>
        <th>Trạng thái</th>
        <th>Ngày tạo</th>
    </tr>

    <?php foreach ($recentOrders as $order): ?>
        <tr>
            <td>#<?= $order['id'] ?></td>
            <td><?= htmlspecialchars($order['name']) ?></td>
            <td><?= number_format($order['total_price']) ?> đ</td>
            <td><?= $order['status'] ?></td>
            <td><?= $order['created_at'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>
