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
        function updateEntrepriseInfo(clientId) {
            fetch(`/entreprise-info/${clientId}`)
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
        let link = document.getElementById("edit_client")
        let selectedClient = client.value
        updateClientInfo(selectedClient)
        client.addEventListener('change', () => {
            selectedClient = client.value
            updateClientInfo(selectedClient)
        })
        link.addEventListener('click', (event)=>{
            event.preventDefault()
            window.location.href = `/client/${selectedClient}/edit`
        })

        let entreprise = document.getElementById('facture_entreprise')
        let selectedEntreprise = entreprise.value
        updateEntrepriseInfo(selectedEntreprise)
        entreprise.addEventListener('change', () => {
            selectedEntreprise = entreprise.value
            updateClientInfo(selectedEntreprise)
        })
    }
})
