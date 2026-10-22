<?php
require_once '../includes/functions.php';
require_admin();

$me = current_user();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];

    if ($_POST['action'] === 'delete') {
        // Свою учётную запись удалить нельзя, иначе можно остаться без администратора
        if ($id === (int)$me['id']) {
            flash('Нельзя удалить свою учётную запись', 'error');
        } else {
            db()->prepare('DELETE FROM users WHERE id = ?')->execute([$id]);
            flash('Пользователь удалён');
        }
    } else {
        $name  = trim($_POST['name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $role  = $_POST['role'] === 'admin' ? 'admin' : 'user';

        // E-mail должен быть уникальным (у другого пользователя такого быть не может)
        $stmt = db()->prepare('SELECT COUNT(*) FROM users WHERE email = ? AND id <> ?');
        $stmt->execute([$email, $id]);

        if ($stmt->fetchColumn() > 0) {
            flash('Этот e-mail уже занят другим пользователем', 'error');
        } elseif ($id) {
            db()->prepare('UPDATE users SET name = ?, email = ?, phone = ?, role = ? WHERE id = ?')
                ->execute([$name, $email, $phone, $role, $id]);
            if ($_POST['password'] !== '') {
                db()->prepare('UPDATE users SET password = ? WHERE id = ?')
                    ->execute([password_hash($_POST['password'], PASSWORD_DEFAULT), $id]);
            }
            flash('Данные пользователя обновлены');
        } elseif ($_POST['password'] === '') {
            flash('Для нового пользователя укажите пароль', 'error');
        } else {
            db()->prepare('INSERT INTO users (name, email, phone, role, password) VALUES (?, ?, ?, ?, ?)')
                ->execute([$name, $email, $phone, $role, password_hash($_POST['password'], PASSWORD_DEFAULT)]);
            flash('Пользователь добавлен');
        }
    }
    redirect('admin/users.php');
}

// ?edit=ID — редактирование пользователя, без параметра — добавление нового
$edit = ['id' => 0, 'name' => '', 'email' => '', 'phone' => '', 'role' => 'user'];
if (isset($_GET['edit'])) {
    $stmt = db()->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([(int)$_GET['edit']]);
    $edit = $stmt->fetch() ?: $edit;
}

$title = 'Пользователи';
$users = db()->query(
    'SELECT users.*, COUNT(orders.id) AS orders_count
     FROM users LEFT JOIN orders ON orders.user_id = users.id
     GROUP BY users.id ORDER BY users.created_at DESC'
)->fetchAll();

require '../includes/header.php';
require 'nav.php';
?>

<section class="section container admin-layout">
    <form class="form" method="post">
        <h2><?= $edit['id'] ? 'Редактирование пользователя' : 'Новый пользователь' ?></h2>
        <input type="hidden" name="id" value="<?= $edit['id'] ?>">
        <label>Имя <input type="text" name="name" required value="<?= e($edit['name']) ?>"></label>
        <label>E-mail <input type="email" name="email" required value="<?= e($edit['email']) ?>"></label>
        <label>Телефон <input type="tel" name="phone" value="<?= e($edit['phone']) ?>"></label>
        <label>Роль
            <select name="role">
                <option value="user">Клиент</option>
                <option value="admin" <?= $edit['role'] === 'admin' ? 'selected' : '' ?>>Администратор</option>
            </select>
        </label>
        <label>Пароль <input type="password" name="password" minlength="6"
               placeholder="<?= $edit['id'] ? 'Оставьте пустым, чтобы не менять' : '' ?>"></label>
        <button class="btn" type="submit">Сохранить</button>
        <?php if ($edit['id']): ?><a class="btn btn--outline" href="users.php">Отмена</a><?php endif; ?>
    </form>

    <table class="table">
        <tr><th>Имя</th><th>E-mail</th><th>Телефон</th><th>Роль</th><th>Заказов</th><th></th></tr>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?= e($u['name']) ?></td>
                <td><?= e($u['email']) ?></td>
                <td><?= e($u['phone']) ?></td>
                <td><?= $u['role'] === 'admin' ? 'Администратор' : 'Клиент' ?></td>
                <td><?= $u['orders_count'] ?></td>
                <td class="table__actions">
                    <a href="?edit=<?= $u['id'] ?>">Изменить</a>
                    <?php if ($u['id'] != $me['id']): ?>
                        <form method="post" data-confirm="Удалить пользователя и все его заказы?">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $u['id'] ?>">
                            <button class="link-button link-button--danger">Удалить</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>

<?php require '../includes/footer.php'; ?>
