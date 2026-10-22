<?php
require_once 'includes/functions.php';
require_login();

$title = 'Личный кабинет';
$user  = current_user();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'update') {
        $name  = trim($_POST['name']);
        $phone = trim($_POST['phone']);
        db()->prepare('UPDATE users SET name = ?, phone = ? WHERE id = ?')->execute([$name, $phone, $user['id']]);

        if ($_POST['password'] !== '') {
            if (mb_strlen($_POST['password']) < 6) {
                flash('Пароль должен быть не короче 6 символов', 'error');
                redirect('profile.php');
            }
            $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            db()->prepare('UPDATE users SET password = ? WHERE id = ?')->execute([$hash, $user['id']]);
        }

        $_SESSION['user']['name']  = $name;
        $_SESSION['user']['phone'] = $phone;
        flash('Данные сохранены');
    }

    // Клиент может отменить только свой заказ и только пока он не взят в работу
    if ($action === 'cancel') {
        db()->prepare("UPDATE orders SET status = 'canceled' WHERE id = ? AND user_id = ? AND status = 'new'")
            ->execute([(int)$_POST['order_id'], $user['id']]);
        flash('Заказ отменён');
    }
    redirect('profile.php');
}

$stmt = db()->prepare(
    'SELECT orders.*, services.title, services.unit
     FROM orders JOIN services ON services.id = orders.service_id
     WHERE orders.user_id = ? ORDER BY orders.created_at DESC'
);
$stmt->execute([$user['id']]);
$orders = $stmt->fetchAll();

require 'includes/header.php';
?>

<section class="page-head">
    <div class="container">
        <h1>Личный кабинет</h1>
        <p><?= e($user['email']) ?> · <?= $user['role'] === 'admin' ? 'Администратор' : 'Клиент' ?></p>
    </div>
</section>

<section class="section container profile">
    <form class="form" method="post">
        <h2>Мои данные</h2>
        <input type="hidden" name="action" value="update">
        <label>Имя <input type="text" name="name" required value="<?= e($user['name']) ?>"></label>
        <label>Телефон <input type="tel" name="phone" value="<?= e($user['phone']) ?>"></label>
        <label>Новый пароль <input type="password" name="password" placeholder="Оставьте пустым, чтобы не менять"></label>
        <button class="btn" type="submit">Сохранить</button>
    </form>

    <div>
        <h2>Мои заказы</h2>
        <?php if (!$orders): ?>
            <p class="empty">Заказов пока нет. <a href="services.php">Выбрать услугу</a></p>
        <?php endif; ?>

        <?php foreach ($orders as $order): ?>
            <div class="order-row">
                <div>
                    <strong><?= e($order['title']) ?></strong>
                    <span><?= $order['quantity'] ?> <?= e($order['unit']) ?> · <?= money($order['total']) ?> · <?= date('d.m.Y', strtotime($order['created_at'])) ?></span>
                    <?php if ($order['comment']): ?><em><?= e($order['comment']) ?></em><?php endif; ?>
                </div>
                <div class="order-row__side">
                    <span class="status status-<?= $order['status'] ?>"><?= ORDER_STATUSES[$order['status']] ?></span>
                    <?php if ($order['status'] === 'new'): ?>
                        <form method="post" data-confirm="Отменить заказ?">
                            <input type="hidden" name="action" value="cancel">
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                            <button class="link-button" type="submit">Отменить</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php require 'includes/footer.php'; ?>
