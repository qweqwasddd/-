<?php // Карточка проекта портфолио. Ожидает переменную $project (строка из таблицы projects). ?>
<figure class="project" data-lightbox
        data-title="<?= e($project['title']) ?>"
        data-text="<?= e($project['location'] . ' · ' . $project['area'] . ' м² · ' . $project['year'] . ' г. — ' . $project['description']) ?>">
    <img src="<?= image_url($project['image']) ?>" alt="<?= e($project['title']) ?>">
    <figcaption>
        <strong><?= e($project['title']) ?></strong>
        <span><?= e($project['location']) ?>, <?= $project['area'] ?> м²</span>
    </figcaption>
</figure>
