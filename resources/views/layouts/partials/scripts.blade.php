<script>
document.addEventListener('DOMContentLoaded', function () {
    // ------------------- SIDEBAR TOGGLE -------------------
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarTexts = document.querySelectorAll('.sidebar-text');
    const sidebarTitle = document.getElementById('sidebarTitle');
    const menuLabel = document.getElementById('menuLabel');
    const supportLabel = document.getElementById('supportLabel');

    let sidebarCollapsed = false;

    sidebarToggle.addEventListener('click', function () {
        sidebarCollapsed = !sidebarCollapsed;

        const header = document.querySelector('header');
        const mainContent = document.querySelector('.ml-64, .ml-16');

        if (sidebarCollapsed) {
            sidebar.classList.replace('w-64', 'w-16');
            header.classList.replace('left-64', 'left-16');
            if (mainContent) mainContent.classList.replace('ml-64', 'ml-16');

            sidebarTexts.forEach(text => {
                text.style.opacity = '0';
                setTimeout(() => { text.style.display = 'none'; }, 150);
            });

            [sidebarTitle, menuLabel, supportLabel].forEach(el => {
                el.style.opacity = '0';
                setTimeout(() => { el.style.display = 'none'; }, 150);
            });
        } else {
            sidebar.classList.replace('w-16', 'w-64');
            header.classList.replace('left-16', 'left-64');
            if (mainContent) mainContent.classList.replace('ml-16', 'ml-64');

            [sidebarTitle, menuLabel, supportLabel].forEach(el => {
                el.style.display = 'block';
            });

            sidebarTexts.forEach(text => {
                text.style.display = 'block';
            });

            setTimeout(() => {
                [sidebarTitle, menuLabel, supportLabel].forEach(el => {
                    el.style.opacity = '1';
                });
                sidebarTexts.forEach(text => {
                    text.style.opacity = '1';
                });
            }, 50);
        }

        localStorage.setItem('sidebarCollapsed', sidebarCollapsed);
    });

    // Load saved sidebar state
    if (localStorage.getItem('sidebarCollapsed') === 'true') {
        sidebarToggle.click();
    }

    // ------------------- DARK MODE TOGGLE -------------------
    const darkModeToggle = document.getElementById('darkModeToggle');
    const darkModeIcon = document.getElementById('darkModeIcon');
    const html = document.documentElement;

    const isDarkMode = localStorage.getItem('darkMode') === 'true';
    if (isDarkMode) {
        html.classList.add('dark');
        darkModeIcon.className = 'fas fa-sun w-5 h-5 text-gray-600 dark:text-gray-300';
    }

    darkModeToggle.addEventListener('click', function () {
        html.classList.toggle('dark');
        const isDark = html.classList.contains('dark');
        darkModeIcon.className = isDark
            ? 'fas fa-sun w-5 h-5 text-gray-600 dark:text-gray-300'
            : 'fas fa-moon w-5 h-5 text-gray-600 dark:text-gray-300';
        localStorage.setItem('darkMode', isDark);
    });

    // ------------------- USER DROPDOWN -------------------
    const userMenuButton = document.getElementById('userMenuButton');
    const userDropdown = document.getElementById('userDropdown');

    if (userMenuButton && userDropdown) {
        userMenuButton.addEventListener('click', function (e) {
            e.stopPropagation();
            userDropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', function (e) {
            if (!userMenuButton.contains(e.target) && !userDropdown.contains(e.target)) {
                userDropdown.classList.add('hidden');
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                userDropdown.classList.add('hidden');
            }
        });
    }

    // ------------------- NOTIFICATION DROPDOWN -------------------
    const notifToggle = document.getElementById('notifToggle');
    const notifDropdown = document.getElementById('notifDropdown');

    if (notifToggle && notifDropdown) {
        notifToggle.addEventListener('click', function (e) {
            e.preventDefault();
            notifDropdown.classList.toggle('hidden');
        });
    }

    // ------------------- NOTIFICATION LU/NON-LU -------------------
    function getTicketsLus() {
        return JSON.parse(localStorage.getItem('ticketsLus') || '[]');
    }

    function markAsRead(ticketId) {
        let lus = getTicketsLus();
        if (!lus.includes(ticketId)) {
            lus.push(ticketId);
            localStorage.setItem('ticketsLus', JSON.stringify(lus));
        }
    }

    function appliquerStyle() {
        const notifItems = document.querySelectorAll('.notif-item');
        const lus = getTicketsLus();

        notifItems.forEach(item => {
            const ticketId = parseInt(item.getAttribute('data-ticket-id'));
            const title = item.querySelector('.notif-title');

            if (lus.includes(ticketId)) {
                item.classList.remove('bg-blue-100', 'dark:bg-blue-700');
                item.classList.add('bg-gray-100', 'dark:bg-gray-700');
                if (title) {
                    title.classList.remove('font-semibold', 'text-red-600', 'dark:text-red-400');
                    title.classList.add('text-gray-600', 'dark:text-gray-400');
                }
            } else {
                item.classList.remove('bg-gray-100', 'dark:bg-gray-700');
                item.classList.add('bg-blue-100', 'dark:bg-blue-700');
                if (title) {
                    title.classList.add('font-semibold', 'text-red-600', 'dark:text-red-400');
                    title.classList.remove('text-gray-600', 'dark:text-gray-400');
                }
            }
        });
    }

    appliquerStyle();

    document.querySelectorAll('.notif-item').forEach(item => {
        item.addEventListener('click', () => {
            const ticketId = parseInt(item.getAttribute('data-ticket-id'));
            markAsRead(ticketId);
            appliquerStyle();
        });
    });
});
</script>
