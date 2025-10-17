document.addEventListener('DOMContentLoaded', () => {
    // Potvrda pre brisanja
    document.querySelectorAll('form[data-confirm]').forEach(form => {
        form.addEventListener('submit', e => {
            if (!confirm(form.getAttribute('data-confirm'))) {
                e.preventDefault();
            }
        });
    });

    // Fokus na prvo polje forme
    const firstInput = document.querySelector('form input, form textarea');
    if (firstInput) firstInput.focus();
});
