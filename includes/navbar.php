<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="<?= BASE_URL ?>/index.php">Camera Shop</a>

        <ul class="navbar-nav ms-auto">
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
