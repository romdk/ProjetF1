// on selectionne tous les bouton like
document.querySelectorAll('[data-like]').forEach(element => {
    // event listener sur chaque bouton
    element.addEventListener('click', function(event){
        event.preventDefault();
        const url = this.href
        fetch(url, {
            method: 'POST',
        })
        .then((response) => response.json())
        .then((data) => {
            nbLike = data.nbLike
            const span = this.querySelector('span')
            span.innerHTML = nbLike

            element.classList.toggle('liked')
        });

    })
})

// on selectionne tous les bouton delete
document.querySelectorAll('[data-delete]').forEach(element => {
    // event listener sur chaque bouton
    element.addEventListener('click', function(event){
        event.preventDefault();
        const url = this.href
        console.log(url);
        fetch(url)
        this.parentNode.parentNode.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode.parentNode.parentNode)
    })
})

// event listener sur les boutons répondre
document.querySelectorAll('[data-reply]').forEach(element => {
    // défini dans le formulaire l'id du post sur lequel on clique et affiche le formulaire de réponse
    element.addEventListener('click', function(){
        document.querySelector('#reponse_postId').value = this.dataset.id
        document.getElementById('addReponse').style.display = "flex"
    })
})

document.getElementById('btnFermer').addEventListener('click', ()=>{
    document.getElementById('addReponse').style.display = "none"
})