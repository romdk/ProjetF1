// on selectionne tous les bouton like
document.querySelectorAll('[data-fav]').forEach(element => {
    // event listener sur chaque bouton
    element.addEventListener('click', function(event){
        event.preventDefault();
        const url = this.href
        fetch(url, {
            method: 'POST',
        })
        // .then((response) => response.json())
        // .then((data) => {
            element.classList.toggle('fav')
        // });

    })
})