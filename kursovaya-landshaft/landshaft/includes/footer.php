</main>

<footer class="footer">
    <div class="container footer__inner">
        <div>
            <div class="footer__logo"><?= SITE_NAME ?></div>
            <p>Студия ландшафтного дизайна.<br>Проектируем и создаём сады с 2015 года.</p>
        </div>
        <div>
            <h4>Разделы</h4>
            <a href="<?= BASE_URL ?>services.php">Услуги</a>
            <a href="<?= BASE_URL ?>portfolio.php">Портфолио</a>
            <a href="<?= BASE_URL ?>contacts.php">Контакты</a>
        </div>
        <div>
            <h4>Контакты</h4>
            <p>г. Красноярск, ул. Садовая, 12<br>+7 (391) 200-10-10<br>info@zdvor.ru</p>
        </div>
    </div>
    <div class="container footer__copy">© <?= date('Y') ?> <?= SITE_NAME ?></div>
</footer>

<script src="<?= BASE_URL ?>assets/js/main.js?v=<?= filemtime(__DIR__ . '/../assets/js/main.js') ?>"></script>
</body>
</html>
