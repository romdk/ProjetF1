// ------------------------------------------------SEASONS--------------------------------
    const currentYear = new Date().getFullYear()
    const saison = document.getElementById('saison')
    const select = document.getElementById('saisonSelect')
    const calendrier = document.getElementById('calendrier')
    const classements = document.getElementById('classements')

    var couleursEcuries = {
        red_bull : '#3671c6',
        ferrari : '#f91536',
        mercedes : '#6cd3bf',
        mclaren : '#f58020',
        alpine : '#2293d1',
        aston_martin : '#00594f',
        alphatauri : '#080e1a',
        alfa : '#c92d4b',
        williams : '#101593',
        haas : '#b4b4b4',
        racing_point: 'ff82c3',
        renault : '#f1e132',
        toro_rosso : '#080e1a',
        sauber : '#c92d4b',
        force_india : '#ff82c3',
        manor : '#ed1b24',
        lotus_f1 : '#bf9000',
        caterham : '#20b94c',
        marussia : '#ed1b24',
        hrt : '#b4b4b4',
        lotus_racing : '#145e29',
        virgin : '#434343',
        brawn : '#bdde4b',
        toyota : '#cc0000',
        bmw_sauber : '#6fa8dc'
    }

    // afficher dans le selecteur les 10 dernières années
    for(i = currentYear; i != currentYear -11; i--){
        let option = document.createElement('option')
        option.innerText = "Saison " + i
        option.value = i
        select.append(option)
    }

    // on met l'année en cours par défaut
    let year = currentYear

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
        
        // on affiche la liste des gp seulement pour l'année en cours
        if (year == currentYear){
            calendrier.style.display = 'flex'

            // recupere la liste des GP depuis l'api
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

                calendrier.appendChild(listeGp)
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
        }else {
            calendrier.style.display = 'none'
        }

        // On créer un selecteur pour choisir d'afficher le classement pilotes ou constructeurs
        const standingsSelector = document.createElement('div')
        classements.appendChild(standingsSelector)
        standingsSelector.id = 'standingsSelector'
        
        let driversBtn = document.createElement('button')
        standingsSelector.appendChild(driversBtn)
        driversBtn.innerHTML = 'Pilotes'
        driversBtn.id = 'driversBtn'
        driversBtn.style.color = '#f1f1f1'
        
        let constructorsBtn = document.createElement('button')
        standingsSelector.appendChild(constructorsBtn)
        constructorsBtn.innerHTML = 'Constructeurs'
        constructorsBtn.id = 'constructorsBtn'          
        
        // récupere le classement pilotes depuis l'api
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

            classements.appendChild(driverStandingsUl)
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
                // !!API pas a jour pour 2023!!
                if (year == currentYear){
                    couleur.style.backgroundColor ="black"
                }else{
                    couleur.style.backgroundColor = couleursEcuries[result.Constructors[0].constructorId]
                }

                const prenom = document.createElement("span")
                driverStandingsLi.appendChild(prenom)
                prenom.innerHTML = result.Driver.givenName

                const nom = document.createElement("span")
                driverStandingsLi.appendChild(nom)
                nom.innerHTML = result.Driver.familyName

                const ecurie = document.createElement("span")
                driverStandingsLi.appendChild(ecurie)
                // !!API pas a jour pour 2023!!
                if (year == currentYear){
                    ecurie.innerHTML = 'undefined'
                } else {
                    ecurie.innerHTML = result.Constructors[0].name
                }

                const arrow = document.createElement("span")
                driverStandingsLi.appendChild(arrow)
                arrow.innerHTML = '<i class="fa-solid fa-chevron-right"></i>'            
            });
        })

        // récupere le classement constructeur depuis l'api
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
            
            classements.appendChild(constructorStandingsUl)
            constructorStandingsUl.id = 'constructorStandingsUl'
            constructorStandingsUl.classList.add('classement')
            constructorStandingsUl.style.display = "none"

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
        // on affiche le tableau pilotes lorsqu'on clique sur le bouton pilotes
        driversBtn.addEventListener('click', () => {        
            driversBtn.style.color = "#f1f1f1"
            constructorsBtn.style.color = "#8d8d8d"
            driverStandingsUl.style.display = "block"
            constructorStandingsUl.style.display = "none"
        })
        // on affiche le tableau constructeurs lorsqu'on clique sur le bouton constructeurs
        constructorsBtn.addEventListener('click', () => {
            driversBtn.style.color = "#8d8d8d"
            constructorsBtn.style.color = "#f1f1f1"
            driverStandingsUl.style.display = "none"
            constructorStandingsUl.style.display = "block"
        })
    }
    
    affElements()   

    // l'orsqu'on change l'année du selecteur, on execute la fonction affElements()
    select.addEventListener('change', (e) => {        
        e.preventDefault()
        year = select.selectedOptions[0].value   
        affElements()     
    })





    
    

