<article class="card">
    <a class="card__media" href="<?= BASE_URL ?>order.php?id=<?= $service['id'] ?>">
        <img src="<?= image_url($service['image']) ?>" alt="<?= e($service['title']) ?>">
    </a>
    <div class="card__body">
        <div class="card__meta">
            <span><?= e($service['category']) ?></span>
            <span>от <?= money($service['price']) ?> / <?= e($service['unit']) ?></span>
        </div>
        <h3><?= e($service['title']) ?></h3>
        <p><?= e($service['description']) ?></p>
        <a class="link-arrow" href="<?= BASE_URL ?>order.php?id=<?= $service['id'] ?>">Заказать</a>
    </div>
</article>
