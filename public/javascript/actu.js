let btnTous = document.getElementById('btnTous');
let btnFavoris = document.getElementById('btnFavoris');

let tous = document.getElementById("tous");
let favoris = document.getElementById("favoris");

btnTous.addEventListener('click', () => {
    tous.style.display = 'flex'
    favoris.style.display = 'none'
    btnTous.classList.add("active")
    btnFavoris.classList.remove("active")
})  

btnFavoris.addEventListener('click', () => {
    tous.style.display = 'none'
    favoris.style.display = 'flex'
    btnTous.classList.remove("active")
    btnFavoris.classList.add("active")
})  

// ---------------------------------------------SCROLL FOOTER-----------------------------------

container = document.getElementById('actu')
let previousScrollPositionContainer = container.scrollTop;

container.addEventListener('scroll', () => {
    let currentScrollPosition = container.scrollTop;
    console.log(window.scrollY);
    
    // Si l'utilisateur fait défiler vers le haut et que le footer est visible
    if (currentScrollPosition < previousScrollPositionContainer && window.scrollY == 200)  {
        window.scroll(0,0)
        console.log('scroll haut');
    }
    previousScrollPositionContainer = currentScrollPosition;
})