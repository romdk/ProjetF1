const chargement = document.getElementById("chargement")
const container = document.getElementById("container")

function loadPage() {
    chargement.removeAttribute('hidden')

    // document.querySelector('video').playbackRate = 10;   

    // ------------------------------------------------HOME--------------------------------
        // ------------------------------------------------COUNTDOWN--------------------------------
            const currentYear = new Date().getFullYear()
            const url = '/grandsprix'
            fetch(url , {
                method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'Accept': '*/*'
                    },
                    body: new URLSearchParams({ 
                        'year' : currentYear
                    })
            })
                .then(async (response) => {
                    const rep = await response.json() 
                    
                    // on crée une fonction qui se répète toutes les secondes
                    let x = setInterval(function(){
                        let now = new Date().getTime()   
                        let nextGpDate = ''

                        // on créer une boucle sur chaque course de la saison
                        rep.MRData.RaceTable.Races.every(race => {
                            //si la date de la course est posterieur à la date actuelle on définit cette date comme date du prochain GP puis on stop la boucle 
                            if (new Date(race.date + ' ' + race.time).getTime() >= now){
                                document.getElementById("nextGp").innerHTML = race.raceName + ' :'
                                nextGpDate = new Date(race.date + ' '+race.time).getTime()
                                return false
                            }
                            return true
                        })

                        let timeRemaining = nextGpDate - now 
                        
                        let jours = Math.floor(timeRemaining / (1000 * 60 * 60 * 24))
                        let heures = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))
                        let minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60))
                        let secondes = Math.floor((timeRemaining % (1000 * 60)) / (1000))
                        
                        document.getElementById("jours").innerHTML = ("0" + jours).slice(-2)
                        document.getElementById("heures").innerHTML = ("0" + heures).slice(-2)
                        document.getElementById("minutes").innerHTML = ("0" + minutes).slice(-2)
                        document.getElementById("secondes").innerHTML = ("0" + secondes).slice(-2)
                        
                    },1000)

                    // ------------------------------------------------ACCORDEON--------------------------------
                    const accordeon = document.getElementById("accordeonGp")
                    // pour chaque GP on crée une card qui contient une image et une date
                    rep.MRData.RaceTable.Races.forEach(race => {
                        const cardGp = document.createElement("div")
                        cardGp.classList.add("cardGp")
                        accordeon.appendChild(cardGp)

                        const lien = document.createElement('a')
                        cardGp.appendChild(lien)
                        lien.href = '/grandprix/'+race.season+'_'+race.round

                        const imgGp = document.createElement("img")
                        imgGp.classList.add("imgGp")
                        lien.appendChild(imgGp)
                        imgGp.src = "../assets/grandprix/" + race.season + "_" + race.round + ".png"

                        const dateGp = document.createElement("p")
                        dateGp.classList.add("dateGp")
                        lien.appendChild(dateGp)
                        moment.locale('fr')
                        dateGp.innerHTML = moment(race.date).format('D MMMM')
                    });
                })
                
                // ------------------------------------------------LAST RESULTS--------------------------------
                const url2 = '/lastResults'
                fetch(url2 , {
                    method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'Accept': '*/*'
                        },
                        body: '.json'
                })
                .then(async (response) => {
                    // chargement.setAttribute('hidden', '')
                    chargement.style.transform = 'translateY(-100vh)'
                    container.removeAttribute('hidden')
                    const rep = await response.json()
                    const raceResults = rep.MRData.RaceTable.Races[0].Results
                    const lastRaceResultsUl = document.getElementById("lastRaceResultsUl")
                    const lastRaceResultsHeader = document.getElementById('lastRaceResultsHeader')
                    const headerImg = document.createElement("img")
                    lastRaceResultsHeader.appendChild(headerImg)
                    headerImg.src = "../assets/grandprix/" + rep.MRData.RaceTable.season + "_" + rep.MRData.RaceTable.round + "_header.png"
                    const headerTitle = document.createElement("span")
                    lastRaceResultsHeader.appendChild(headerTitle)
                    headerTitle.innerHTML = rep.MRData.RaceTable.Races[0].raceName

                    // on associe chaque écurie à une couleur
                    var couleursEcuries = {
                        red_bull : '#0d1a2a',
                        ferrari : '#fa0c00',
                        mercedes : '#019c96',
                        mclaren : '#ff8800',
                        alpine : '#0856ab',
                        aston_martin : '#00594f',
                        alphatauri : '#080e1a',
                        alfa : '#9f2234',
                        williams : '#0e1c67',
                        haas : '#ec1b3b'
                    }

                    raceResults.forEach(result => {

                        const lastRaceResultsLi = document.createElement("li")
                        lastRaceResultsUl.appendChild(lastRaceResultsLi)
                        const position = document.createElement("span")
                        lastRaceResultsLi.appendChild(position)
                        position.innerHTML = result.position
                        const couleur = document.createElement("div")
                        lastRaceResultsLi.appendChild(couleur)
                        couleur.style.backgroundColor = couleursEcuries[result.Constructor.constructorId]
                        const prenom = document.createElement("span")
                        lastRaceResultsLi.appendChild(prenom)
                        prenom.innerHTML = result.Driver.givenName
                        const nom = document.createElement("span")
                        lastRaceResultsLi.appendChild(nom)
                        nom.innerHTML = result.Driver.familyName
                        const ecurie = document.createElement("span")
                        lastRaceResultsLi.appendChild(ecurie)
                        ecurie.innerHTML = result.Constructor.name
                        const arrow = document.createElement("span")
                        lastRaceResultsLi.appendChild(arrow)
                        arrow.innerHTML = '<i class="fa-solid fa-chevron-right"></i>'
                    });            
                })
}
loadPage()




        

    
    
