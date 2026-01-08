<?php
session_start();
require_once "../../config/database.php";

/* ✅ Chặn nếu không phải admin */
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /camerashop/login.php");
    exit;
}

/* ✅ Xử lý POST (đổi role) */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['role'])) {

    $user_id = (int)$_POST['user_id'];
    $role = $_POST['role'];

    if (in_array($role, ['user', 'admin'])) {

        $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->execute([$role, $user_id]);

        /* ✅ Nếu đổi role chính mình → cập nhật session */
        if ($user_id === $_SESSION['user']['id']) {
            $_SESSION['user']['role'] = $role;
        }
    }

    /* ✅ Reload lại chính trang user.php */
    header("Location: user.php");
    exit;
}

/* ✅ Load lại danh sách user (GET) */
$stmt = $pdo->query("SELECT id, name, email, role FROM users ORDER BY id DESC");
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý người dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-4">👤 Quản lý người dùng</h2>

    <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
        <tr>
            <th>Tên</th>
            <th>Email</th>
            <th>Role</th>
            <th>Thao tác</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?= htmlspecialchars($u['name']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td>
                    <span class="badge <?= $u['role'] === 'admin' ? 'bg-danger' : 'bg-secondary' ?>">
                        <?= $u['role'] ?>
                    </span>
                </td>
                <td>
                    <?php if ($u['id'] != $_SESSION['user']['id']): ?>
                        <form method="post" class="d-inline">
                            <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                            <select name="role" class="form-select form-select-sm d-inline w-auto">
                                <option value="user" <?= $u['role'] === 'user' ? 'selected' : '' ?>>User</option>
                                <option value="admin" <?= $u['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary ms-1">
                                Lưu
                            </button>
                        </form>
                    <?php else: ?>
                        <em class="text-muted">Bạn</em>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
