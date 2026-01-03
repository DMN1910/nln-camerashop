<?php
require_once "../config/database.php";
require_once "../controllers/AuthController.php";

$auth = new AuthController($pdo);
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $auth->login($_POST['email'], $_POST['password']);

    if ($result === true) {
        header("Location: index.php");
        exit;
    } else {
        $error = $result;
    }
}
?>

<form method="post" class="container mt-5 col-md-4">
    <h3>Đăng nhập</h3>
    <input class="form-control mb-2" name="email" type="email" placeholder="Email" required>
    <input class="form-control mb-2" name="password" type="password" placeholder="Mật khẩu" required>
    <button class="btn btn-success w-100">Đăng nhập</button>
    <p class="text-danger mt-2"><?= $error ?></p>
</form>
