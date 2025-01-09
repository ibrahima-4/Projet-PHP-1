document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form-utilisateur');
    const tableUtilisateurs = document.getElementById('table-utilisateurs');
    const notification = document.getElementById('notification');

    // Charger les utilisateurs au démarrage
    chargerUtilisateurs();

    // Gérer l'envoi du formulaire
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(form);

        const response = await fetch('../actions/utilisateurs_actions.php', {
            method: 'POST',
            body: formData,
        });

        const result = await response.json();
        afficherNotification(result.message, result.type);

        if (result.type === 'success') {
            form.reset();
            chargerUtilisateurs();
        }
    });

    // Charger les utilisateurs
    async function chargerUtilisateurs() {
        const response = await fetch('../actions/utilisateurs_actions.php?action=lister');
        const utilisateurs = await response.json();

        tableUtilisateurs.innerHTML = utilisateurs
            .map(
                (u) => `
                <tr>
                    <td>${u.id}</td>
                    <td>${u.nom}</td>
                    <td>${u.email}</td>
                    <td>
                        <button class="btn btn-danger btn-sm" onclick="supprimerUtilisateur(${u.id})">Supprimer</button>
                    </td>
                </tr>`
            )
            .join('');
    }

    // Supprimer un utilisateur
    window.supprimerUtilisateur = async (id) => {
        const formData = new FormData();
        formData.append('action', 'supprimer');
        formData.append('id', id);

        const response = await fetch('../actions/utilisateurs_actions.php', {
            method: 'POST',
            body: formData,
        });

        const result = await response.json();
        afficherNotification(result.message, result.type);

        if (result.type === 'success') {
            chargerUtilisateurs();
        }
    };

    // Afficher les notifications
    function afficherNotification(message, type) {
        notification.textContent = message;
        notification.className = `alert alert-${type}`;
        notification.classList.remove('d-none');
        setTimeout(() => notification.classList.add('d-none'), 3000);
    }
});
