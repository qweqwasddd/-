<figure class="project" data-lightbox
        data-title="<?= e($project['title']) ?>"
        data-text="<?= e($project['location'] . ' · ' . $project['area'] . ' м² · ' . $project['year'] . ' г. — ' . $project['description']) ?>">
    <div class="project__media">
        <img src="<?= image_url($project['image']) ?>" alt="<?= e($project['title']) ?>">
    </div>
    <figcaption>
        <strong><?= e($project['title']) ?></strong>
        <span><?= e($project['location']) ?> · <?= $project['area'] ?> м² · <?= $project['year'] ?></span>
    </figcaption>
</figure>
