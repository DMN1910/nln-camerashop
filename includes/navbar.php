<nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
    <div class="container">
        <a class="navbar-brand" href="<?= BASE_URL ?>/index.php">Camera Shop</a>
        <ul class="navbar-nav ms-auto">
            <li class="search-box">
                <form action="search.php" method="get" style="display:flex; align-items:center; gap:5px;">
                    <input type="text" name="search" placeholder="Tìm kiếm..." required>
                    <button type="submit" style="border:none; background:none; cursor:pointer;">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </li>

            <li>
                <a href="<?= BASE_URL ?>/cart/cart.php" class="">
                    <i class="fas fa-shopping-bag"></i>
                    <span id="cart-count">
                        <?php echo isset($_SESSION['cart'])
                            ? array_sum(array_column($_SESSION['cart'], 'quantity'))
                            : 0; ?>
                    </span>
                </a>
            </li>


            <?php if (isset($_SESSION['user'])): ?>
                <li class="nav-item text-white me-3">
                    Xin chào, <?= $_SESSION['user']['name'] ?>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/logout.php">Đăng xuất</a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/login.php">Đăng nhập</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/register.php">Đăng ký</a>
                </li>
            <?php endif; ?>


        </ul>
    </div>
</nav>

    <?php
    require_once __DIR__ . "/../config/database.php";
    $stmt = $pdo->query("SELECT id, name FROM categories ORDER BY id ASC");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

<div class="nav" style="display:flex">
    <li class="nav-item">
        <a class="nav-link" href="<?= BASE_URL ?>/index.php">
            Tất cả
        </a>
    </li>

    <?php foreach ($categories as $category): ?>
        <li class="nav-item">
            <a class="nav-link"
               href="<?= BASE_URL ?>/index.php?category_id=<?= $category['id'] ?>">
                <?= htmlspecialchars($category['name']) ?>
            </a>
        </li>
    <?php endforeach; ?>
</div>
