// Permet l'affichage immédiat de la photo sélectionnée sur la page profil admin

document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('img');
    input && input.addEventListener('change', function () {
        const label = document.querySelector('label[for="img"]');
        let avatar = label ? label.querySelector('img') : null;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (ev) {
                // Si pas d'img, créer et remplacer l'icône
                if (!avatar) {
                    avatar = document.createElement('img');
                    avatar.className = 'w-16 h-16 rounded-full object-cover';
                    const iconDiv = label.querySelector('div');
                    if (iconDiv) {
                        label.replaceChild(avatar, iconDiv);
                    } else {
                        label.insertBefore(avatar, label.firstChild);
                    }
                }
                avatar.src = ev.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    });
});
