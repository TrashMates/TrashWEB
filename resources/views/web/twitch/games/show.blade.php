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
                    <a class="btn btn-sm btn-outline-secondary btn-block" href="https://twitch.tv/directory/game/{{ $game->name }}">Twitch.tv</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-9">
            <canvas id="streams" width="100%"></canvas>
        </div>

        @if($streams->isNotEmpty())
            <div class="col-12 my-3">
                <div class="card">
                    <div class="card-header">Streams</div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Language</th>
                                    <th>Title</th>
                                    <th>Started at</th>
                                    <th>Stopped at</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($streams as $streamDay)
                                    @foreach($streamDay as $stream)
                                        <tr>
                                            <td><a href="{{ route("twitch.users.show", [$stream->user]) }}">{{ $stream->user->username }}</a></td>
                                            <td>{{ $stream->language }}</td>
                                            <td>{{ $stream->title }}</td>
                                            <td>{{ $stream->created_at->format("d/m/Y H:i:s") }}</td>
                                            <td>{{ $stream->stopped_at ? $stream->stopped_at->format("d/m/Y H:i:s") : ""}}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
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
                labels: {!! $streams->keys()  !!} ,
                datasets: [{
                    label: '# of Streams',
                    data: {!! $streams->map(function ($items, $key) { return json_decode(json_encode(["x" => $key, "y" => $items->count()])); })->values()  !!},
                    backgroundColor: [
                        '#B6363666',
                    ],
                    borderColor: [
                        '#B63636',
                    ],
                    borderWidth: 1,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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
