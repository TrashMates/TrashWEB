@extends("web._includes._master")

@section("content")

    @component("web._includes.components.title")
        <h5 class="mb-0">Twitch Users - {{ $user->username }}</h5>

        <div>
            @isset($user->broadcaster_type)
                <div class="badge badge-primary">{{ $user->broadcaster_type }}</div>
            @endisset
            @isset($user->type)
                <div class="badge badge-danger">{{ $user->type }}</div>
            @endisset
        </div>
    @endcomponent

    <div class="row">
        <div class="col-12 col-sm-6 col-lg-3">
            <div id="user-card" class="card">
                <img id="user-image" class="card-img-top" src="{{ $user->profile_image_url }}" alt="">
                <div class="card-body text-center">
                    <b>{{ $user->username }}</b>
                    @if($user->description !== "")
                        <hr>
                        <p class="mb-0">
                            {{ $user->description }}
                        </p>
                    @endisset
                </div>
                <div class="card-footer">
                    <button id="updateFollowers" class="btn btn-sm btn-raised btn-primary btn-block js-block">Update followers [{{ count($user->followers) }}]</button>
                    <button id="updateFollowings" class="btn btn-sm btn-raised btn-primary btn-block js-block">Update followings [{{ count($user->followings) }}]</button>
                    <button id="updateUser" class="btn btn-sm btn-raised btn-primary btn-block js-block">Update user</button>
                    @if($user->stalking)
                        <button id="updateStatus" class="btn btn-sm btn-raised btn-dark btn-block js-block">Disable stalking</button>
                    @else
                        <button id="updateStatus" class="btn btn-sm btn-raised btn-success btn-block js-block">Enable stalking</button>
                    @endif
                    <a class="btn btn-sm btn-outline-secondary btn-block" href="https://twitch.tv/{{ $user->username }}" target="_blank">Twitch.tv channel</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-9">
            <div class="card">
                <div class="card-body">
                    <canvas id="events" width="100%"></canvas>
                </div>
            </div>
        </div>

        @if($user->streams->isNotEmpty())
            <div class="col-12 my-3">
                <div class="card">
                    <div class="card-header">Streams</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Language</th>
                                        <th>Game</th>
                                        <th>Title</th>
                                        <th>Avg</th>
                                        <th>Started at</th>
                                        <th>Stopped at</th>
                                        <th>Length</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->streams->sortByDesc("created_at") as $stream)
                                        <tr>
                                            <td>{{ $stream->language }}</td>
                                            <td><a href="{{ route("twitch.games.show", [$stream->game]) }}">{{ $stream->game->name }}</a></td>
                                            <td><a href="{{ route("twitch.streams.show", [$stream]) }}">{{ $stream->title }}</a></td>
                                            <td>{{ $stream->metadata->count() ? number_format($stream->metadata()->average("viewers"), 2) : "No data" }}</td>
                                            <td>{{ $stream->created_at->format("d/m/Y H:i:s") }}</td>
                                            <td>{{ $stream->stopped_at ? $stream->stopped_at->format("d/m/Y H:i:s") : ""}}</td>
                                            <td>{{ $stream->stopped_at ? $stream->stopped_at->diff($stream->created_at)->format("%H:%I:%S") . " hours" : "" }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endisset

        @if($user->followers->isNotEmpty())
            <div class="col-12 col-lg-6 my-3">
                <div class="card">
                    <div class="card-header" data-toggle="collapse" data-target="#followers">Followers</div>
                    <div id="followers" class="card-body collapse">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Broadcaster</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->followers as $follower)
                                        <tr>
                                            <td class="align-middle"><a href="{{ route("twitch.users.show", [$follower]) }}">{{ $follower->username }}</a></td>
                                            <td class="align-middle">{{ $follower->broadcaster_type }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($user->followings->isNotEmpty())
            <div class="col-12 col-lg-6 my-3">
                <div class="card">
                    <div class="card-header" data-toggle="collapse" data-target="#followings">Following</div>
                    <div id="followings" class="card-body collapse">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Broadcaster</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->followings as $following)
                                        <tr>
                                            <td class="align-middle"><a href="{{ route("twitch.users.show", [$following]) }}">{{ $following->username }}</a></td>
                                            <td class="align-middle">{{ $following->broadcaster_type }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection

@section("scripts")

    <script>
        // ---
        // - ChartJS
        // ---

        let $ctx = document.querySelector("#events")
        new Chart($ctx, {
            type: "bar",
            data: {
                datasets: [{
                    label: "# Followers",
                    borderWidth: 1,
                    borderColor: "#FF000055",
                    backgroundColor: "#FF000022",
                    data:  {!! ($followers->map(function ($item, $key) { return ["x" => $key, "y" => $item->count()]; })->values()) !!},
                }, {
                    label: "# Followings",
                    borderWidth: 1,
                    borderColor: "#0000FF22",
                    backgroundColor: "#0000FF22",
                    data:  {!! ($followings->map(function ($item, $key) { return ["x" => $key, "y" => $item->count()]; })->values()) !!},
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                hover: {
                    mode: null,
                },
                scales: {
                    xAxes: [{
                        type: "time",
                        gridLines: {
                            color: "rgba(0, 0, 0, 0)",
                        },
                        stacked: true,
                        maxBarThickness: 10,
                        time: {
                            unit: 'day',
                            distribution: "series",
                        },
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
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
        document.querySelector(`#updateUser`).addEventListener(`click`, (e) => {
            e.preventDefault()
            e.stopPropagation()

            axios.post(`${url}/twitch/users/fetch-user`, {id: {{ $user->id }}}).then(() => {
                location.reload(true)
            }).catch((e) => {
                console.error(e.response.data)
            })
        })

        document.querySelector(`#updateFollowers`).addEventListener(`click`, (e) => {
            e.preventDefault()
            e.stopPropagation()

            axios.post(`${url}/twitch/users/fetch-followers`, {id: {{ $user->id }}}).then(() => {
                location.reload(true)
            }).catch((e) => {
                console.error(e.response.data)
            })
        })

        document.querySelector(`#updateFollowings`).addEventListener(`click`, (e) => {
            e.preventDefault()
            e.stopPropagation()

            axios.post(`${url}/twitch/users/fetch-followings`, {id: {{ $user->id }}}).then(() => {
                location.reload(true)
            }).catch((e) => {
                console.error(e.response.data)
            })
        })

        document.querySelector(`#updateStatus`).addEventListener(`click`, (e) => {
            e.preventDefault()
            e.stopPropagation()

            axios.put(`${url}/twitch/users/{{ $user->id }}`, {id: {{ $user->id }}, stalking: {{ $user->stalking ? "false" : "true" }}}).then(() => {
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
        // - EVENT SYSTEM
        // ---
        window.addEventListener(`resize`, () => {
            $ctx.parentNode.parentNode.style.height = `${document.querySelector(`#user-card`).clientHeight}px`
            $ctx.style.height = `${document.querySelector(`#user-card`).clientHeight - 40}px`
        })


        // ---
        // - When the document is loaded
        // - We resize
        // - And we fetch data
        // ---
        document.addEventListener("DOMContentLoaded", (e) => {
            window.dispatchEvent(new Event(`resize`))
        })

        // ---
        // - When the image is loaded
        // - It will probably change the height of the card
        // - We have to trigger the resize again
        // ---
        document.querySelector(`#user-image`).addEventListener(`load`, (e) => {
            window.dispatchEvent(new Event(`resize`))
        })
    </script>

@endsection
