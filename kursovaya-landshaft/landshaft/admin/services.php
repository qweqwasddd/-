<?php
require_once '../includes/functions.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];

    if ($_POST['action'] === 'delete') {
        db()->prepare('DELETE FROM services WHERE id = ?')->execute([$id]);
        flash('Услуга удалена');
    } else {
        $data = [
            trim($_POST['title']),
            trim($_POST['category']),
            trim($_POST['description']),
            (float)$_POST['price'],
            trim($_POST['unit']),
            upload_image('image', $_POST['old_image']),
        ];
        if ($id) {
            $data[] = $id;
            db()->prepare('UPDATE services SET title = ?, category = ?, description = ?, price = ?, unit = ?, image = ? WHERE id = ?')->execute($data);
            flash('Услуга обновлена');
        } else {
            db()->prepare('INSERT INTO services (title, category, description, price, unit, image) VALUES (?, ?, ?, ?, ?, ?)')->execute($data);
            flash('Услуга добавлена');
        }
    }
    redirect('admin/services.php');
}

$edit = ['id' => 0, 'title' => '', 'category' => '', 'description' => '', 'price' => '', 'unit' => 'м²', 'image' => ''];
if (isset($_GET['edit'])) {
    $stmt = db()->prepare('SELECT * FROM services WHERE id = ?');
    $stmt->execute([(int)$_GET['edit']]);
    $edit = $stmt->fetch() ?: $edit;
}

$title      = 'Услуги';
$services   = db()->query('SELECT * FROM services ORDER BY category, title')->fetchAll();
$categories = db()->query('SELECT DISTINCT category FROM services')->fetchAll(PDO::FETCH_COLUMN);

require '../includes/header.php';
require 'nav.php';
?>

<section class="section container admin-layout">
    <form class="form" method="post" enctype="multipart/form-data">
        <h2><?= $edit['id'] ? 'Редактирование услуги' : 'Новая услуга' ?></h2>
        <input type="hidden" name="id" value="<?= $edit['id'] ?>">
        <input type="hidden" name="old_image" value="<?= e($edit['image']) ?>">
        <label>Название <input type="text" name="title" required value="<?= e($edit['title']) ?>"></label>
        <label>Категория
            <input type="text" name="category" required list="categories" value="<?= e($edit['category']) ?>">
            <datalist id="categories">
                <?php foreach ($categories as $cat): ?><option value="<?= e($cat) ?>"><?php endforeach; ?>
            </datalist>
        </label>
        <label>Описание <textarea name="description" rows="4" required><?= e($edit['description']) ?></textarea></label>
        <div class="form__row">
            <label>Цена, ₽ <input type="number" name="price" min="0" step="1" required value="<?= e($edit['price']) ?>"></label>
            <label>Единица <input type="text" name="unit" required value="<?= e($edit['unit']) ?>"></label>
        </div>
        <label>Изображение (jpg, png, webp) <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp"></label>
        <button class="btn" type="submit">Сохранить</button>
        <?php if ($edit['id']): ?><a class="btn btn--outline" href="services.php">Отмена</a><?php endif; ?>
    </form>

    <table class="table">
        <tr><th></th><th>Название</th><th>Категория</th><th>Цена</th><th></th></tr>
        <?php foreach ($services as $service): ?>
            <tr>
                <td><img class="table__thumb" src="<?= image_url($service['image']) ?>" alt=""></td>
                <td><?= e($service['title']) ?></td>
                <td><?= e($service['category']) ?></td>
                <td><?= money($service['price']) ?> / <?= e($service['unit']) ?></td>
                <td class="table__actions">
                    <a href="?edit=<?= $service['id'] ?>">Изменить</a>
                    <form method="post" data-confirm="Удалить услугу и все её заказы?">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $service['id'] ?>">
                        <button class="link-button link-button--danger">Удалить</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>

<?php require '../includes/footer.php'; ?>
