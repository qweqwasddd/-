<?php // Карточка услуги. Ожидает переменную $service (строка из таблицы services). ?>
<article class="card">
    <img class="card__img" src="<?= image_url($service['image']) ?>" alt="<?= e($service['title']) ?>">
    <div class="card__body">
        <span class="tag"><?= e($service['category']) ?></span>
        <h3><?= e($service['title']) ?></h3>
        <p><?= e($service['description']) ?></p>
        <div class="card__footer">
            <span class="price">от <?= money($service['price']) ?> / <?= e($service['unit']) ?></span>
            <a class="btn btn--small" href="<?= BASE_URL ?>order.php?id=<?= $service['id'] ?>">Заказать</a>
        </div>
    </div>
</article>
