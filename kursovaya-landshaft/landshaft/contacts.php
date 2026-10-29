<?php
require_once 'includes/functions.php';

$title = 'Контакты';
$user  = current_user();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $text  = trim($_POST['text']);

    if ($name === '' || $phone === '' || $text === '') {
        flash('Заполните все поля формы', 'error');
    } else {
        $stmt = db()->prepare('INSERT INTO messages (name, phone, text) VALUES (?, ?, ?)');
        $stmt->execute([$name, $phone, $text]);
        flash('Спасибо! Мы перезвоним вам в течение рабочего дня');
    }
    redirect('contacts.php');
}

require 'includes/header.php';
?>

<section class="page-head">
    <div class="container">
        <h1>Контакты</h1>
        <p>Приезжайте в офис, звоните или оставьте сообщение — ответим на любые вопросы о вашем саде.</p>
    </div>
</section>

<section class="section container two-columns">
    <div class="contacts">
        <div><span class="eyebrow">Адрес</span><p>г. Красноярск, ул. Садовая, 12</p></div>
        <div><span class="eyebrow">Телефон</span><p>+7 (391) 200-10-10</p></div>
        <div><span class="eyebrow">Почта</span><p>info@zdvor.ru</p></div>
        <div><span class="eyebrow">Режим работы</span><p>Пн–Сб, 9:00–19:00</p></div>
    </div>

    <form class="form" method="post">
        <h2>Бесплатная консультация</h2>
        <label>Ваше имя
            <input type="text" name="name" required value="<?= e($user['name'] ?? '') ?>">
        </label>
        <label>Телефон
            <input type="tel" name="phone" required placeholder="+7 (___) ___-__-__" value="<?= e($user['phone'] ?? '') ?>">
        </label>
        <label>Сообщение
            <textarea name="text" rows="4" required placeholder="Опишите участок и ваши пожелания"></textarea>
        </label>
        <button class="btn" type="submit">Отправить</button>
    </form>
</section>

<?php require 'includes/footer.php'; ?>
