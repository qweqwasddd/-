<?php
// Вкладки админ-панели: файл => подпись
$tabs = [
    'index.php'    => 'Сводка',
    'orders.php'   => 'Заказы',
    'services.php' => 'Услуги',
    'projects.php' => 'Портфолио',
    'users.php'    => 'Пользователи',
];
?>
<section class="page-head page-head--admin">
    <div class="container">
        <h1>Админ-панель</h1>
        <nav class="tabs">
            <?php foreach ($tabs as $file => $label): ?>
                <a href="<?= $file ?>" class="<?= basename($_SERVER['SCRIPT_NAME']) === $file ? 'active' : '' ?>"><?= $label ?></a>
            <?php endforeach; ?>
        </nav>
    </div>
</section>
