-- База данных сайта студии ландшафтного дизайна «Зелёный двор»
-- Импорт: phpMyAdmin → вкладка «Импорт» → выбрать этот файл → «Вперёд»

DROP DATABASE IF EXISTS landshaft;
CREATE DATABASE landshaft CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE landshaft;

-- Пользователи сайта (клиенты и администраторы)
CREATE TABLE users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    email      VARCHAR(100) NOT NULL UNIQUE,
    phone      VARCHAR(20)  NOT NULL DEFAULT '',
    password   VARCHAR(255) NOT NULL,
    role       ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Услуги студии
CREATE TABLE services (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(150) NOT NULL,
    category    VARCHAR(50)  NOT NULL,
    description TEXT NOT NULL,
    price       DECIMAL(10, 2) NOT NULL,
    unit        VARCHAR(20)  NOT NULL DEFAULT 'м²',
    image       VARCHAR(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB;

-- Заказы клиентов на услуги
CREATE TABLE orders (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT NOT NULL,
    service_id INT NOT NULL,
    quantity   INT NOT NULL,
    total      DECIMAL(12, 2) NOT NULL,
    comment    TEXT,
    status     ENUM('new', 'in_work', 'done', 'canceled') NOT NULL DEFAULT 'new',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)    REFERENCES users(id)    ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Портфолио: выполненные проекты
CREATE TABLE projects (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(150) NOT NULL,
    location    VARCHAR(100) NOT NULL,
    area        INT NOT NULL,
    year        INT NOT NULL,
    description TEXT NOT NULL,
    image       VARCHAR(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB;

-- Обращения с формы «Контакты» (доступна без регистрации)
CREATE TABLE messages (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    phone      VARCHAR(20)  NOT NULL,
    text       TEXT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Тестовые учётные записи (пароль у обеих: 123456)
INSERT INTO users (name, email, phone, password, role) VALUES
('Администратор', 'admin@zdvor.ru', '+7 (391) 200-10-10', '$2y$10$Yj/v8/6hnJrO.CBfyei0XOKT1IyWGfGLUJqCElGjqKPN.71yvoemK', 'admin'),
('Иван Петров',   'user@zdvor.ru',  '+7 (913) 555-12-34', '$2y$10$Yj/v8/6hnJrO.CBfyei0XOKT1IyWGfGLUJqCElGjqKPN.71yvoemK', 'user');

INSERT INTO services (title, category, description, price, unit, image) VALUES
('Ландшафтный проект участка', 'Проектирование', 'Обмер участка, анализ почвы и рельефа, генеральный план, дендроплан и разбивочный чертёж. Итог — полный комплект документации для реализации сада.', 150, 'м²', 'srv-project.svg'),
('3D-визуализация сада', 'Проектирование', 'Объёмная модель будущего сада с видами с разных точек и в разные сезоны. Помогает увидеть результат до начала работ.', 9000, 'объект', 'srv-3d.svg'),
('Устройство газона', 'Озеленение', 'Подготовка основания, планировка, посев газонной травосмеси или укладка рулонного газона с первичным уходом.', 450, 'м²', 'srv-lawn.svg'),
('Посадка деревьев и кустарников', 'Озеленение', 'Подбор посадочного материала под климат Сибири, посадка с заменой грунта, мульчирование и гарантия приживаемости.', 1800, 'шт.', 'srv-plants.svg'),
('Садовые дорожки', 'Благоустройство', 'Дорожки из брусчатки, натурального камня или гравия с бордюром, дренажом и уплотнённым основанием.', 2400, 'м²', 'srv-paths.svg'),
('Декоративный водоём', 'Благоустройство', 'Пруд или ручей с плёночной гидроизоляцией, береговым оформлением камнем и водными растениями.', 65000, 'объект', 'srv-pond.svg'),
('Ландшафтное освещение', 'Благоустройство', 'Подсветка деревьев, дорожек и фасада. Проект, прокладка кабеля, установка светильников.', 3500, 'точка', 'srv-light.svg'),
('Сезонный уход за садом', 'Уход', 'Стрижка газона и живой изгороди, обрезка, подкормка, обработка от вредителей. Выезд садовника по графику.', 2500, 'выезд', 'srv-care.svg');

INSERT INTO projects (title, location, area, year, description, image) VALUES
('Сад в английском стиле', 'Красноярск, Удачный', 1200, 2025, 'Извилистые дорожки, миксбордеры из многолетников и уютная зона отдыха под перголой.', 'prj-1.svg'),
('Двор с прудом', 'Емельяново', 900, 2025, 'Декоративный водоём с каменным берегом стал центром композиции участка.', 'prj-2.svg'),
('Зона отдыха с перголой', 'Дивногорск', 600, 2024, 'Деревянная пергола, увитая девичьим виноградом, и площадка для барбекю.', 'prj-3.svg'),
('Газон и живая изгородь', 'Красноярск, Солнечный', 1500, 2024, 'Ровный рулонный газон, туевая изгородь по периметру и автоматический полив.', 'prj-4.svg'),
('Террасированный склон', 'Овсянка', 2000, 2023, 'Склон укреплён подпорными стенками, на террасах — альпинарий и ягодник.', 'prj-5.svg'),
('Тропа в природном стиле', 'Берёзовка', 800, 2023, 'Дорожка из спилов и гравия ведёт через посадки хвойных к беседке.', 'prj-6.svg');

INSERT INTO orders (user_id, service_id, quantity, total, comment, status) VALUES
(2, 1, 600, 90000, 'Участок с уклоном, хочется зону барбекю', 'in_work'),
(2, 3, 300, 135000, NULL, 'new');

INSERT INTO messages (name, phone, text) VALUES
('Ольга', '+7 (902) 111-22-33', 'Здравствуйте! Сколько стоит выезд специалиста на участок в Емельяново?');
