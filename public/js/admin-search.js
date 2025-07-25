// JS intelligent search bar for admin
let searchTimeout = null;
const searchInput = document.querySelector('input[data-admin-search]');
const searchResults = document.createElement('div');
searchResults.className = "absolute bg-white border rounded-lg shadow-lg w-full z-50 mt-2 p-2 text-gray-900";
searchResults.style.display = 'none';

if (searchInput) {
    searchInput.parentElement.appendChild(searchResults);
    searchInput.addEventListener('input', function () {
        if (searchTimeout) clearTimeout(searchTimeout);
        if (this.value.length < 1) {
            searchResults.style.display = 'none';
            searchResults.innerHTML = '';
            return;
        }
        searchTimeout = setTimeout(() => {
            fetch(`/admin/search?q=${encodeURIComponent(this.value)}`)
                .then(r => r.json())
                .then(data => {
                    let html = '';
                    if (data.tickets.length > 0) {
                        html += '<div class="font-semibold mb-1 text-blue-700">Tickets</div>';
                        data.tickets.forEach(ticket => {
                            html += `<div class='py-1 px-2 hover:bg-blue-50 rounded'>[#${ticket.id}] ${ticket.titre} <span class='text-xs text-gray-500'>(${ticket.status})</span></div>`;
                        });
                    }
                    if (data.users.length > 0) {
                        html += '<div class="font-semibold mt-2 mb-1 text-blue-700">Agents</div>';
                        data.users.forEach(user => {
                            html += `<div class='py-1 px-2 hover:bg-blue-50 rounded'>${user.nom} ${user.prenom} <span class='text-xs text-gray-500'>(${user.email})</span></div>`;
                        });
                    }
                    if (!html) {
                        html = '<div class="text-gray-500 text-sm">Aucun résultat</div>';
                    }
                    searchResults.innerHTML = html;
                    searchResults.style.display = 'block';
                });
        }, 500);
    });
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });
}
