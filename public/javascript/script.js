// ------------------------------------------------COUNTDOWN --------------------------------
currentYear = new Date().getFullYear()
fetch(`http://ergast.com/api/f1/${currentYear}.json`)
.then( (response) => response.json())
.then((data)=> { 

    let n = 0
    let nextGp = data.MRData.RaceTable.Races[n]
    console.log(nextGp)
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
})