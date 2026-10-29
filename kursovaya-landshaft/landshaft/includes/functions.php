<?php
require_once __DIR__ . '/config.php';
session_start();

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

function e($text): string
{
    return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
}

function money($value): string
{
    return number_format((float)$value, 0, ',', ' ') . ' ₽';
}

function redirect(string $page): void
{
    header('Location: ' . BASE_URL . $page);
    exit;
}

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

function image_url(string $image): string
{
    if ($image === '') {
        return BASE_URL . 'assets/img/hero.svg';
    }
    return BASE_URL . (strpos($image, 'uploads/') === 0 ? $image : 'assets/img/' . $image);
}

const ORDER_STATUSES = [
    'new'      => 'Новый',
    'in_work'  => 'В работе',
    'done'     => 'Выполнен',
    'canceled' => 'Отменён',
];

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . csrf_token() . '">';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !hash_equals(csrf_token(), $_POST['csrf'] ?? '')) {
    http_response_code(403);
    exit('Форма устарела. Вернитесь назад, обновите страницу и отправьте её ещё раз.');
}
