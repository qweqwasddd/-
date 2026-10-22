<?php
require_once 'includes/functions.php';

$stmt = db()->prepare('SELECT * FROM services WHERE id = ?');
$stmt->execute([(int)($_GET['id'] ?? 0)]);
$service = $stmt->fetch();

if (!$service) {
    flash('Услуга не найдена', 'error');
    redirect('services.php');
}

$title = $service['title'];
$user  = current_user();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_login();
    $quantity = max(1, (int)$_POST['quantity']);
    // Стоимость считается на сервере: значению из браузера доверять нельзя
    $total    = $quantity * $service['price'];

    $stmt = db()->prepare('INSERT INTO orders (user_id, service_id, quantity, total, comment) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$user['id'], $service['id'], $quantity, $total, trim($_POST['comment'])]);

    flash('Заказ оформлен! Менеджер свяжется с вами для уточнения деталей');
    redirect('profile.php');
}

require 'includes/header.php';
?>

<section class="section container two-columns">
    <div>
        <img class="order__img" src="<?= image_url($service['image']) ?>" alt="<?= e($service['title']) ?>">
        <span class="tag"><?= e($service['category']) ?></span>
        <h1><?= e($service['title']) ?></h1>
        <p><?= e($service['description']) ?></p>
        <p class="price">от <?= money($service['price']) ?> / <?= e($service['unit']) ?></p>
    </div>

    <?php if ($user): ?>
        <form class="form" method="post">
            <h2>Оформление заказа</h2>
            <label>Количество, <?= e($service['unit']) ?>
                <input type="number" name="quantity" min="1" value="1" required
                       id="quantity" data-price="<?= $service['price'] ?>">
            </label>
            <label>Комментарий
                <textarea name="comment" rows="4" placeholder="Адрес участка, удобное время звонка, пожелания"></textarea>
            </label>
            <p class="total">Предварительная стоимость: <strong id="total"><?= money($service['price']) ?></strong></p>
            <button class="btn" type="submit">Заказать</button>
        </form>
    <?php else: ?>
        <div class="form">
            <h2>Оформление заказа</h2>
            <p>Чтобы заказать услугу и отслеживать её статус, войдите в аккаунт или зарегистрируйтесь — это займёт минуту.</p>
            <a class="btn" href="login.php">Войти</a>
            <a class="btn btn--outline" href="register.php">Регистрация</a>
        </div>
    <?php endif; ?>
</section>

<?php require 'includes/footer.php'; ?>
