<?php
// Общие функции: подключение к БД, авторизация, сообщения, загрузка файлов.
// Подключается в начале каждой страницы.
require_once __DIR__ . '/config.php';
session_start();

// Единственное подключение к БД на весь запрос
function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }
    return $pdo;
}

// Экранирование текста перед выводом в HTML (защита от XSS)
function e($text): string
{
    return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
}

// Цена в формате «12 500 ₽»
function money($value): string
{
    return number_format((float)$value, 0, ',', ' ') . ' ₽';
}

function redirect(string $page): void
{
    header('Location: ' . BASE_URL . $page);
    exit;
}

// Текущий пользователь из сессии или null для гостя
function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function is_admin(): bool
{
    return (current_user()['role'] ?? '') === 'admin';
}

function require_login(): void
{
    if (!current_user()) {
        flash('Войдите в аккаунт, чтобы продолжить', 'error');
        redirect('login.php');
    }
}

function require_admin(): void
{
    if (!is_admin()) {
        redirect('index.php');
    }
}

// Одноразовое сообщение: записывается до перенаправления, показывается после
function flash(string $text, string $type = 'success'): void
{
    $_SESSION['flash'] = ['text' => $text, 'type' => $type];
}

function take_flash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

// Сохраняет загруженную картинку в папку uploads и возвращает путь к ней.
// Если файл не выбран или формат не подходит — возвращает $old (прежнее изображение).
function upload_image(string $field, string $old): string
{
    $file = $_FILES[$field] ?? null;
    if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
        return $old;
    }
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
        return $old;
    }
    $name = 'uploads/' . uniqid() . '.' . $ext;
    move_uploaded_file($file['tmp_name'], __DIR__ . '/../' . $name);
    return $name;
}

// Путь к картинке для атрибута src: стандартные лежат в assets/img, загруженные — в uploads
function image_url(string $image): string
{
    if ($image === '') {
        return BASE_URL . 'assets/img/hero.svg';
    }
    return BASE_URL . (strpos($image, 'uploads/') === 0 ? $image : 'assets/img/' . $image);
}

// Подписи статусов заказа (цвет задаётся в CSS классом status-<ключ>)
const ORDER_STATUSES = [
    'new'      => 'Новый',
    'in_work'  => 'В работе',
    'done'     => 'Выполнен',
    'canceled' => 'Отменён',
];
