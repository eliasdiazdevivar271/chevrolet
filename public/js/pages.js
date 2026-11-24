document.addEventListener('DOMContentLoaded', () => {
    const email = document.body.dataset.contactEmail?.trim() ?? '';
    const details = document.querySelector('#contact-details');
    const emailLink = document.querySelector('#contact-email-link');

    if (email && details && emailLink) {
        emailLink.textContent = email;
        emailLink.href = `mailto:${email}`;
        details.hidden = false;
    }

    document.querySelector('#current-year').textContent = new Date().getFullYear();

    document.querySelectorAll('[data-vehicle]').forEach((link) => {
        link.addEventListener('click', () => {
            const subject = document.querySelector('#subject');
            if (subject && !subject.value.trim()) {
                subject.value = `Consulta por ${link.dataset.vehicle}`.slice(0, 150);
            }
        });
    });

    const form = document.querySelector('#contact-form');
    const status = document.querySelector('#contact-status');
    form?.addEventListener('submit', (event) => {
        event.preventDefault();
        if (!form.reportValidity()) return;

        status.hidden = false;
        if (!email) {
            status.className = 'form-error';
            status.textContent = 'El envío por correo todavía no está configurado. Consulte los datos de contacto de la concesionaria.';
            return;
        }

        const data = new FormData(form);
        const subject = String(data.get('subject') || 'Consulta Chevrolet').slice(0, 150);
        const body = [
            'Nombre: ' + String(data.get('name') || ''),
            'Teléfono: ' + String(data.get('phone') || 'No indicado'),
            'Correo: ' + String(data.get('email') || ''),
            '',
            'Mensaje:',
            String(data.get('message') || ''),
        ].join('\n');

        status.className = 'form-success';
        status.textContent = 'Se abrirá su aplicación de correo con la consulta preparada. Revise los datos y envíela desde allí.';
        window.location.href = `mailto:${encodeURIComponent(email)}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
    });
});
