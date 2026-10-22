<?php
require_once 'includes/functions.php';

$title = 'Вход';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = db()->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([trim($_POST['email'])]);
    $found = $stmt->fetch();

    if ($found && password_verify($_POST['password'], $found['password'])) {
        unset($found['password']);
        $_SESSION['user'] = $found;
        redirect($found['role'] === 'admin' ? 'admin/index.php' : 'profile.php');
    }
    $error = 'Неверный e-mail или пароль';
}

require 'includes/header.php';
?>

<section class="section container auth">
    <form class="form" method="post">
        <h1>Вход</h1>
        <?php if ($error): ?><p class="form__error"><?= e($error) ?></p><?php endif; ?>
        <label>E-mail <input type="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>"></label>
        <label>Пароль <input type="password" name="password" required></label>
        <button class="btn" type="submit">Войти</button>
        <p class="form__hint">Нет аккаунта? <a href="register.php">Зарегистрироваться</a></p>
    </form>
</section>

<?php require 'includes/footer.php'; ?>
