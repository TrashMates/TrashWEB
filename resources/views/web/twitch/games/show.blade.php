@extends("web._includes._master")

@section("content")

    @component("web._includes.components.title")
        <h5 class="mb-0">Twitch Games - {{ $game->name }}</h5>

        <button id="fetchStreams" class="btn btn-sm btn-primary">Fetch streams</button>
    @endcomponent

    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div id="game-card" class="card">
                <img class="card-img-top" src="{{ str_replace("{height}", "500", str_replace("{width}", "376", $game->box_art_url)) }}" alt="">
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
            <canvas id="streams" width="100%"></canvas>
        </div>

        @if($languages->isNotEmpty())
            <div class="col-12 my-3">
                <div class="card">
                    <div class="card-header">Streams</div>
                    <div class="card-body">
                        <div>
                            <label for="language">Select language</label>
                            <select id="language" class="form-control" name="language">
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
    </div>
@endsection

@section("scripts")

    <script>
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
                    borderColor: '##1A7CB0',
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


        window.addEventListener(`resize`, () => {
            $ctx.style.height = `${document.querySelector(`#game-card`).clientHeight}px`
        })


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

        document.querySelector(`#language`).addEventListener(`change`, (e) => {
            e.preventDefault()
            e.stopPropagation()

            let language = e.target.value
            axios.get(`${url}/twitch/streams?game_id={{ $game->id }}&language=${language}&stats`).then((response) => {
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

        })
        document.querySelector(`#language`).dispatchEvent(new Event(`change`))


        document.querySelectorAll(`.js-block`).forEach((button) => {
            button.addEventListener(`click`, (e) => {
                document.querySelectorAll(`.js-block`).forEach((button) => {
                    button.classList.add(`disabled`)
                    button.disabled = true
                })
            })
        })
    </script>

@endsection
