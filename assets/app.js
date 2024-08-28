import './bootstrap.js'
import './styles/app.css'
document.addEventListener('DOMContentLoaded', (event) => {
    //(rappel pour la suite [mettre adresse dans le .env])
    if (location.href == 'http://localhost:8000/') {
        //fonction pour accéder et afficher les infos du client selectionné en temps réel
        function updateClientInfo(clientId) {
            fetch(`/client-info/${clientId}`)
                .then((response) => response.json())
                .then((data) => {
                    document.getElementById('nomSociete_client').innerText = data.nom
                    document.getElementById('adresse_client').innerText = data.adresse
                    document.getElementById('telephone_client').innerText = data.telephone
                    document.getElementById('email_client').innerText = data.email
                })
                .catch((error) => console.error('Erreur:', error))
        }
        //fonction pour accéder et afficher les infos de son entreprise selectionné en temps réel
        function updateEntrepriseInfo(entrepriseId) {
            fetch(`/entreprise-info/${entrepriseId}`)
                .then((response) => response.json())
                .then((data) => {
                    document.getElementById('nomSociete_entreprise').innerText = data.nom
                    document.getElementById('adresse_entreprise').innerText = data.adresse
                    document.getElementById('telephone_entreprise').innerText = data.telephone
                    document.getElementById('email_entreprise').innerText = data.email
                    document.getElementById('siren_entreprise').innerText = data.siren
                })
                .catch((error) => console.error('Erreur:', error))
        }

        let client = document.getElementById('facture_client')
        let selectedClient = client.value
        let deleteClientForm = document.forms['delete_client']
        let editClient = document.getElementById('edit_client')

        let entreprise = document.getElementById('facture_entreprise')
        let selectedEntreprise = entreprise.value
        let deleteEntrepriseForm = document.forms['delete_entreprise']
        let editEntreprise = document.getElementById('edit_entreprise')

        let printButton = document.getElementById("facture_save")

        
        
        updateClientInfo(selectedClient)
        client.addEventListener('change', () => {
            selectedClient = client.value
            updateClientInfo(selectedClient)
        })
        editClient.addEventListener('click', (event) => {
            event.preventDefault()
            window.location.href = `/client/${selectedClient}/edit`
        })
        deleteClientForm.addEventListener('submit', (event) => {
            event.preventDefault()
            const url = `/client/${selectedClient}/delete`
            fetch(url, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            }).then((response) => {
                if (response.status === 200) {
                    window.location.href = '/'
                }
            }).catch((err)=>console.log(err))
        })

        updateEntrepriseInfo(selectedEntreprise)
        entreprise.addEventListener('change', () => {
            selectedEntreprise = entreprise.value
            updateEntrepriseInfo(selectedEntreprise)
        })
        editEntreprise.addEventListener('click', (event) => {
            event.preventDefault()
            window.location.href = `/entreprise/${selectedEntreprise}/edit`
        })
        deleteEntrepriseForm.addEventListener('submit', (event) => {
            event.preventDefault()
            const url = `/entreprise/${selectedEntreprise}/delete`
            fetch(url, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            }).then((response) => {
                if (response.ok) {
                    window.location.href = '/'
                }
            }).catch((err)=>console.log(err))
        })
        

    }
})
