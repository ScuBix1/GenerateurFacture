'use strict';
import './bootstrap.js';
import './styles/app.css';
document.addEventListener("DOMContentLoaded", function(){
    if(location.href == "http://localhost:8000/"){
        let clients = document.querySelector('select_client');
        clients.forEach((client) => {
            console.log(client)
        });
    }
})
