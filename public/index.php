<?php
require_once "../config/config.php";
require_once "../config/database.php";
include "../includes/header.php";
include "../includes/navbar.php";

$category = $_GET['category_id'] ?? null;

if ($category) {
    $stmt = $pdo->prepare("
        SELECT id, name, description 
        FROM products 
        WHERE category_id = ?
        ORDER BY id DESC
    ");
    $stmt->execute([$category]);
} else {
    $stmt = $pdo->query("
        SELECT id, name, description 
        FROM products 
        ORDER BY id DESC
    ");
}

$name = null;

if ($name) {
    $stmtCat = $pdo->prepare("
        SELECT name
        FROM categories
        WHERE id = ?
    ");
    $stmtCat->execute([$name]);
    $name = $stmtCat->fetchColumn();
}

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <h1 class="mb-4">
        📷 Danh sách sản phẩm
        <?php if ($name): ?>
            – <?= htmlspecialchars($name) ?>
        <?php endif; ?>
    </h1>



    <?php if (count($products) === 0): ?>
        <div class="alert alert-warning">
            Chưa có sản phẩm nào.
        </div>
    <?php else: ?>


        <div class="container"></div>
        <div class="row">
            <?php foreach ($products as $p): ?>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">
                                <?= htmlspecialchars($p['name']) ?>
                            </h5>
                            <p class="card-text">
                                <?= htmlspecialchars($p['description'] ?? 'Không có mô tả') ?>
                            </p>
                            <a href="product.php?id=<?= $p['id'] ?>" class="btn btn-primary">
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include "../includes/footer.php"; ?>