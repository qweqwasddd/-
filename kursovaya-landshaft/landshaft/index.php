<?php
require_once 'includes/functions.php';

$title    = 'Главная';
$services = db()->query('SELECT * FROM services ORDER BY id LIMIT 3')->fetchAll();
$projects = db()->query('SELECT * FROM projects ORDER BY year DESC, id DESC LIMIT 3')->fetchAll();
$count    = db()->query('SELECT COUNT(*) FROM projects')->fetchColumn();

require 'includes/header.php';
?>

<section class="hero">
    <div class="container hero__inner">
        <div class="hero__text">
            <h1>Ландшафтный дизайн и благоустройство участков в Красноярске</h1>
            <p>Разрабатываем проект сада, сажаем растения, делаем дорожки, газоны и водоёмы. Работаем с частными домами и дачами с 2015 года.</p>
            <div class="hero__buttons">
                <a class="btn" href="services.php">Услуги и цены</a>
                <a class="btn btn--outline" href="portfolio.php">Наши работы</a>
            </div>
            <p class="hero__note">Выезд на участок и консультация — бесплатно. В портфолио <?= $count ?> проектов.</p>
        </div>
        <img class="hero__img" src="<?= BASE_URL ?>assets/img/hero.svg" alt="Сад с дорожкой">
    </div>
</section>

<section class="section container">
    <h2>Почему выбирают нас</h2>
    <div class="features">
        <div class="feature"><h3>Растения для Сибири</h3><p>Подбираем только зимостойкие сорта, которые переживут морозы.</p></div>
        <div class="feature"><h3>Подробный проект</h3><p>Генплан, дендроплан и 3D-модель — вы видите сад до начала работ.</p></div>
        <div class="feature"><h3>Без субподрядчиков</h3><p>Проектируем, строим и ухаживаем за садом своей командой.</p></div>
        <div class="feature"><h3>Гарантия 2 года</h3><p>Бесплатно заменим растение, если оно не прижилось.</p></div>
    </div>
</section>

<section class="section section--light">
    <div class="container">
        <div class="section__head">
            <h2>Популярные услуги</h2>
            <a href="services.php">Все услуги</a>
        </div>
        <div class="grid">
            <?php foreach ($services as $service) require 'includes/service_card.php'; ?>
        </div>
    </div>
</section>

<section class="section container">
    <h2>Как мы работаем</h2>
    <ol class="steps">
        <li><strong>Заявка</strong>Оставляете заказ на сайте или звоните нам.</li>
        <li><strong>Выезд</strong>Специалист осматривает участок и делает замеры.</li>
        <li><strong>Проект</strong>Согласовываем план, смету и сроки.</li>
        <li><strong>Реализация</strong>Создаём сад и передаём его вам с рекомендациями по уходу.</li>
    </ol>
</section>

<section class="section section--light">
    <div class="container">
        <div class="section__head">
            <h2>Наши работы</h2>
            <a href="portfolio.php">Всё портфолио</a>
        </div>
        <div class="grid">
            <?php foreach ($projects as $project) require 'includes/project_card.php'; ?>
        </div>
    </div>
</section>

<section class="section container">
    <div class="cta">
        <div>
            <h2>Нужна консультация?</h2>
            <p>Позвоните по телефону 1 (234) 567 89 10 или оставьте заявку — перезвоним в течение рабочего дня.</p>
        </div>
        <a class="btn" href="contacts.php">Оставить заявку</a>
    </div>
</section>

<?php require 'includes/footer.php'; ?>
