@extends("web._includes._master")

@section("content")

    @component("web._includes.components.title")
        <h5 class="mb-0">Twitch Users</h5>
    @endcomponent

    <div class="card">
        <div class="card-header">Search a user</div>
        <div class="card-body">
            <input id="query" class="form-control" type="text" placeholder="Search">
        </div>

    </div>

    <div class="card my-3">
        <div class="card-header">Users</div>
        <div class="card-body">
            <table id="table" class="d-none table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Broadcaster Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>

    <div id="alert" class="d-none alert alert-warning mt-3">
        <h4 class="alert-heading mt-3">404 - Not Found</h4>
        <hr>

        <p>
            We did not match any user with your query. <br>
            Would you like to <a id="fetch" href="#">fetch the user</a> instead ?
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

                // Query is empty
                if ($query.value === "") {
                    return false
                }

                latestQuery = $query.value
                console.log(`[QUERY] - ${query}`)

                document.querySelector(`#alert`).classList.add(`d-none`)
                document.querySelector(`#table`).classList.add(`d-none`)

                axios.get(`${url}/twitch/users?id=${query}&username=${query}`).then((response) => {

                    if (response.data.length === 0) {
                        document.querySelector(`#alert`).classList.remove(`d-none`)
                    } else {
                        document.querySelector(`#table tbody`).innerHTML = ``
                        response.data.forEach((user) => {
                            let HTMLUser = document.createElement(`tr`)
                            HTMLUser.innerHTML += `<td>${user[`id`]}</td>`
                            HTMLUser.innerHTML += `<td>${user[`username`]}</td>`
                            HTMLUser.innerHTML += `<td>${user[`broadcaster_type`] || ""}</td>`
                            HTMLUser.innerHTML += `<td><a href="{{ route("twitch.users.show", "") }}/${user[`id`]}">Profile</a></td>`

                            document.querySelector(`#table tbody`).append(HTMLUser)
                        })

                        document.querySelector(`#table`).classList.remove(`d-none`)
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

            axios.post(`${url}/twitch/users/fetch-user`, {username: latestQuery}).then((response) => {

                console.log(response.data)
                $query.dispatchEvent(new Event(`keyup`))

            }).catch((e) => {
                console.error(e.response.data)
            })
        })
    </script>

@endsection
