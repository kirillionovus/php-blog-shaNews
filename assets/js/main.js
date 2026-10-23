function toggleMenu(menuId) {
    const menu = document.getElementById(menuId);
    if (!menu) return;

    document.querySelectorAll('[id$="Menu"]').forEach(m => {
        if (m.id !== menuId) m.classList.add('hidden');
    });
    menu.classList.toggle('hidden');
}

window.addEventListener('click', function(e) {
    const menus = document.querySelectorAll('#userMenu, #guestMenu');
    menus.forEach(menu => {
        if (menu && !menu.classList.contains('hidden')) {
            const button = menu.previousElementSibling;
            if (!button.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.add('hidden');
            }
        }
    });
});

window.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('#userMenu, #guestMenu').forEach(menu => {
            menu.classList.add('hidden');
        });
    }
});