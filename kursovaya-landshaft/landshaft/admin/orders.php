<?php
require_once '../includes/functions.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset(ORDER_STATUSES[$_POST['status']])) {
    db()->prepare('UPDATE orders SET status = ? WHERE id = ?')->execute([$_POST['status'], (int)$_POST['id']]);
    flash('Статус заказа №' . (int)$_POST['id'] . ' изменён');
    redirect('admin/orders.php');
}

$title  = 'Заказы';
$orders = db()->query(
    'SELECT orders.*, users.name, users.phone, services.title, services.unit
     FROM orders
     JOIN users    ON users.id    = orders.user_id
     JOIN services ON services.id = orders.service_id
     ORDER BY orders.created_at DESC'
)->fetchAll();

require '../includes/header.php';
require 'nav.php';
?>

<section class="section container">
    <h2>Все заказы</h2>
    <table class="table">
        <tr><th>№</th><th>Дата</th><th>Клиент</th><th>Услуга</th><th>Объём</th><th>Сумма</th><th>Комментарий</th><th>Статус</th></tr>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= $order['id'] ?></td>
                <td><?= date('d.m.Y', strtotime($order['created_at'])) ?></td>
                <td><?= e($order['name']) ?><br><small><?= e($order['phone']) ?></small></td>
                <td><?= e($order['title']) ?></td>
                <td><?= $order['quantity'] ?> <?= e($order['unit']) ?></td>
                <td><?= money($order['total']) ?></td>
                <td><?= e($order['comment']) ?></td>
                <td>
                    <form method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= $order['id'] ?>">
                        <select name="status" class="status status-<?= $order['status'] ?>" data-autosubmit>
                            <?php foreach (ORDER_STATUSES as $key => $label): ?>
                                <option value="<?= $key ?>" <?= $key === $order['status'] ? 'selected' : '' ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php if (!$orders): ?><p class="empty">Заказов пока нет.</p><?php endif; ?>
</section>

<?php require '../includes/footer.php'; ?>
