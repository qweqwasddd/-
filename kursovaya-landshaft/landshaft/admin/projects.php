<?php
require_once '../includes/functions.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];

    if ($_POST['action'] === 'delete') {
        db()->prepare('DELETE FROM projects WHERE id = ?')->execute([$id]);
        flash('Проект удалён');
    } else {
        $data = [
            trim($_POST['title']),
            trim($_POST['location']),
            (int)$_POST['area'],
            (int)$_POST['year'],
            trim($_POST['description']),
            upload_image('image', $_POST['old_image']),
        ];
        if ($id) {
            $data[] = $id;
            db()->prepare('UPDATE projects SET title = ?, location = ?, area = ?, year = ?, description = ?, image = ? WHERE id = ?')->execute($data);
            flash('Проект обновлён');
        } else {
            db()->prepare('INSERT INTO projects (title, location, area, year, description, image) VALUES (?, ?, ?, ?, ?, ?)')->execute($data);
            flash('Проект добавлен');
        }
    }
    redirect('admin/projects.php');
}

// ?edit=ID — редактирование проекта, без параметра — добавление нового
$edit = ['id' => 0, 'title' => '', 'location' => '', 'area' => '', 'year' => date('Y'), 'description' => '', 'image' => ''];
if (isset($_GET['edit'])) {
    $stmt = db()->prepare('SELECT * FROM projects WHERE id = ?');
    $stmt->execute([(int)$_GET['edit']]);
    $edit = $stmt->fetch() ?: $edit;
}

$title    = 'Портфолио';
$projects = db()->query('SELECT * FROM projects ORDER BY year DESC, id DESC')->fetchAll();

require '../includes/header.php';
require 'nav.php';
?>

<section class="section container admin-layout">
    <form class="form" method="post" enctype="multipart/form-data">
        <h2><?= $edit['id'] ? 'Редактирование проекта' : 'Новый проект' ?></h2>
        <input type="hidden" name="id" value="<?= $edit['id'] ?>">
        <input type="hidden" name="old_image" value="<?= e($edit['image']) ?>">
        <label>Название <input type="text" name="title" required value="<?= e($edit['title']) ?>"></label>
        <label>Местоположение <input type="text" name="location" required value="<?= e($edit['location']) ?>"></label>
        <div class="form__row">
            <label>Площадь, м² <input type="number" name="area" min="1" required value="<?= e($edit['area']) ?>"></label>
            <label>Год <input type="number" name="year" min="2000" max="2100" required value="<?= e($edit['year']) ?>"></label>
        </div>
        <label>Описание <textarea name="description" rows="4" required><?= e($edit['description']) ?></textarea></label>
        <label>Изображение (jpg, png, webp) <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp"></label>
        <button class="btn" type="submit">Сохранить</button>
        <?php if ($edit['id']): ?><a class="btn btn--outline" href="projects.php">Отмена</a><?php endif; ?>
    </form>

    <table class="table">
        <tr><th></th><th>Название</th><th>Место</th><th>Площадь</th><th>Год</th><th></th></tr>
        <?php foreach ($projects as $project): ?>
            <tr>
                <td><img class="table__thumb" src="<?= image_url($project['image']) ?>" alt=""></td>
                <td><?= e($project['title']) ?></td>
                <td><?= e($project['location']) ?></td>
                <td><?= $project['area'] ?> м²</td>
                <td><?= $project['year'] ?></td>
                <td class="table__actions">
                    <a href="?edit=<?= $project['id'] ?>">Изменить</a>
                    <form method="post" data-confirm="Удалить проект из портфолио?">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $project['id'] ?>">
                        <button class="link-button link-button--danger">Удалить</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>

<?php require '../includes/footer.php'; ?>
