<?php
require_once 'includes/functions.php';

$title = 'Регистрация';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $password = $_POST['password'];

    $stmt = db()->prepare('SELECT COUNT(*) FROM users WHERE email = ?');
    $stmt->execute([$email]);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Введите корректный e-mail';
    } elseif (mb_strlen($password) < 6) {
        $error = 'Пароль должен быть не короче 6 символов';
    } elseif ($password !== $_POST['password2']) {
        $error = 'Пароли не совпадают';
    } elseif ($stmt->fetchColumn() > 0) {
        $error = 'Пользователь с таким e-mail уже зарегистрирован';
    } else {
        $stmt = db()->prepare('INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)');
        $stmt->execute([$name, $email, $phone, password_hash($password, PASSWORD_DEFAULT)]);

        session_regenerate_id(true);
        $_SESSION['user'] = ['id' => db()->lastInsertId(), 'name' => $name, 'email' => $email, 'phone' => $phone, 'role' => 'user'];
        flash('Добро пожаловать, ' . $name . '!');
        redirect('profile.php');
    }
}

require 'includes/header.php';
?>

<section class="section container auth">
    <form class="form" method="post">
        <?= csrf_field() ?>
        <h1>Регистрация</h1>
        <?php if ($error): ?><p class="form__error"><?= e($error) ?></p><?php endif; ?>
        <label>Имя <input type="text" name="name" required value="<?= e($_POST['name'] ?? '') ?>"></label>
        <label>E-mail <input type="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>"></label>
        <label>Телефон <input type="tel" name="phone" placeholder="+7 (___) ___-__-__" value="<?= e($_POST['phone'] ?? '') ?>"></label>
        <label>Пароль <input type="password" name="password" required minlength="6"></label>
        <label>Повторите пароль <input type="password" name="password2" required minlength="6"></label>
        <button class="btn" type="submit">Зарегистрироваться</button>
        <p class="form__hint">Уже есть аккаунт? <a href="login.php">Войти</a></p>
    </form>
</section>

<?php require 'includes/footer.php'; ?>
