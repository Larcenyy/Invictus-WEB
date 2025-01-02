/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

const iconEyes = document.querySelectorAll(".eye");
const inputPassword = document.getElementById("password");
const inputUsername = document.getElementById("username");
const isFirst = document.getElementById("isFirst");

const inputPasswordFirstLogin = document.getElementById("password-first-login");
const inputUsernameFirstLogin = document.getElementById("username-first-login");
const inputCodeFirstLogin= document.getElementById("code-first-login");

const btnLogin = document.getElementById("btnLogin");
const btnRegister = document.getElementById("btnRegister");

const btnGoToFirstLogin = document.getElementById("gotoFirstLogin");
const btnGoToLogin = document.getElementById("gotoLogin");

const containerLoginForm = document.getElementById("loginForm");
const containerFirstLoginForm = document.getElementById("firstLogin");

const buttons = document.querySelectorAll(".btn-container-slider button");
const picture = document.querySelector(".picture");
let currentIndex = 0; 
const totalButtons = buttons.length;

let autoChange; // Stocker l'intervalle
let userPauseTimeout; // Stocker le délai de reprise automatique

// Fonction pour changer l'image
function changeImage(index) {
    // Réinitialiser le style des boutons
    buttons.forEach((btn) => {
        btn.style.opacity = "0.5";
        btn.style.width = "60px";
    });

    const button = buttons[index];
    const bgClass = button.classList[0];

    // Animation de l'image
    picture.style.opacity = "0"; 
    setTimeout(() => {
        picture.src = `/build/images/background/${bgClass}.png`; // Changer l'image
        picture.style.opacity = "1"; // Réafficher avec fondu
    }, 100); 

    // Style du bouton actif
    button.style.opacity = "1";
    button.style.width = "80px"; 
}

// Fonction pour démarrer l'automatisme
function startAutoChange() {
    autoChange = setInterval(() => {
        currentIndex = (currentIndex + 1) % totalButtons; // Aller à l'image suivante
        changeImage(currentIndex);
    }, 3000); // Toutes les 3 secondes
}

// Fonction pour arrêter l'automatisme
function stopAutoChange() {
    clearInterval(autoChange);
}

// Initialiser l'automatisme au début
startAutoChange();

// Gestion des clics sur les boutons
buttons.forEach((button, index) => {
    button.addEventListener("click", () => {
        // Arrêter temporairement l'automatisme
        stopAutoChange();

        // Changer l'image immédiatement
        changeImage(index);
        currentIndex = index; // Mettre à jour l'index actif

        // Réinitialiser le délai de reprise automatique
        clearTimeout(userPauseTimeout); // Effacer le précédent timeout
        userPauseTimeout = setTimeout(() => {
            startAutoChange(); // Reprendre après 2-3 secondes d'inactivité
        }, 3000); // Reprendre après 3 secondes
    });
});


// ---------------------------- SYSTEM EYES INPUT
iconEyes.forEach(iconEye => {
    iconEye.addEventListener('click', () => {
        const inputPassword = iconEye.previousElementSibling; // L'input est juste avant l'icône dans le DOM
        
        const currentOpacity = window.getComputedStyle(iconEye).opacity;
        iconEye.style.opacity = currentOpacity === "1" ? "0.2" : "1";

        inputPassword.type = inputPassword.type === "password" ? "text" : "password";
    });
});



// ----- LOGIN FORM VALIDATION ----- //
btnLogin.addEventListener("click", (e) => {
    const username = inputUsername.value.trim();
    const password = inputPassword.value.trim();

    if(username === "") {
        sendClientErrorMessage("Veuillez entrer votre nom d'utilisateur", "error");
        return;
    }

    if(password === "") {
        sendClientErrorMessage("Veuillez entrer votre mot de passe", "error");
        return;
    }
});

btnRegister.addEventListener("click", (e) => {
    e.preventDefault();

    const username = inputUsernameFirstLogin.value.trim();
    const password = inputPasswordFirstLogin.value.trim();
    const code = inputCodeFirstLogin.value.trim();

    if(username === "") {
        sendClientErrorMessage("Veuillez entrer votre nom d'utilisateur", "error");
        return;
    }

    if(password === "") {
        sendClientErrorMessage("Veuillez entrer votre mot de passe", "error");
        return;
    }

    if(code === "") {
        sendClientErrorMessage("Veuillez entrer le code de validation", "error");
        return;
    }

    // Reset les champs
    inputUsername.value = "";
    inputPassword.value = "";
    code.value = "";
});

// ------------ REDIRRECTION VERS LE SYSTEM DE REGISTER ------------------------------- //
btnGoToFirstLogin.addEventListener("click", (e) => {
    e.preventDefault();

    containerLoginForm.classList.add("hidden");
    containerFirstLoginForm.classList.remove("hidden");
});

btnGoToLogin.addEventListener("click", (e) => {
    e.preventDefault();
    containerLoginForm.classList.remove("hidden");
    containerFirstLoginForm.classList.add("hidden");
});

function playerGoodRegistred(){
    containerLoginForm.classList.remove("hidden");
    containerFirstLoginForm.classList.add("hidden");
    isFirst.classList.add("hidden");
}

// Quand il se login mais qu'on trouve déjà un serializer
function playerHaveRegistered(){
    isFirst.classList.add("hidden");
}

function playerNotRegistred(){
    containerLoginForm.classList.add("hidden");
    containerFirstLoginForm.classList.remove("hidden");
}

// ----- MESSAGE ERROR SUCCES SYSTEM ----- //
function sendClientErrorMessage(message, messageType) {
    const getParentElement = document.querySelector('.container');

    const existingAlerts = getParentElement.querySelectorAll('.alert');
    existingAlerts.forEach(alert => alert.remove());

    let alertPopup = ''; 

    let type = "";

    if(messageType == 'error') {
        type = "danger";
    } else if(messageType == 'success') {
        type = "success";
    }

    // Créer l'alerte en fonction du type
    alertPopup = `
        <div class="alert">
            <div class="alert-container">
                <div class="alert-spinner alert-spinner-${type}">
                    <div class="spinner-circle spinner-circle-${type}"></div>
                    
                    <img id="icons" class="icons-${type}" src="/build/images/icons/${type}.svg" alt="image-danger">
                </div>
                <div id="alert-content">
                    <p><strong>${type == "danger" ? "Erreur" : "Succès"}</strong></p>
                    <small>${message}</small>
                </div>
            </div>
        </div>
    `;


    getParentElement.insertAdjacentHTML('beforeend', alertPopup);
    setTimeout(() => {
        const alertElement = getParentElement.querySelector('.alert');
        alertElement.classList.add('animation-fade'); // Lancer l'animation
        document.querySelector(".spinner-circle").style.animationPlayState = "paused";
        
        setTimeout(() => {
            // Applique l'animation "alert" pour le décalage vers la droite
            alertElement.remove();
        }, 1000);  
    }, 3000); 
} 
