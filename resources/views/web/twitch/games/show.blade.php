@extends("web._includes._master")

@section("content")

    @component("web._includes.components.title")
        <h5 class="mb-0">Twitch Games - {{ $game->name }}</h5>

        <button id="fetchStreams" class="btn btn-sm btn-primary">Fetch streams</button>
    @endcomponent

    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div id="game-card" class="card">
                <img id="game-image" class="card-img-top" src="{{ str_replace("{height}", "500", str_replace("{width}", "376", $game->box_art_url)) }}" alt="">
                <div class="card-body text-center">
                    <b>{{ $game->name }}</b>
                </div>
                <div class="card-footer">
                    @if($game->stalking)
                        <button id="updateStatus" class="btn btn-sm btn-raised btn-dark btn-block js-block">Disable stalking</button>
                    @else
                        <button id="updateStatus" class="btn btn-sm btn-raised btn-success btn-block js-block">Enable stalking</button>
                    @endif
                    <a class="btn btn-sm btn-outline-secondary btn-block" href="https://twitch.tv/directory/game/{{ $game->name }}" target="_blank">Twitch.tv</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-9">
            <div class="card">
                <div class="card-body">
                    <canvas id="streams" width="100%"></canvas>
                </div>
            </div>
        </div>

        @if($languages->isNotEmpty())
            <div class="col-12 my-3">
                <div class="card">
                    <div class="card-header">Streams</div>
                    <div class="card-body">
                        <div>
                            <label for="streamsLanguage">Select language</label>
                            <select id="streamsLanguage" class="form-control" name="streamsLanguage">
                                <option value="" selected>All languages</option>
                                @foreach($languages as $language)
                                    <option value="{{ $language }}">{{ $language }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="stats">

                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="col-12 my-3">
            <div class="card">
                <div class="card-header">Streams</div>
                <div class="card-body">
                    <input id="streamsSearch" class="form-control mb-3" name="streamsSearch" placeholder="Search a stream">
                    <div class="table-responsive">
                        <table id="streamsTable" class="table table-sm table-hover table-striped d-none">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Language</th>
                                    <th>Username</th>
                                    <th>Title</th>
                                    <th>Avg</th>
                                    <th width="150px">Started at</th>
                                    <th width="150px">Stopped at</th>
                                    <th width="70px">Length</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <div id="streamsNotFoundAlert" class="d-none alert alert-danger mt-3">
                        <h4 class="alert-heading mt-3">404 - Not Found</h4>
                        <hr>

                        <p>We did not match any stream with your query.</p>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button id="prev-100" class="btn btn-primary">Previous 100</button>
                        <div></div>
                        <button id="next-100" class="btn btn-primary">Next 100</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("scripts")

    <script>
        // ---
        // - ChartJS
        // ---

        let $ctx = document.querySelector("#streams")
        let streamChart = new Chart($ctx, {
            type: "line",
            data: {
                datasets: [{
                    label: '# of Total Streams',
                    data: [],
                    backgroundColor: '#B6363666',
                    borderColor: '#B63636',
                    borderWidth: 1,
                }, {
                    label: '# of Finished Streams',
                    data: [],
                    backgroundColor: '#1A7CB066',
                    borderColor: '#1A7CB0',
                    borderWidth: 1,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                tooltips: {
                    mode: 'x',
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                        },
                    }],
                    xAxes: [{
                        type: 'time',
                        time: {
                            unit: 'day',
                            min: moment().subtract(1, "month"),
                            tooltipFormat: 'lll',
                        },
                    }],
                },
            },
        })

    </script>

    <script>
        // ---
        // - Buttons
        // ---

        let url = `/api`
        document.querySelector(`#fetchStreams`).addEventListener(`click`, (e) => {
            e.preventDefault()
            e.stopPropagation()

            axios.post(`${url}/twitch/streams/fetch`, {game_id: {{ $game->id }}}).then((response) => {
                location.reload(true)
            }).catch((e) => {
                console.error(e.response.data)
            })
        })

        document.querySelector(`#updateStatus`).addEventListener(`click`, (e) => {
            e.preventDefault()
            e.stopPropagation()

            axios.put(`${url}/twitch/games/{{ $game->id }}`, {id: {{ $game->id }}, stalking: {{ $game->stalking ? "false" : "true" }}}).then(() => {
                location.reload(true)
            }).catch((e) => {
                console.error(e.response.data)
            })
        })


        document.querySelectorAll(`.js-block`).forEach((button) => {
            button.addEventListener(`click`, (e) => {
                document.querySelectorAll(`.js-block`).forEach((button) => {
                    button.classList.add(`disabled`)
                    button.disabled = true
                })
            })
        })
    </script>

    <script>
        // ---
        // - Update stream Charts
        // ---

        let updateCharts = (language, title) => {
            console.log(`updateCharts(${language}, ${title})`)

            axios.get(`${url}/twitch/streams?game_id={{ $game->id }}&language=${language}&title=${title}&stats`).then((response) => {
                let allStreams = []
                let finishedStreams = []

                response.data.forEach((stream) => {
                    let allStreamIndex = allStreams.findIndex((s) => {
                        return s.x == moment(stream.created_at).format("YYYY-MM-DD")
                    })

                    if (allStreamIndex !== -1) {
                        allStreams[allStreamIndex].y += stream.total
                    } else {
                        allStreams.push({x: moment(stream.created_at).format("YYYY-MM-DD"), y: stream.total})
                    }


                    let finishedStreamIndex = finishedStreams.findIndex((s) => {
                        return s.x == moment(stream.created_at).format("YYYY-MM-DD")
                    })

                    if (finishedStreamIndex !== -1) {
                        finishedStreams[finishedStreamIndex].y += stream.finished
                    } else {
                        finishedStreams.push({x: moment(stream.created_at).format("YYYY-MM-DD"), y: stream.finished})
                    }
                })

                // Remove previous chart data
                streamChart.data.datasets.forEach((dataset) => {
                    dataset.data.pop()
                })

                // Add new chart data
                streamChart.data.datasets[0].data = allStreams.sort()
                streamChart.data.datasets[1].data = finishedStreams.sort()

                streamChart.update()

            }).catch(console.error)
        }
    </script>

    <script>
        // ---
        // - Update Stream Table
        // ---

        let updateStreamsTable = (language, title, after) => {
            console.log(`updateStreamTable(${language}, ${title}, ${after})`)

            document.querySelector(`#streamsTable`).classList.add(`d-none`)
            document.querySelector(`#streamsNotFoundAlert`).classList.add(`d-none`)
            document.querySelector(`#prev-100`).classList.add(`d-none`)
            document.querySelector(`#next-100`).classList.add(`d-none`)

            axios.get(`${url}/twitch/streams?game_id={{ $game->id }}&title=${title}&language=${language}&after=${after}&limit=100&with=user,metadata`).then((response) => {

                let tableBody = document.querySelector(`#streamsTable tbody`)
                tableBody.innerHTML = ``

                response.data.forEach((stream) => {
                    if (tableBody.children.length <= 100) {
                        let streamDuration = stream.stopped_at ? (moment.duration(moment(stream.stopped_at).diff(stream.created_at)).asMinutes()) : null
                        let streamAvg = stream.metadata.length ? (stream.metadata.map((s) => s.viewers).reduce((a, b) => {return a + b}) / stream.metadata.length).toFixed(2) : "No data"

                        let HTMLStream = document.createElement(`tr`)
                        HTMLStream.innerHTML = `<td>${stream.id}</td>`
                        HTMLStream.innerHTML += `<td>${stream.language}</td>`
                        HTMLStream.innerHTML += `<td><a href="{{ route("twitch.users.show", "") }}/${stream.user.id}">${stream.user.username}</a></td>`
                        HTMLStream.innerHTML += `<td><a href="{{ route("twitch.streams.show", "") }}/${stream.id}">${stream.title}</a></td>`
                        HTMLStream.innerHTML += `<td>${streamAvg}</td>`
                        HTMLStream.innerHTML += `<td>${moment(stream.created_at).format("DD/MM/YYYY HH:mm:ss")}</td>`
                        HTMLStream.innerHTML += `<td>${stream.stopped_at ? moment(stream.stopped_at).format("DD/MM/YYYY HH:mm:ss") : ""}</td>`
                        HTMLStream.innerHTML += `<td>${streamDuration ? (streamDuration / 60).toFixed(0) + ":" + ('0' + (streamDuration % 60).toFixed(0)).slice(-2) : ""}</td>`

                        tableBody.append(HTMLStream)
                    }
                })

                if (response.data.length === 0) {
                    document.querySelector(`#streamsNotFoundAlert`).classList.remove(`d-none`)
                } else {
                    document.querySelector(`#streamsTable`).classList.remove(`d-none`)

                    if (response.data.length >= 100) {
                        document.querySelector(`#next-100`).classList.remove(`d-none`)
                    }

                    if (after > 0) {
                        document.querySelector(`#prev-100`).classList.remove(`d-none`)
                    }

                }

            }).catch(console.error)
        }
    </script>

    <script>
        // ---
        // - EVENT SYSTEM
        // - Title search & language
        // ---

        let pagination = 0
        let previousQuery = document.querySelector(`#streamsSearch`).value

        document.querySelector(`#streamsSearch`).addEventListener(`change`, (e) => {
            e.preventDefault()

            let language = document.querySelector(`#streamsLanguage`).value
            let title = e.target.value

            // Title search has changed
            // Resetting pagination status
            if (title !== previousQuery) {
                previousQuery = title
                pagination = 0
            }

            updateStreamsTable(language, title, pagination)
            updateCharts(language, title)
        })

        document.querySelector(`#streamsLanguage`).addEventListener(`change`, (e) => {
            e.preventDefault()

            let language = e.target.value
            let title = document.querySelector(`#streamsSearch`).value

            pagination = 0

            updateStreamsTable(language, title, 0)
            updateCharts(language, title)
        })

        document.querySelector(`#prev-100`).addEventListener(`click`, (e) => {
            e.preventDefault()

            pagination -= 100
            document.querySelector(`#streamsSearch`).dispatchEvent(new Event(`change`))
        })

        document.querySelector(`#next-100`).addEventListener(`click`, (e) => {
            e.preventDefault()

            pagination += 100
            document.querySelector(`#streamsSearch`).dispatchEvent(new Event(`change`))
        })


        // ---
        // - When the page is resized
        // ---
        window.addEventListener(`resize`, () => {
            $ctx.parentNode.parentNode.style.height = `${document.querySelector(`#game-card`).clientHeight}px`
            $ctx.style.height = `${document.querySelector(`#game-card`).clientHeight - 40}px`
        })

        // ---
        // - When the image is loaded
        // - It will probably change the height of the card
        // - We have to trigger the resize again
        // ---
        document.querySelector(`#game-image`).addEventListener(`load`, (e) => {
            window.dispatchEvent(new Event(`resize`))
        })

        // ---
        // - When the document is loaded
        // - We resize
        // - And we fetch data
        // ---
        document.addEventListener("DOMContentLoaded", (e) => {
            window.dispatchEvent(new Event(`resize`))
            document.querySelector(`#streamsLanguage`).dispatchEvent(new Event(`change`))
        })
    </script>
@endsection
