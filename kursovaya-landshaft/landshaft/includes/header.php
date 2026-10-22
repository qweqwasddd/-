<?php
// Шапка сайта. Перед подключением страница задаёт переменную $title.
$user    = current_user();
$current = basename($_SERVER['SCRIPT_NAME']);
$inAdmin = strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false;

// Пункты главного меню: файл => подпись
$menu = [
    'index.php'     => 'Главная',
    'services.php'  => 'Услуги',
    'portfolio.php' => 'Портфолио',
    'contacts.php'  => 'Контакты',
];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?> — <?= SITE_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body>
<header class="header">
    <div class="container header__inner">
        <a class="logo" href="<?= BASE_URL ?>index.php">
            <span class="logo__icon">❦</span> <?= SITE_NAME ?>
        </a>

        <button class="burger" type="button" aria-label="Меню">☰</button>

        <nav class="nav">
            <?php foreach ($menu as $file => $label): ?>
                <a href="<?= BASE_URL . $file ?>"
                   class="<?= !$inAdmin && $current === $file ? 'active' : '' ?>"><?= $label ?></a>
            <?php endforeach; ?>

            <span class="nav__sep"></span>

            <?php if ($user): ?>
                <?php if (is_admin()): ?>
                    <a href="<?= BASE_URL ?>admin/index.php" class="<?= $inAdmin ? 'active' : '' ?>">Админ-панель</a>
                <?php endif; ?>
                <a href="<?= BASE_URL ?>profile.php" class="<?= $current === 'profile.php' ? 'active' : '' ?>">
                    👤 <?= e($user['name']) ?>
                </a>
                <a href="<?= BASE_URL ?>logout.php">Выйти</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>login.php">Войти</a>
                <a href="<?= BASE_URL ?>register.php" class="btn btn--small">Регистрация</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<?php if ($flash = take_flash()): ?>
    <div class="flash flash--<?= $flash['type'] ?>"><?= e($flash['text']) ?></div>
<?php endif; ?>

<main>
