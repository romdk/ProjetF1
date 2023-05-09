let sessionInputs = document.getElementsByName('session')
let prix = document.getElementsByName('prix')

sessionInputs.forEach(sessionRadio => {
    sessionRadio.addEventListener('click', () => {
        let sessionValue = sessionRadio.value.slice(0,1);

        prix.forEach(element => {
            element.innerHTML = element.dataset.prix * sessionValue + '€'
        });
    })    
});