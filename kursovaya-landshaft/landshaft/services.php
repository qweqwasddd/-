<?php
require_once 'includes/functions.php';

$title      = 'Услуги';
$categories = db()->query('SELECT DISTINCT category FROM services ORDER BY category')->fetchAll(PDO::FETCH_COLUMN);
$category   = $_GET['category'] ?? '';

if ($category !== '') {
    $stmt = db()->prepare('SELECT * FROM services WHERE category = ? ORDER BY price');
    $stmt->execute([$category]);
} else {
    $stmt = db()->query('SELECT * FROM services ORDER BY category, price');
}
$services = $stmt->fetchAll();

require 'includes/header.php';
?>

<section class="page-head">
    <div class="container">
        <h1>Услуги студии</h1>
        <p>Выберите услугу, укажите объём работ — стоимость рассчитается автоматически.</p>
    </div>
</section>

<section class="section container">
    <div class="filters">
        <a href="services.php" class="<?= $category === '' ? 'active' : '' ?>">Все</a>
        <?php foreach ($categories as $cat): ?>
            <a href="services.php?category=<?= urlencode($cat) ?>"
               class="<?= $category === $cat ? 'active' : '' ?>"><?= e($cat) ?></a>
        <?php endforeach; ?>
    </div>

    <div class="grid">
        <?php foreach ($services as $service) require 'includes/service_card.php'; ?>
    </div>

    <?php if (!$services): ?>
        <p class="empty">В этой категории пока нет услуг.</p>
    <?php endif; ?>
</section>

<?php require 'includes/footer.php'; ?>
