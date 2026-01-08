<?php
require_once "../../includes/admin_auth.php";
require_once "../../config/database.php";

/* update product */
$stmt = $pdo->prepare(
    "UPDATE products
     SET name=?, brand_id=?, category_id=?, description=?
     WHERE id=?"
);

$stmt->execute([
    $_POST['name'],
    $_POST['brand_id'],
    $_POST['category_id'],
    $_POST['description'],
    $_POST['id']
]);

/* update variants */
foreach ($_POST['variant_id'] as $i => $vid) {
    $stmt = $pdo->prepare(
        "UPDATE product_variants
         SET `condition`=?, cost_price=?, sell_price=?, stock=?
         WHERE id=?"
    );

    $stmt->execute([
        $_POST['condition'][$i],
        $_POST['cost_price'][$i],
        $_POST['sell_price'][$i],
        $_POST['stock'][$i],
        $vid
    ]);
}

header("Location: index.php");
