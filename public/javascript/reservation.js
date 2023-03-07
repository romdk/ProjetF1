// recupere l'année et le round dans l'url
let year = window.location.href.slice(34,38)
let round = window.location.href.slice(39,41)
console.log(year);
console.log(round);

const url = '/detailsGrandprix'
    fetch(url , {
        method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Accept': '*/*'
            },
            body: new URLSearchParams({ 
                'year' : year,
                'round' : round
            })
    })
    .then(async (response) => {
        const rep = await response.json()
        const map = document.getElementById('map')
        const img = document.createElement('img')
        const race = rep.MRData.RaceTable.Races[0]
        map.appendChild(img)
        img.src = "../assets/circuits/" + race.Circuit.circuitId + "_map.png"
    })