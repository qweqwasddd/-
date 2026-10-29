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

<section class="container hero">
    <div class="hero__media" style="background-image: url('<?= BASE_URL ?>assets/img/hero.svg')">
        <span class="eyebrow eyebrow--light">Студия ландшафтного дизайна · Красноярск</span>
        <h1>Создаём сады, в&nbsp;которых хочется жить</h1>
        <div class="hero__bottom">
            <p>Проектирование, озеленение и благоустройство частных участков. От идеи до первого урожая.</p>
            <div class="hero__buttons">
                <a class="btn btn--accent" href="services.php">Выбрать услугу</a>
                <a class="btn btn--ghost" href="portfolio.php">Портфолио</a>
            </div>
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
    <div class="section-head">
        <span class="eyebrow">Почему мы</span>
        <h2>Делаем сад под ваш участок, климат и образ жизни</h2>
    </div>
    <div class="features">
        <div class="feature"><span class="feature__num">01</span><h3>Растения для Сибири</h3><p>Подбираем только зимостойкие сорта, которые переживут морозы.</p></div>
        <div class="feature"><span class="feature__num">02</span><h3>Проект до мелочей</h3><p>Генплан, дендроплан и 3D-модель — видите сад до начала работ.</p></div>
        <div class="feature"><span class="feature__num">03</span><h3>Один договор</h3><p>Проектируем, строим и ухаживаем сами, без субподрядчиков.</p></div>
        <div class="feature"><span class="feature__num">04</span><h3>Гарантия 2 года</h3><p>Бесплатно заменим растение, если оно не прижилось.</p></div>
    </div>
</section>

<section class="section container">
    <div class="section-head section-head--row">
        <div>
            <span class="eyebrow">Услуги</span>
            <h2>Популярные услуги</h2>
        </div>
        <a class="link-arrow" href="services.php">Все услуги</a>
    </div>
    <div class="grid">
        <?php foreach ($services as $service) require 'includes/service_card.php'; ?>
    </div>
</section>

<section class="section section--dark">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow eyebrow--light">Процесс</span>
            <h2>Как мы работаем</h2>
        </div>
        <ol class="steps">
            <li><strong>Заявка</strong>Оставляете заказ на сайте или звоните нам</li>
            <li><strong>Выезд</strong>Специалист осматривает участок и делает замеры</li>
            <li><strong>Проект</strong>Согласовываем план, смету и сроки</li>
            <li><strong>Реализация</strong>Создаём сад и передаём его вам с рекомендациями</li>
        </ol>
    </div>
</section>

<section class="section container">
    <div class="section-head section-head--row">
        <div>
            <span class="eyebrow">Портфолио</span>
            <h2>Последние работы</h2>
        </div>
        <a class="link-arrow" href="portfolio.php">Все проекты</a>
    </div>
    <div class="grid">
        <?php foreach ($projects as $project) require 'includes/project_card.php'; ?>
    </div>
</section>

<section class="container cta">
    <span class="eyebrow">Консультация</span>
    <h2>Расскажите о своём участке —<br>перезвоним и бесплатно проконсультируем</h2>
    <a class="btn btn--accent" href="contacts.php">Получить консультацию</a>
</section>

<?php require 'includes/footer.php'; ?>
