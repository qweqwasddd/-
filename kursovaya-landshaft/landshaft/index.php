<?php
require_once 'includes/functions.php';

$title    = 'Главная';
$services = db()->query('SELECT * FROM services ORDER BY id LIMIT 3')->fetchAll();
$projects = db()->query('SELECT * FROM projects ORDER BY year DESC, id DESC LIMIT 3')->fetchAll();
$stats    = [
    'проектов в портфолио' => db()->query('SELECT COUNT(*) FROM projects')->fetchColumn(),
    'видов услуг'          => db()->query('SELECT COUNT(*) FROM services')->fetchColumn(),
    'лет работы'           => date('Y') - 2015,
];

require 'includes/header.php';
?>

<section class="hero" style="background-image: url('<?= BASE_URL ?>assets/img/hero.svg')">
    <div class="container hero__content">
        <h1>Создаём сады,<br>в которых хочется жить</h1>
        <p>Проектирование, озеленение и благоустройство частных участков в Красноярске и пригороде. От идеи до первого урожая.</p>
        <div class="hero__buttons">
            <a class="btn" href="services.php">Выбрать услугу</a>
            <a class="btn btn--light" href="portfolio.php">Смотреть работы</a>
        </div>
    </div>
</section>

<section class="container stats">
    <?php foreach ($stats as $label => $value): ?>
        <div class="stats__item">
            <strong><?= $value ?></strong>
            <span><?= $label ?></span>
        </div>
    <?php endforeach; ?>
</section>

<section class="section container">
    <h2 class="section__title">Почему выбирают нас</h2>
    <div class="features">
        <div class="feature"><span>🌿</span><h3>Растения для Сибири</h3><p>Подбираем только зимостойкие сорта, которые переживут морозы.</p></div>
        <div class="feature"><span>📐</span><h3>Проект до мелочей</h3><p>Генплан, дендроплан и 3D-модель — видите сад до начала работ.</p></div>
        <div class="feature"><span>🤝</span><h3>Один договор</h3><p>Проектируем, строим и ухаживаем сами, без субподрядчиков.</p></div>
        <div class="feature"><span>🛡️</span><h3>Гарантия 2 года</h3><p>Бесплатно заменим растение, если оно не прижилось.</p></div>
    </div>
</section>

<section class="section section--tinted">
    <div class="container">
        <h2 class="section__title">Популярные услуги</h2>
        <div class="grid">
            <?php foreach ($services as $service) require 'includes/service_card.php'; ?>
        </div>
        <div class="center"><a class="btn btn--outline" href="services.php">Все услуги</a></div>
    </div>
</section>

<section class="section container">
    <h2 class="section__title">Как мы работаем</h2>
    <ol class="steps">
        <li><strong>Заявка</strong>Оставляете заказ на сайте или звоните нам</li>
        <li><strong>Выезд</strong>Специалист осматривает участок и делает замеры</li>
        <li><strong>Проект</strong>Согласовываем план, смету и сроки</li>
        <li><strong>Реализация</strong>Создаём сад и передаём его вам с рекомендациями</li>
    </ol>
</section>

<section class="section section--tinted">
    <div class="container">
        <h2 class="section__title">Наши работы</h2>
        <div class="grid">
            <?php foreach ($projects as $project) require 'includes/project_card.php'; ?>
        </div>
        <div class="center"><a class="btn btn--outline" href="portfolio.php">Всё портфолио</a></div>
    </div>
</section>

<section class="cta container">
    <h2>Хотите такой же сад?</h2>
    <p>Расскажите о своём участке — перезвоним и бесплатно проконсультируем.</p>
    <a class="btn btn--light" href="contacts.php">Получить консультацию</a>
</section>

<?php require 'includes/footer.php'; ?>
