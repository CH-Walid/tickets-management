// JS for dropdown admin (avatar)
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('admin-dropdown-btn');
    const menu = document.getElementById('admin-dropdown-menu');
    if (!btn || !menu) return;
    let open = false;
    function showMenu() {
        menu.classList.remove('hidden');
        open = true;
    }
    function hideMenu() {
        menu.classList.add('hidden');
        open = false;
    }
    btn.addEventListener('click', function (e) {
        e.stopPropagation();
        if (open) {
            hideMenu();
        } else {
            showMenu();
        }
    });
    document.addEventListener('click', function (e) {
        if (open && !menu.contains(e.target) && !btn.contains(e.target)) {
            hideMenu();
        }
    });
    // Accessibilité clavier
    btn.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') hideMenu();
    });
});
