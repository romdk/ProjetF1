// ------------------------------------------------HOME --------------------------------
    // ------------------------------------------------COUNTDOWN --------------------------------
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
                
                let n = 0
                let nextGp = rep.MRData.RaceTable.Races[n]
                document.getElementById("nextGp").innerHTML = nextGp.raceName + ' :'
                let nextGpDate = nextGp.date +' '+ nextGp.time
                
                let countDownDate = new Date(nextGpDate).getTime()
                let x = setInterval(function(){
                    let now = new Date().getTime()        
                    let timeRemaining = countDownDate - now        
                    
                    let jours = Math.floor(timeRemaining / (1000 * 60 * 60 * 24))
                    let heures = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))
                    let minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60))
                    let secondes = Math.floor((timeRemaining % (1000 * 60)) / (1000))
                    
                    document.getElementById("jours").innerHTML = ("0" + jours).slice(-2)
                    document.getElementById("heures").innerHTML = ("0" + heures).slice(-2)
                    document.getElementById("minutes").innerHTML = ("0" + minutes).slice(-2)
                    document.getElementById("secondes").innerHTML = ("0" + secondes).slice(-2)
                    
                },1000)
                // ------------------------------------------------ACCORDEON --------------------------------
                const accordeon = document.getElementById("accordeonGp")
                rep.MRData.RaceTable.Races.forEach(race => {
                    const cardGp = document.createElement("div")
                    cardGp.classList.add("cardGp")
                    accordeon.appendChild(cardGp)
                    const imgGp = document.createElement("img")
                    imgGp.classList.add("imgGp")
                    cardGp.appendChild(imgGp)
                    imgGp.src = "../assets/grandprix/" + race.season + "_" + race.round + ".png"
                    const dateGp = document.createElement("p")
                    dateGp.classList.add("dateGp")
                    cardGp.appendChild(dateGp)
                    moment.locale('fr')
                    dateGp.innerHTML = moment(race.date).format('D MMMM')
                });
            })
            
            // ------------------------------------------------LAST RESULTS --------------------------------
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




        

    
    
