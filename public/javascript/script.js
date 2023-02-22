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
        }) + '.json'
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
        const accordeon = document.getElementById("accordeonGp")
        rep.MRData.RaceTable.Races.forEach(race => {
            console.log(race.season + "_" + race.round);
            const cardGp = document.createElement("div")
            cardGp.classList.add("cardGp")
            accordeon.appendChild(cardGp)
            const imgGp = document.createElement("img")
            imgGp.classList.add("imgGp")
            cardGp.appendChild(imgGp)
            imgGp.src = "../assets/grandprix/" + race.season + "_" + race.round + ".png"
            // const nomGp = document.createElement("p")
            // nomGp.classList.add("nomGp")
            // cardGp.appendChild(nomGp)
            // nomGp.innerHTML = race.raceName
            const dateGp = document.createElement("p")
            dateGp.classList.add("dateGp")
            cardGp.appendChild(dateGp)
            moment.locale('fr')
            dateGp.innerHTML = moment(race.date).format('D MMMM')
        });
    })

    
    
