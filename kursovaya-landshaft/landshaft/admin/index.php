<?php
require_once '../includes/functions.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    db()->prepare('DELETE FROM messages WHERE id = ?')->execute([(int)$_POST['id']]);
    flash('Обращение удалено');
    redirect('admin/index.php');
}

$title = 'Админ-панель';
$stats = [
    'Новых заказов'   => db()->query("SELECT COUNT(*) FROM orders WHERE status = 'new'")->fetchColumn(),
    'Заказов в работе' => db()->query("SELECT COUNT(*) FROM orders WHERE status = 'in_work'")->fetchColumn(),
    'Выручка, ₽'      => number_format(db()->query("SELECT COALESCE(SUM(total), 0) FROM orders WHERE status = 'done'")->fetchColumn(), 0, ',', ' '),
    'Клиентов'        => db()->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn(),
];
$messages = db()->query('SELECT * FROM messages ORDER BY created_at DESC')->fetchAll();

require '../includes/header.php';
require 'nav.php';
?>

<section class="section container">
    <div class="stats stats--admin">
        <?php foreach ($stats as $label => $value): ?>
            <div class="stats__item"><strong><?= $value ?></strong><span><?= $label ?></span></div>
        <?php endforeach; ?>
    </div>

    <h2>Обращения с сайта</h2>
    <table class="table">
        <tr><th>Дата</th><th>Имя</th><th>Телефон</th><th>Сообщение</th><th></th></tr>
        <?php foreach ($messages as $message): ?>
            <tr>
                <td><?= date('d.m.Y H:i', strtotime($message['created_at'])) ?></td>
                <td><?= e($message['name']) ?></td>
                <td><?= e($message['phone']) ?></td>
                <td><?= e($message['text']) ?></td>
                <td>
                    <form method="post" data-confirm="Удалить обращение?">
                        <input type="hidden" name="id" value="<?= $message['id'] ?>">
                        <button class="link-button link-button--danger">Удалить</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php if (!$messages): ?><p class="empty">Новых обращений нет.</p><?php endif; ?>
</section>

<?php require '../includes/footer.php'; ?>
