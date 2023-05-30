// -------------------------------------------------BOUTON BANNIR---------------------------------------------
// on selectione tous les boutons
document.querySelectorAll('[data-ban]').forEach(element => {

    // on selectionne le curseur du bouton
    let curseur = element.querySelector('.toggle')
    // on selectionne le texte du bouton
    let texte = curseur.querySelector('.text')

    // event listener sur chaque bouton
    element.addEventListener('click', function(event){
        event.preventDefault()
        const url = this.href
        console.log(url)
        fetch(url)

        // on ajoute/enleve la classe activeToggle à chaque click
        curseur.classList.toggle('activeToggle')

        // on remplace le texte ON/OFF
        if (texte.innerHTML === 'OFF'){
            texte.innerHTML = 'ON'
        }else{
            texte.innerHTML = 'OFF'
        }
    })
})  

// -------------------------------------------------BOUTON BANNIR---------------------------------------------
// on selectionne tous les bouton delete
document.querySelectorAll('[data-delete]').forEach(element => {
    // event listener sur chaque bouton
    element.addEventListener('click', function(event){
        event.preventDefault();
        const url = this.href
        console.log(url);
        fetch(url)
        element.parentNode.remove()
    })
})

