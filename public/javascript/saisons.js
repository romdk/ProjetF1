// ------------------------------------------------SEASONS--------------------------------
    const currentYear = new Date().getFullYear()
    const saison = document.getElementById('saison')
    const select = document.getElementById('saisonSelect')

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

    // afficher dans le selecteur les dernieres années jusqu'a 2000
    for(i = currentYear; i != 2000 -1; i--){
        let option = document.createElement('option')
        option.innerText = "Saison " + i
        option.value = i
        select.append(option)
    }

    // par défaut year = l'année actuelle
    let year = currentYear
    // par défaut on affiche le classement pilote
    let show = 'drivers'

    function affElements(){

        // supprime les elements qui existent déjà
        function removeIfExists(id){
            if (document.contains(document.getElementById(id))) {
                document.getElementById(id).remove();
            }
        }

        removeIfExists('listeGp')
        removeIfExists('driverStandingsUl')
        removeIfExists('constructorStandingsUl')
        removeIfExists('standingsSelector')
        
        if (year == currentYear){

            // recupere la liste des GP
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

                saison.appendChild(listeGp)
                listeGp.id = 'listeGp'
                listeGp.classList.add('listeGp')

                // pour chaque gp on créer une card
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
            // On créer un selecteur pour choisir d'afficher le classement pilotes ou constructeurs
            const standingsSelector = document.createElement('div')
            saison.appendChild(standingsSelector)
            standingsSelector.id = 'standingsSelector'
            
            const driversBtn = document.createElement('button')
            standingsSelector.appendChild(driversBtn)
            driversBtn.innerHTML = 'Pilotes'
            
            const constructorsBtn = document.createElement('button')
            standingsSelector.appendChild(constructorsBtn)
            constructorsBtn.innerHTML = 'Constructeurs'  

            driversBtn.addEventListener('click', () => {
                show = 'drivers'
                driversBtn.style.color = "#f1f1f1"
                constructorsBtn.style.color = "#8d8d8d"
                affElements()
            })
            
            constructorsBtn.addEventListener('click', () => {
                show = 'constructors'
                driversBtn.style.color = "#8d8d8d"
                constructorsBtn.style.color = "#f1f1f1"
                affElements()
            })
            
            
            if (show == 'drivers') {
                // récupere le classement pilotes
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
                    const driverStandings = rep.MRData.StandingsTable.StandingsLists[0].DriverStandings
                    const driverStandingsUl = document.createElement("ul")
                    driverStandingsUl.id = 'driverStandingsUl'                   

                    saison.appendChild(driverStandingsUl)
                    driverStandingsUl.id = 'driverStandingsUl'
                    driverStandingsUl.classList.add('classement')

                    // pour chaque résultat on ajoute une ligne dans notre liste driverStandings
                    driverStandings.forEach(result => {
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
            }else if(show == 'constructors'){
                console.log('test');
                // récupere le classement constructeur
                const urlConstructorStandings = '/constructorStandings'
                fetch(urlConstructorStandings, {
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
                    const constructorStandings = rep.MRData.StandingsTable.StandingsLists[0].ConstructorStandings
                    const constructorStandingsUl = document.createElement("ul")
                    
                    saison.appendChild(constructorStandingsUl)
                    constructorStandingsUl.id = 'constructorStandingsUl'
                    constructorStandingsUl.classList.add('classement')
    
                    // pour chaque résultat on ajoute une ligne dans notre liste constructorStandings
                    constructorStandings.forEach(result => {
                        const constructorStandingsLi = document.createElement("li")
                        constructorStandingsUl.appendChild(constructorStandingsLi)

                        const position = document.createElement("span")
                        constructorStandingsLi.appendChild(position)
                        position.innerHTML = result.position

                        const couleur = document.createElement("div")
                        constructorStandingsLi.appendChild(couleur)
                        couleur.style.backgroundColor = couleursEcuries[result.Constructor.constructorId]      

                        const ecurie = document.createElement("span")
                        constructorStandingsLi.appendChild(ecurie)
                        ecurie.innerHTML = result.Constructor.name

                        const arrow = document.createElement("span")
                        constructorStandingsLi.appendChild(arrow)
                        arrow.innerHTML = '<i class="fa-solid fa-chevron-right"></i>'            
                    });   
                })
            }


        }
    }
    
    affElements()
    
    // l'orsqu'on change l'année du selecteur, on execute la fonction affElements()
    select.addEventListener('change', (e) => {        
        e.preventDefault()
        year = select.selectedOptions[0].value   
        affElements()     
    })


    
    

