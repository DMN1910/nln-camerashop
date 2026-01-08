<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="<?= BASE_URL ?>/index.php">Camera Shop</a>

        <ul class="navbar-nav ms-auto">
            <a>gio hang</a>
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
<div class="nav " style="display:flex">
    <li class="nav-item">
        <a class="nav-link" href="<?= BASE_URL ?>/index.php">
            Tất cả
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="<?= BASE_URL ?>/index.php?category_id=1">
            DSLR
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="<?= BASE_URL ?>/index.php?category_id=2">
            Mirrorless
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="<?= BASE_URL ?>/index.php?category_id=3">
            Ống kính
        </a>
    </li>


</div>