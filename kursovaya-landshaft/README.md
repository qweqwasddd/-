# Курсовая работа: web-сайт студии ландшафтного дизайна «Зелёный двор»

Стек: HTML5, CSS3, JavaScript, PHP (7.4+), MySQL/MariaDB. Запуск через XAMPP, без фреймворков и сторонних библиотек.

## Что в папке

| Путь | Что это |
|---|---|
| `landshaft/` | сам сайт — эту папку копировать в `C:\xampp\htdocs` |
| `landshaft/database.sql` | создаёт базу `landshaft`, все таблицы и тестовые данные |
| `docs/Курсовая_работа.docx` | пояснительная записка (отчёт) с рисунками и листингами кода |
| `docs/Техническое_задание.docx` | техническое задание по ГОСТ 34.602-2020 |

## Запуск в XAMPP

1. Скопируйте папку `landshaft` в `C:\xampp\htdocs\` (должно получиться `C:\xampp\htdocs\landshaft\index.php`).
2. В панели XAMPP нажмите **Start** у **Apache** и **MySQL**.
3. Откройте http://localhost/phpmyadmin → вкладка **Импорт** → выберите `landshaft/database.sql` → **Вперёд**.
4. Откройте http://localhost/landshaft/

Тестовые учётные записи (пароль у обеих `123456`):

- администратор — `admin@zdvor.ru`
- клиент — `user@zdvor.ru`

Если у MySQL задан пароль или папка названа иначе — поправьте `landshaft/includes/config.php`.

## Как устроен код

Каждая страница — отдельный PHP-файл, и все они устроены одинаково:

1. `require_once 'includes/functions.php'` — подключение к БД, сессия, общие функции;
2. обработка отправленной формы (если был POST) → перенаправление с сообщением;
3. выборка данных из БД;
4. `header.php` → HTML-шаблон страницы → `footer.php`.

```
landshaft/
├── index.php  services.php  order.php  portfolio.php  contacts.php   публичные страницы
├── register.php  login.php  logout.php  profile.php                  пользователь
├── includes/   config.php · functions.php · header.php · footer.php · карточки
├── admin/      index (сводка) · orders · services · projects · users · nav
├── assets/     css/style.css · js/main.js · img/*.svg
└── uploads/    картинки, загруженные через админку
```

Таблицы БД: `users`, `services`, `orders` (связь клиент ↔ услуга, каскадное удаление), `projects`, `messages`.

## Активная ссылка (сайт в интернете)

XAMPP работает только на вашем компьютере. Чтобы получить публичную ссылку, нужен бесплатный хостинг с PHP и MySQL (например, InfinityFree или аналог):

1. Создайте на хостинге базу данных MySQL.
2. В phpMyAdmin хостинга импортируйте `database.sql`, **предварительно удалив из него первые строки** `DROP DATABASE`, `CREATE DATABASE` и `USE` (на хостинге база уже создана и называется по-своему).
3. Загрузите содержимое папки `landshaft` в корневую папку сайта (обычно `htdocs` или `public_html`).
4. В `includes/config.php` укажите выданные хостингом `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` и `BASE_URL = '/'`.
