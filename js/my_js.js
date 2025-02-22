
const images = [  
    "https://cdn.pixabay.com/photo/2015/07/24/02/17/afghan-857794_960_720.jpg",  
    "https://cdn.pixabay.com/photo/2022/07/05/12/11/poor-7302954_960_720.jpg",  
    "https://cdn.pixabay.com/photo/2016/07/27/13/12/smile-1545243_960_720.jpg",  
    "https://cdn.pixabay.com/photo/2016/11/23/14/39/asia-1853267_960_720.jpg",  
    "https://cdn.pixabay.com/photo/2017/04/21/09/38/street-2248101_960_720.jpg",  
    "https://cdn.pixabay.com/photo/2017/04/20/10/12/children-of-uganda-2245270_960_720.jpg"  
];  

const messages = [  
    "Nous soutenons les familles  dans le besoin.",  
    "Aidez-nous à fournir des ressources aux plus démunis.",  
    "Chaque sourire compte, ensemble nous pouvons faire la différence.",  
    "Apportez votre soutien aux communautés vulnérables en Asie.",  
    "Aidez-nous à redonner espoir aux enfants dans le besoin.",  
    "Ensemble, nous pouvons changer des vies en Ouganda."  
];  
/*...fonction pour changer les images...*/
let currentIndex = 0;  
const backgroundElement = document.getElementById('backgroundCarrousel');  

function changeBackground() {  
    backgroundElement.style.backgroundImage = `url(${images[currentIndex]})`;  
    
    const messageElement = document.getElementById('message');  
    messageElement.innerText = messages[currentIndex];  
    messageElement.classList.remove('show'); // Enlève la classe d'affichage  
    void messageElement.offsetWidth; // Forcer le reflow pour réinitialiser l'animation  
    messageElement.classList.add('show'); // Ajoute la classe d'affichage  

    currentIndex = (currentIndex + 1) % images.length;  
}  

setInterval(changeBackground, 5000);  
changeBackground(); // Applique la première image immédiatement 

/*...le modal....*/
function toggleMenu(event) {  
    const modal = document.getElementById('modal');  
    const menu = document.getElementById('menu');  

    if (modal.style.display === "none" || modal.style.display === "") {  
        modal.style.display = "flex";  
        setTimeout(() => {  
            menu.classList.add('show');  
        }, 10);  
    } else {  
        closeModal();  
    }  
}  

function closeModal() {  
    const modal = document.getElementById('modal');  
    const menu = document.getElementById('menu');  
    menu.classList.remove('show');  
    setTimeout(() => {  
        modal.style.display = "none";  
    }, 300);  
}  

function menuItemClicked() {  
    closeModal();  
}  

// Fermer le modal si on clique en dehors  
window.onclick = function (event) {  
    const modal = document.getElementById('modal');  
    if (modal.style.display === "flex" && !event.target.matches('.hamburger')) {  
        closeModal();  
    }  
};  


