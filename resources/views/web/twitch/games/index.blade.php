@extends("web._includes._master")

@section("content")

    @component("web._includes.components.title")
        <h5 class="mb-0">Twitch Games</h5>
    @endcomponent

    <div class="card">
        <div class="card-header">Search a Game</div>
        <div class="card-body">
            <input id="query" class="form-control" type="text" placeholder="Search">
        </div>

    </div>

    <div id="games" class="d-none row mt-3">
    </div>

    <div id="alert" class="d-none alert alert-warning mt-3">
        <h4 class="alert-heading mt-3">404 - Not Found</h4>
        <hr>

        <p>
            We did not match any game with your query. <br>
            Would you like to <a id="fetch" href="#">fetch the game</a> instead ?
        </p>
    </div>

@endsection

@section("scripts")

    <script>
        let latestQuery = null
        let url = `/api`

        /**
         * TrashMates API
         * Query our database
         */

        let $query = document.querySelector(`#query`)
        $query.addEventListener(`keyup`, () => {

            let query = $query.value
            setTimeout(() => {

                // Query has changed before timeout
                if ($query.value !== query) {
                    return false
                }

                // Query hasn't changed since last time
                // if (latestQuery === $query.value) {
                //     return false
                // }

                latestQuery = $query.value
                console.log(`[QUERY] - ${query}`)

                document.querySelector(`#alert`).classList.add(`d-none`)
                document.querySelector(`#games`).classList.add(`d-none`)

                axios.get(`${url}/twitch/games?id=${query}&name=${query}`).then((response) => {

                    if (response.data.length === 0) {
                        document.querySelector(`#alert`).classList.remove(`d-none`)
                    } else {
                        document.querySelector(`#games`).innerHTML = ``
                        response.data.forEach((game) => {
                            let HTMLGame = document.createElement(`a`)
                            HTMLGame.href = `{{ route("twitch.games.show", "") }}/${game[`id`]}`
                            HTMLGame.className = `col-12 col-sm-6 col-md-3 col-lg-2 mb-3`
                            HTMLGame.innerHTML += `<div class="card">`
                            HTMLGame.innerHTML += `    <img class="card-img-top" src="${game[`box_art_url`].replace("{width}", "376").replace("{height}", "500")}">`
                            HTMLGame.innerHTML += `    <div class="card-footer text-center">${game[`name`]}</div>`
                            HTMLGame.innerHTML += `</div>`

                            document.querySelector(`#games`).append(HTMLGame)
                        })

                        document.querySelector(`#games`).classList.remove(`d-none`)
                    }

                }).catch(console.error)

            }, 250)

        })

        /**
         * Twitch API
         * Fetch a user from twitch and add it into our database
         */
        let $fetch = document.querySelector(`#fetch`)
        $fetch.addEventListener(`click`, (e) => {
            e.preventDefault()
            e.stopPropagation()

            axios.post(`${url}/twitch/games/fetch`, {name: latestQuery}).then((response) => {

                console.log(response.data)
                $query.dispatchEvent(new Event(`keyup`))

            }).catch((e) => {
                console.error(e.response.data)
            })
        })
    </script>

@endsection
