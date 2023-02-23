// ------------------------------------------------SEASONS--------------------------------
    const currentYear = new Date().getFullYear()
    const saison = document.getElementById('saison')
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

        if (year == currentYear){

            const url = '/grandsprix'
            fetch(url , {
                method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'Accept': '*/*'
                    },
                    body: new URLSearchParams({ 
                        'year' : year
                    })
            })
            .then(async (response) => {
                const rep = await response.json()
                const listeGp = document.createElement('ul')
                saison.removeChild(saison.lastChild)
                saison.appendChild(listeGp)
                listeGp.classList.add('listeGp')

                rep.MRData.RaceTable.Races.forEach(race => {
                    const cardGp = document.createElement('li')
                    listeGp.appendChild(cardGp)
                    const imgGp = document.createElement('img')
                    cardGp.appendChild(imgGp)
                    imgGp.src = "../assets/grandprix/" + race.season + "_" + race.round + ".png"
                    const dateGp = document.createElement('span')
                    cardGp.appendChild(dateGp)
                    moment.locale('fr')
                    dateGp.innerHTML = moment(race.date).format('D MMMM')
                })
            })
        }





        if (year != currentYear){
            const urlDriverStandings = '/driverStandings'
            fetch(urlDriverStandings, {
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
                console.log(rep)
                const driverStandings = rep.MRData.StandingsTable.StandingsLists[0].DriverStandings
                const driverStandingsUl = document.createElement("ul")
                saison.removeChild(saison.lastChild)
                saison.appendChild(driverStandingsUl)
                driverStandingsUl.classList.add('classement')
                // console.log(driverStandings);

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

                driverStandings.forEach(result => {
                    // console.log(result);
                    const driverStandingsLi = document.createElement("li")
                    driverStandingsUl.appendChild(driverStandingsLi)
                    const position = document.createElement("span")
                    driverStandingsLi.appendChild(position)
                    position.innerHTML = result.position
                    const couleur = document.createElement("div")
                    driverStandingsLi.appendChild(couleur)
                    couleur.style.backgroundColor = couleursEcuries[result.Constructors[0].constructorId]
                    const prenom = document.createElement("span")
                    driverStandingsLi.appendChild(prenom)
                    prenom.innerHTML = result.Driver.givenName
                    const nom = document.createElement("span")
                    driverStandingsLi.appendChild(nom)
                    nom.innerHTML = result.Driver.familyName
                    const ecurie = document.createElement("span")
                    driverStandingsLi.appendChild(ecurie)
                    ecurie.innerHTML = result.Constructors[0].name
                    const arrow = document.createElement("span")
                    driverStandingsLi.appendChild(arrow)
                    arrow.innerHTML = '<i class="fa-solid fa-chevron-right"></i>'            
                });


            })
        }
    })

