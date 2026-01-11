<?php
session_start();
require_once "../../config/database.php";

/* ====== CHỐNG TRUY CẬP TRÁI PHÉP ====== */
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../../auth/login.php");
    exit;
}

/* ====== LẤY DANH SÁCH ĐƠN HÀNG ====== */
$sql = "
    SELECT 
        o.id,
        o.total_price,
        o.status,
        o.created_at,
        u.name AS customer_name,
        u.email
    FROM orders o
    JOIN users u ON o.user_id = u.id
    ORDER BY o.created_at DESC
";
$stmt = $pdo->query($sql);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý đơn hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="container mt-4">
    <h1 class="mb-4">📦 Quản lý đơn hàng</h1>

    <?php if (empty($orders)): ?>
        <div class="alert alert-info">Chưa có đơn hàng nào.</div>
    <?php else: ?>

    <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Khách hàng</th>
            <th>Email</th>
            <th>Tổng tiền</th>
            <th>Trạng thái</th>
            <th>Ngày đặt</th>
            <th width="180">Hành động</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= $order['id'] ?></td>
                <td><?= htmlspecialchars($order['customer_name']) ?></td>
                <td><?= htmlspecialchars($order['email']) ?></td>
                <td class="text-danger fw-bold">
                    <?= number_format($order['total_price'], 0, ',', '.') ?> đ
                </td>
                <td>
                    <span class="badge bg-secondary">
                        <?= $order['status'] ?>
                    </span>
                </td>
                <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                <td>
                    <a href="view.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-primary">
                        Xem
                    </a>
                    <a href="update_status.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-warning">
                        Trạng thái
                    </a>
                    <a href="delete.php?id=<?= $order['id'] ?>"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Xóa đơn hàng này?')">
                        Xóa
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?php endif; ?>
</div>
</body>
</html>
