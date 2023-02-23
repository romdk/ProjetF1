// ------------------------------------------------SEASONS--------------------------------
const currentYear = new Date().getFullYear()
const select = document.getElementById('saisonSelect')
for(i = currentYear; i != 2000 -1; i--){
    let option = document.createElement('option')
    option.innerText = "Saison " + i
    option.value = i
    select.append(option)
}

select.addEventListener('change', (e) => {
    e.preventDefault()
    const year = select.selectedOptions[0].value
    const urlGame = '/standinds'
    fetch(urlGame, {
        method: 'POST',
        headers: {
            'Content-type': 'application/x-www-form-urlencoded',
            'Accept': '*/*'
        },
        body: new URLSearchParams({
            'year' : year
        })
    })
    .then(async (response) => {
        const rep = await response.json()
        // console.log(rep);


    })
})