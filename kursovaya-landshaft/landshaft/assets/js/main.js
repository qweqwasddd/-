// Интерактивные элементы сайта. Каждый блок срабатывает только на тех страницах,
// где есть нужные элементы.

// Мобильное меню: кнопка ☰ показывает и скрывает навигацию
const burger = document.querySelector('.burger');
const nav = document.querySelector('.nav');
burger.addEventListener('click', () => nav.classList.toggle('open'));

// Всплывающее сообщение исчезает через 4 секунды
const flash = document.querySelector('.flash');
if (flash) {
    setTimeout(() => flash.classList.add('hidden'), 4000);
    setTimeout(() => flash.remove(), 4500);
}

// Подтверждение перед удалением или отменой (формы с атрибутом data-confirm)
document.querySelectorAll('form[data-confirm]').forEach(form => {
    form.addEventListener('submit', event => {
        if (!confirm(form.dataset.confirm)) {
            event.preventDefault();
        }
    });
});

// Выпадающий список статуса в админке отправляет форму сразу после выбора
document.querySelectorAll('select[data-autosubmit]').forEach(select => {
    select.addEventListener('change', () => select.form.submit());
});

// Калькулятор на странице заказа: стоимость = количество × цена за единицу
const quantity = document.getElementById('quantity');
if (quantity) {
    const total = document.getElementById('total');
    const price = Number(quantity.dataset.price);
    quantity.addEventListener('input', () => {
        const sum = Math.max(1, Number(quantity.value) || 1) * price;
        total.textContent = sum.toLocaleString('ru-RU') + ' ₽';
    });
}

// Окно просмотра проекта в портфолио
const lightbox = document.getElementById('lightbox');
if (lightbox) {
    document.querySelectorAll('[data-lightbox]').forEach(project => {
        project.addEventListener('click', () => {
            lightbox.querySelector('img').src = project.querySelector('img').src;
            lightbox.querySelector('h3').textContent = project.dataset.title;
            lightbox.querySelector('p').textContent = project.dataset.text;
            lightbox.classList.add('open');
        });
    });
    // Закрытие по крестику или клику по затемнённому фону
    lightbox.addEventListener('click', event => {
        if (event.target === lightbox || event.target.classList.contains('lightbox__close')) {
            lightbox.classList.remove('open');
        }
    });
}
