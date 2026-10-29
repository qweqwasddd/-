<?php
$user    = current_user();
$current = basename($_SERVER['SCRIPT_NAME']);
$inAdmin = strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false;

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
    <link href="https://fonts.googleapis.com/css2?family=PT+Sans:wght@400;700&family=PT+Serif:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css?v=<?= filemtime(__DIR__ . '/../assets/css/style.css') ?>">
</head>
<body>
<header class="header">
    <div class="container header__inner">
        <a class="logo" href="<?= BASE_URL ?>index.php"><?= SITE_NAME ?><small>ландшафтный дизайн</small></a>

        <button class="burger" type="button">Меню</button>

        <nav class="nav">
            <div class="nav__menu">
                <?php foreach ($menu as $file => $label): ?>
                    <a href="<?= BASE_URL . $file ?>"
                       class="<?= !$inAdmin && $current === $file ? 'active' : '' ?>"><span><?= $label ?></span></a>
                <?php endforeach; ?>
                <?php if (is_admin()): ?>
                    <a href="<?= BASE_URL ?>admin/index.php" class="<?= $inAdmin ? 'active' : '' ?>"><span>Админ-панель</span></a>
                <?php endif; ?>
            </div>

            <div class="nav__actions">
                <a class="header__phone" href="tel:12345678910">1 (234) 567 89 10</a>
                <?php if ($user): ?>
                    <a href="<?= BASE_URL ?>profile.php" class="<?= $current === 'profile.php' ? 'active' : '' ?>"><?= e($user['name']) ?></a>
                    <a href="<?= BASE_URL ?>logout.php">Выйти</a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>login.php">Войти</a>
                    <a href="<?= BASE_URL ?>register.php" class="btn btn--small">Регистрация</a>
                <?php endif; ?>
            </div>
        </nav>
    </div>
</header>

<?php if ($flash = take_flash()): ?>
    <div class="flash flash--<?= $flash['type'] ?>"><?= e($flash['text']) ?></div>
<?php endif; ?>

<main>
