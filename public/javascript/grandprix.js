// recupere l'année et le round dans l'url
let year = window.location.href.slice(32,36)
let round = window.location.href.slice(37,39)

const chargement = document.getElementById("chargement")

function loadPage() {
    chargement.removeAttribute('hidden')

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
        let dateFin = dataGp.date

        // récupere depuis l'api les prévisions météos du weekend au coordonées indiqués
        const url = '/meteoGrandprix'
        fetch(url , {
            method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'Accept': '*/*'
                },
                body: new URLSearchParams({ 
                    'latitude' : latitude,
                    'longitude' : longitude,
                    'dateDebut' : dateDebut,
                    'dateFin' : dateFin
                })
        })
        .then(async (response) => {
            chargement.setAttribute('hidden', '')
            // si la météo est indisponible on affiche l'erreur météo
            if (response.status !== 200) {
                const errMeteo = document.getElementById('errMeteo')
                errMeteo.style.display = 'flex'
                const meteoData = document.querySelectorAll('#meteo div:not(div:last-child)')
                meteoData.forEach(data => {
                    data.style.display = 'none'              
                })            
            }else{
                const rep = await response.json()
                const iconMeteo = document.getElementById('iconMeteo')
                const temperature = document.getElementById('temperature')
                const descrMeteo = document.getElementById('descrMeteo')
                const date = document.getElementById('date')

                // on associe le code météo à une description et une icone
                let descriptionsMeteo = {
                    0 : { description: 'Ciel dégagé', icon:'<i class="fa-solid fa-sun"></i>'},
                    1 : { description: 'Principalement dégagé', icon:'<i class="fa-solid fa-sun"></i>'},
                    2 : { description: 'Partiellement nuageux', icon:'<i class="fa-solid fa-cloud-sun"></i>'},
                    3 : { description: 'Couvert', icon:'<i class="fa-solid fa-cloud"></i>'},
                    45 : { description: 'Brouillard', icon:'<i class="fa-solid fa-smog"></i>'},
                    48 : { description: 'Brouillard givré', icon:'<i class="fa-solid fa-smog"></i>'},
                    51 : { description: 'Légère bruine', icon:'<i class="fa-solid fa-water"></i>'},
                    53 : { description: 'Bruine modérée', icon:'<i class="fa-solid fa-water"></i>'},
                    55 : { description: 'Bruine dense', icon:'<i class="fa-solid fa-water"></i>'},
                    56 : { description: 'Légère bruine verglaçante', icon:'<i class="fa-solid fa-water"></i>'},
                    57 : { description: 'Bruine verglaçante', icon:'<i class="fa-solid fa-water"></i>'},
                    61 : { description: 'Légère pluie', icon:'<i class="fa-solid fa-cloud-sun-rain"></i>'},
                    63 : { description: 'Pluie', icon:'<i class="fa-solid fa-cloud-rain"></i>'},
                    65 : { description: 'Forte pluie', icon:'<i class="fa-solid fa-cloud-showers-heavy"></i>'},
                    66 : { description: 'Légère pluie Verglaçante', icon:'<i class="fa-solid fa-cloud-sun-rain"></i>'},
                    67 : { description: 'Pluie verglaçante', icon:'<i class="fa-solid fa-cloud-rain"></i>'},
                    71 : { description: 'Légères chutes de neige', icon:'<i class="fa-regular fa-snowflake"></i>'},
                    73 : { description: 'Chutes de neige', icon:'<i class="fa-regular fa-snowflake"></i>'},
                    75 : { description: 'Importantes chutes de neige', icon:'<i class="fa-regular fa-snowflake"></i>'},
                    77 : { description: 'Légères chutes de neige', icon:'<i class="fa-regular fa-snowflake"></i>'},
                    80 : { description: 'Légères averses de pluie', icon:'<i class="fa-solid fa-cloud-showers-water"></i>'},
                    81 : { description: 'Averses de pluie', icon:'<i class="fa-solid fa-cloud-showers-water"></i>'},
                    82 : { description: 'Fortes averses de pluie', icon:'<i class="fa-solid fa-cloud-showers-water"></i>'},
                    85 : { description: 'Tempête de neige', icon:'<i class="fa-regular fa-snowflake"></i>'},
                    86 : { description: 'Forte tempête de neige', icon:'<i class="fa-regular fa-snowflake"></i>'},
                    95 : { description: 'Orages', icon:'<i class="fa-solid fa-cloud-bolt"></i>'},
                    96 : { description: 'Orages et légère grêle', icon:'<i class="fa-solid fa-cloud-bolt"></i>'},
                    99 : { description: 'Orages et grêle', icon:'<i class="fa-solid fa-cloud-bolt"></i>'}
                }

                rep.daily.weathercode.forEach(data => {
                    const dayIcon = document.createElement('span')
                    iconMeteo.appendChild(dayIcon)
                    dayIcon.innerHTML = descriptionsMeteo[data].icon 

                    const dayDescr = document.createElement('span')
                    descrMeteo.appendChild(dayDescr)
                    dayDescr.innerHTML = descriptionsMeteo[data].description
                })

                rep.daily.temperature_2m_max.forEach(data => {
                    const dayTemperature = document.createElement('span')
                    temperature.appendChild(dayTemperature)
                    dayTemperature.innerHTML = Math.round(data) + '°C'      
                })

                rep.daily.time.forEach(data => {
                    const day = document.createElement('span')
                    date.appendChild(day)
                    moment.locale('fr')
                    day.innerHTML = moment(data).format('D MMMM')
                })


                const btnVendredi = document.getElementById('btnVendredi')        
                // affiche les informations du vendredi
                btnVendredi.addEventListener('click', () =>{
                    btnVendredi.classList.add('active')
                    btnSamedi.classList.remove('active')
                    btnDimanche.classList.remove('active')
                    document.querySelector('#iconMeteo > span:first-child').style.display = 'block'
                    document.querySelector('#iconMeteo > span:nth-child(2)').style.display = 'none'
                    document.querySelector('#iconMeteo > span:last-child').style.display = 'none'
                    document.querySelector('#temperature > span:first-child').style.display = 'block'
                    document.querySelector('#temperature > span:nth-child(2)').style.display = 'none'
                    document.querySelector('#temperature > span:last-child').style.display = 'none'
                    document.querySelector('#descrMeteo > span:first-child').style.display = 'block'
                    document.querySelector('#descrMeteo > span:nth-child(2)').style.display = 'none'
                    document.querySelector('#descrMeteo > span:last-child').style.display = 'none'
                    document.querySelector('#date > span:first-child').style.display = 'block'
                    document.querySelector('#date > span:nth-child(2)').style.display = 'none'
                    document.querySelector('#date > span:last-child').style.display = 'none'
                })
                
                const btnSamedi = document.getElementById('btnSamedi')
                // affiche les informations du samedi
                btnSamedi.addEventListener('click', () =>{
                    btnVendredi.classList.remove('active')
                    btnSamedi.classList.add('active')
                    btnDimanche.classList.remove('active')
                    document.querySelector('#iconMeteo > span:first-child').style.display = 'none'
                    document.querySelector('#iconMeteo > span:nth-child(2)').style.display = 'block'
                    document.querySelector('#iconMeteo > span:last-child').style.display = 'none'
                    document.querySelector('#temperature > span:first-child').style.display = 'none'
                    document.querySelector('#temperature > span:nth-child(2)').style.display = 'block'
                    document.querySelector('#temperature > span:last-child').style.display = 'none'
                    document.querySelector('#descrMeteo > span:first-child').style.display = 'none'
                    document.querySelector('#descrMeteo > span:nth-child(2)').style.display = 'block'
                    document.querySelector('#descrMeteo > span:last-child').style.display = 'none'
                    document.querySelector('#date > span:first-child').style.display = 'none'
                    document.querySelector('#date > span:nth-child(2)').style.display = 'block'
                    document.querySelector('#date > span:last-child').style.display = 'none'
                    
                })
                
                const btnDimanche = document.getElementById('btnDimanche')
                // affiche les informations du dimanche
                btnDimanche.addEventListener('click', () =>{
                    btnVendredi.classList.remove('active')
                    btnSamedi.classList.remove('active')
                    btnDimanche.classList.add('active')
                    document.querySelector('#iconMeteo > span:first-child').style.display = 'none'
                    document.querySelector('#iconMeteo > span:nth-child(2)').style.display = 'none'
                    document.querySelector('#iconMeteo > span:last-child').style.display = 'block'
                    document.querySelector('#temperature > span:first-child').style.display = 'none'
                    document.querySelector('#temperature > span:nth-child(2)').style.display = 'none'
                    document.querySelector('#temperature > span:last-child').style.display = 'block'
                    document.querySelector('#descrMeteo > span:first-child').style.display = 'none'
                    document.querySelector('#descrMeteo > span:nth-child(2)').style.display = 'none'
                    document.querySelector('#descrMeteo > span:last-child').style.display = 'block'
                    document.querySelector('#date > span:first-child').style.display = 'none'
                    document.querySelector('#date > span:nth-child(2)').style.display = 'none'
                    document.querySelector('#date > span:last-child').style.display = 'block'
                }) 
            }       
        })
    })
}
loadPage()