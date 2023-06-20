// on selectionne les inputs du choix de la session
let sessionInputs = document.getElementsByName('session')
// on selectionne les element qui permettent d'afficher le prix
let prix = document.getElementsByName('prix')

// pour chaque inputs on ajoute un ecouteur d'evenement sur le click
sessionInputs.forEach(sessionRadio => {
    sessionRadio.addEventListener('click', () => {
        // on attribue a une variable le coefficient de l'element sur lequel on a cliqué
        let sessionValue = sessionRadio.value.slice(0,1);

        // pour chacun des prix, on multiplie le prix de l'emplacement par le coefficient de la session
        prix.forEach(element => {
            element.innerHTML = element.dataset.prix * sessionValue + '€'
        });
    })    
});