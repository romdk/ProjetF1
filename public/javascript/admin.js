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

// -------------------------------------------------UPLOAD IMAGE---------------------------------------------
let categorie = document.querySelector('#image_upload_categorie')

let circuitForm = document.querySelector('.imageCircuit')
let circuitName = document.querySelector('#image_upload_circuitName')
let circuitImageType = document.querySelector('#image_upload_imageCircuitType')

let ecurieForm = document.querySelector('.imageEcurie')
let ecurieName = document.querySelector('#image_upload_ecurieName')
let ecurieImageType = document.querySelector('#image_upload_ecurieImageType')

let grandprixForm = document.querySelector('.imageGrandprix')
let grandprixYear = document.querySelector('#image_upload_ecurieName')
let grandprixRound = document.querySelector('#image_upload_ecurieImageType')

let driverForm = document.querySelector('.imagePilote')
let driverName = document.querySelector('#image_upload_driverName')
let driverNumber = document.querySelector('#image_upload_driverNumber')
let driverImageType = document.querySelector('#image_upload_driverImageType')

let voitureForm = document.querySelector('.imageVoiture')
let teamName = document.querySelector('#image_upload_teamName')

categorie.addEventListener('change', () => {

    circuitName.value = null
    circuitImageType.value = null

    ecurieName.value = null
    ecurieImageType.value = null

    grandprixYear.value = null
    grandprixRound.value = null

    driverName.value = null
    driverNumber.value = null

    teamName.value = null

    if (categorie.value == 'circuits'){
        circuitForm.style.display = 'flex'
        ecurieForm.style.display = 'none'
        grandprixForm.style.display = 'none'
        driverForm.style.display = 'none'
        voitureForm.style.display = 'none'

    }else if (categorie.value == 'ecuries'){
        circuitForm.style.display = 'none'
        ecurieForm.style.display = 'flex'
        grandprixForm.style.display = 'none'
        driverForm.style.display = 'none'
        voitureForm.style.display = 'none'

    }else if (categorie.value == 'grandprix'){
        circuitForm.style.display = 'none'
        ecurieForm.style.display = 'none'
        grandprixForm.style.display = 'flex'
        driverForm.style.display = 'none'
        voitureForm.style.display = 'none'

    }else if (categorie.value == 'pilotes'){
        circuitForm.style.display = 'none'
        ecurieForm.style.display = 'none'
        grandprixForm.style.display = 'none'
        driverForm.style.display = 'flex'
        voitureForm.style.display = 'none'

        driverImageType.addEventListener('change', () => {

            driverNumber.value = null

            if (driverImageType.value == 1){
                document.querySelector('.numeroPiloteInput').style.display = 'block';
            }else {
                document.querySelector('.numeroPiloteInput').style.display = 'none';
            }

        })
        
    }else if (categorie.value == 'voitures'){
        circuitForm.style.display = 'none'
        ecurieForm.style.display = 'none'
        grandprixForm.style.display = 'none'
        driverForm.style.display = 'none'
        voitureForm.style.display = 'flex'
    }
})


