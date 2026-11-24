document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.querySelector('.menu-toggle');
    const nav = document.querySelector('#main-nav');

    if (toggle && nav) {
        toggle.addEventListener('click', () => {
            const expanded = toggle.getAttribute('aria-expanded') === 'true';
            toggle.setAttribute('aria-expanded', String(!expanded));
            toggle.textContent = expanded ? 'Menú' : 'Cerrar';
            nav.classList.toggle('is-open', !expanded);
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                toggle.setAttribute('aria-expanded', 'false');
                toggle.textContent = 'Menú';
                nav.classList.remove('is-open');
            }
        });
    }

    const filters = document.querySelectorAll('[data-filter]');
    const vehicles = document.querySelectorAll('#vehicle-list [data-category]');
    filters.forEach((button) => {
        button.addEventListener('click', () => {
            filters.forEach((filter) => {
                filter.classList.remove('is-active');
                filter.setAttribute('aria-pressed', 'false');
            });
            button.classList.add('is-active');
            button.setAttribute('aria-pressed', 'true');
            vehicles.forEach((vehicle) => {
                vehicle.hidden = button.dataset.filter !== 'all' && vehicle.dataset.category !== button.dataset.filter;
            });
        });
    });
});
