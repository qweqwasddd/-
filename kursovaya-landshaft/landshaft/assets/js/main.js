const burger = document.querySelector('.burger');
const nav = document.querySelector('.nav');
burger.addEventListener('click', () => nav.classList.toggle('open'));

const flash = document.querySelector('.flash');
if (flash) {
    setTimeout(() => flash.classList.add('hidden'), 4000);
    setTimeout(() => flash.remove(), 4500);
}

document.querySelectorAll('form[data-confirm]').forEach(form => {
    form.addEventListener('submit', event => {
        if (!confirm(form.dataset.confirm)) {
            event.preventDefault();
        }
    });
});

document.querySelectorAll('select[data-autosubmit]').forEach(select => {
    select.addEventListener('change', () => select.form.submit());
});

const quantity = document.getElementById('quantity');
if (quantity) {
    const total = document.getElementById('total');
    const price = Number(quantity.dataset.price);
    quantity.addEventListener('input', () => {
        const sum = Math.max(1, Number(quantity.value) || 1) * price;
        total.textContent = sum.toLocaleString('ru-RU') + ' ₽';
    });
}

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
    lightbox.addEventListener('click', event => {
        if (event.target === lightbox || event.target.classList.contains('lightbox__close')) {
            lightbox.classList.remove('open');
        }
    });
}
