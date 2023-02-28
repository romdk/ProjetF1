// recupere l'année et le round dans l'url
let year = window.location.href.slice(32,36)
let round = window.location.href.slice(37,39)

// recupere les details du grandprix depuis l'api
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
    let dataGp = rep.MRData.RaceTable.Races[0]
        
    const nomGp = document.getElementById('nomGp')
    nomGp.innerHTML = dataGp.raceName

    const nomCircuit = document.getElementById('nomCircuit')
    nomCircuit.innerHTML = dataGp.Circuit.circuitName

    const drapeau = document.getElementById('drapeau')
    drapeau.src = "../assets/drapeaux/" + dataGp.Circuit.Location.country + ".png"

    const pays = document.getElementById('pays')
    pays.innerHTML = dataGp.Circuit.Location.country

    const trackLayout = document.getElementById('trackLayout')
    trackLayout.src = "../assets/circuits/" + dataGp.Circuit.circuitId + "_layout.png"

    let timeFp1 = dataGp.FirstPractice.time
    let timeFp2 = dataGp.SecondPractice.time
    let timeQualifying = dataGp.Qualifying.time
    let timeRace = dataGp.time

    const vendredi = document.getElementById('vendredi')
    const samedi = document.getElementById('samedi')
    const dimanche = document.getElementById('dimanche')

    // on créer le programme du weekend en fonction de si il contient une course sprint ou non
    if (dataGp.hasOwnProperty('Sprint')){
        let timeSprint = dataGp.Sprint.time

        const fp1 = document.createElement('span')
        vendredi.appendChild(fp1)
        fp1.innerHTML = timeFp1 + "ESSAIS 1"
        
        const qualifying = document.createElement('span')
        vendredi.appendChild(qualifying)
        qualifying.innerHTML = timeQualifying + "QUALIFICATIONS"
        
        const fp2 = document.createElement('span')
        samedi.appendChild(fp2)
        fp2.innerHTML = timeFp2 + "ESSAIS 2"
        
        const sprint = document.createElement('span')
        samedi.appendChild(sprint)
        sprint.innerHTML = timeSprint + "COURSE SPRINT"
        
        const race = document.createElement('span')
        dimanche.appendChild(race)
        race.innerHTML = timeRace + "GRAND PRIX"  

    } else {
        let timeFp3 = dataGp.ThirdPractice.time

        const fp1 = document.createElement('span')
        vendredi.appendChild(fp1)
        fp1.innerHTML = timeFp1 + "ESSAIS 1"

        const fp2 = document.createElement('span')
        vendredi.appendChild(fp2)
        fp2.innerHTML = timeFp2 + "ESSAIS 2"

        const fp3 = document.createElement('span')
        samedi.appendChild(fp3)
        fp3.innerHTML = timeFp3 + "ESSAIS 3"

        const qualifying = document.createElement('span')
        samedi.appendChild(qualifying)
        qualifying.innerHTML = timeQualifying + "QUALIFICATIONS"

        const race = document.createElement('span')
        dimanche.appendChild(race)
        race.innerHTML = timeRace + "GRAND PRIX"
    }
})