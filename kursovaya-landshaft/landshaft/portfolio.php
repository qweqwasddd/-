<?php
require_once 'includes/functions.php';

$title    = 'Портфолио';
$projects = db()->query('SELECT * FROM projects ORDER BY year DESC, id DESC')->fetchAll();

require 'includes/header.php';
?>

<section class="page-head">
    <div class="container">
        <h1>Портфолио</h1>
        <p>Сады, которые мы создали для наших клиентов. Нажмите на проект, чтобы узнать подробности.</p>
    </div>
</section>

<section class="section container">
    <div class="grid">
        <?php foreach ($projects as $project) require 'includes/project_card.php'; ?>
    </div>
</section>

<div class="lightbox" id="lightbox">
    <div class="lightbox__box">
        <button class="lightbox__close" type="button">×</button>
        <img src="" alt="">
        <h3></h3>
        <p></p>
    </div>
</div>

<?php require 'includes/footer.php'; ?>
