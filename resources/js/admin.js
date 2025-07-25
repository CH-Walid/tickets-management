// Admin dashboard JS
document.addEventListener('DOMContentLoaded', () => {
    if (window.lucide) {
        lucide.createIcons();
    }
    document.querySelectorAll('.nav-item').forEach(item => {
        item.addEventListener('click', function() {
            document.querySelectorAll('.nav-item').forEach(nav => {
                nav.classList.remove('bg-white/20', 'text-white');
                nav.classList.add('text-white/70');
            });
            this.classList.add('bg-white/20', 'text-white');
            this.classList.remove('text-white/70');
        });
    });
});
