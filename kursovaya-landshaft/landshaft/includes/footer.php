</main>

<footer class="footer">
    <div class="container">
        <div class="footer__top">
            <p class="footer__slogan">Проектируем и создаём сады в Красноярске и пригороде с 2015 года.</p>
            <a class="btn btn--accent" href="<?= BASE_URL ?>contacts.php">Обсудить проект</a>
        </div>
        <div class="footer__grid">
            <div>
                <span class="eyebrow">Разделы</span>
                <a href="<?= BASE_URL ?>services.php">Услуги</a>
                <a href="<?= BASE_URL ?>portfolio.php">Портфолио</a>
                <a href="<?= BASE_URL ?>contacts.php">Контакты</a>
            </div>
            <div>
                <span class="eyebrow">Адрес</span>
                <p>г. Красноярск,<br>ул. Садовая, 12</p>
            </div>
            <div>
                <span class="eyebrow">Связь</span>
                <p>+7 (391) 200-10-10<br>info@zdvor.ru</p>
            </div>
        </div>
        <div class="footer__brand"><?= SITE_NAME ?></div>
        <div class="footer__copy">
            <span>© <?= date('Y') ?> <?= SITE_NAME ?></span>
            <span>Студия ландшафтного дизайна</span>
        </div>
    </div>
</footer>

<script src="<?= BASE_URL ?>assets/js/main.js?v=<?= filemtime(__DIR__ . '/../assets/js/main.js') ?>"></script>
</body>
</html>
