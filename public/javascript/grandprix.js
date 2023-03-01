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
        fp1.innerHTML = timeFp1.slice(0,5).replace(":", "H") + " ESSAIS 1"
        
        const qualifying = document.createElement('span')
        vendredi.appendChild(qualifying)
        qualifying.innerHTML = timeQualifying.slice(0,5).replace(":", "H") + " QUALIFICATIONS"
        
        const fp2 = document.createElement('span')
        samedi.appendChild(fp2)
        fp2.innerHTML = timeFp2.slice(0,5).replace(":", "H") + " ESSAIS 2"
        
        const sprint = document.createElement('span')
        samedi.appendChild(sprint)
        sprint.innerHTML = timeSprint.slice(0,5).replace(":", "H") + " COURSE SPRINT"
        
        const race = document.createElement('span')
        dimanche.appendChild(race)
        race.innerHTML = timeRace.slice(0,5).replace(":", "H") + " GRAND PRIX"  

    } else {
        let timeFp3 = dataGp.ThirdPractice.time

        const fp1 = document.createElement('span')
        vendredi.appendChild(fp1)
        fp1.innerHTML = timeFp1.slice(0,5).replace(":", "H") + " ESSAIS 1"

        const fp2 = document.createElement('span')
        vendredi.appendChild(fp2)
        fp2.innerHTML = timeFp2.slice(0,5).replace(":", "H") + " ESSAIS 2"

        const fp3 = document.createElement('span')
        samedi.appendChild(fp3)
        fp3.innerHTML = timeFp3.slice(0,5).replace(":", "H") + " ESSAIS 3"

        const qualifying = document.createElement('span')
        samedi.appendChild(qualifying)
        qualifying.innerHTML = timeQualifying.slice(0,5).replace(":", "H") + " QUALIFICATIONS"

        const race = document.createElement('span')
        dimanche.appendChild(race)
        race.innerHTML = timeRace.slice(0,5).replace(":", "H") + " GRAND PRIX"
    }
   
    // récupere les coordonées du circuit depuis l'api
    let latitude = dataGp.Circuit.Location.lat
    let longitude = dataGp.Circuit.Location.long

    // récupere la date du weekend depuis l'api
    let dateDebut = dataGp.FirstPractice.date
    let dateGp = dataGp.date

    // récupere depuis l'api les prévisions météos des 16 prochains jours au coordonées indiqués
    fetch(`https://weatherbit-v1-mashape.p.rapidapi.com/forecast/daily?lat=${latitude}&lon=${longitude}&units=metric&lang=fr`,{
        method: 'GET',
            headers: {
                'X-RapidAPI-Key': '17faaf072bmshe1602147f720cacp1fa337jsn79f9cb1aecc2',
		        'X-RapidAPI-Host': 'weatherbit-v1-mashape.p.rapidapi.com'
            }
    })
    .then(async (response) => {
        const  iconMeteo = document.getElementById('iconMeteo')
        const temperature = document.getElementById('temperature')
        const descrMeteo = document.getElementById('descrMeteo')
        const date = document.getElementById('date')

        
        const rep = await response.json()
        // vérifie pour chaque prévision si la date correspond
        for (let i = 0; i < rep.data.length; i++) {
            const day = rep.data[i];
            if (day.datetime == dateGp){
                iconMeteo.src = day.weather.icon
                temperature.innerHTML = day.temp + '°'
                descrMeteo.innerHTML = day.weather.description
                moment.locale('fr')
                date.innerHTML = moment(dateDebut).format('D MMMM') + ' - ' + moment(day.datetime).format('D MMMM')
            }
        }
    })
})