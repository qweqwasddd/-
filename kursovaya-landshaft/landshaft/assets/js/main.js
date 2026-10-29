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

const header = document.querySelector('.header');
window.addEventListener('scroll', () => header.classList.toggle('header--scrolled', window.scrollY > 10));

if (window.matchMedia('(prefers-reduced-motion: no-preference)').matches && 'IntersectionObserver' in window) {
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15 });

    document.querySelectorAll('.section h2, .feature, .card, .project, .steps li, .cta, .contacts > div')
        .forEach(element => {
            element.classList.add('reveal');
            observer.observe(element);
        });
}

const indicator = document.querySelector('.nav__indicator');
const wide = window.matchMedia('(min-width: 901px)');

function moveIndicator(link) {
    indicator.style.top = (link.offsetTop + link.offsetHeight / 2 - 2) + 'px';
    indicator.classList.add('visible');
}

if (indicator) {
    const active = document.querySelector('.nav__menu a.active');
    if (active) moveIndicator(active);
    requestAnimationFrame(() => requestAnimationFrame(() => indicator.classList.add('animated')));

    document.querySelectorAll('.nav__menu a').forEach(link => {
        link.addEventListener('click', event => {
            if (!wide.matches || link.classList.contains('active') || event.ctrlKey || event.metaKey) return;
            event.preventDefault();
            document.querySelectorAll('.nav__menu a').forEach(item => item.classList.toggle('active', item === link));
            moveIndicator(link);
            const calmMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            setTimeout(() => { window.location.href = link.href; }, calmMotion ? 0 : 420);
        });
    });
}
