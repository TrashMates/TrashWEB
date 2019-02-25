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
                <img class="card-img-top" src="{{ $user->profile_image_url }}" alt="">
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
                    <button id="updateFollowers" class="btn btn-sm btn-outline-primary btn-block">Update followers [{{ count($user->followers) }}]</button>
                    <button id="updateFollowings" class="btn btn-sm btn-outline-primary btn-block">Update followings [{{ count($user->followings) }}]</button>
                    <button id="updateUser" class="btn btn-sm btn-outline-primary btn-block">Update user</button>
                    <a class="btn btn-sm btn-outline-secondary btn-block" href="https://twitch.tv/{{ $user->username }}">Twitch.tv channel</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-9">
            <canvas id="events" width="100%"></canvas>
        </div>

        <div class="col-12 col-lg-6 my-3">
            <div class="card">
                <div class="card-header" data-toggle="collapse" data-target="#followers">Followers</div>
                <div id="followers" class="card-body collapse">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Broadcaster</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->followers as $follower)
                                <tr>
                                    <td class="align-middle">{{ $follower->username }}</td>
                                    <td class="align-middle">{{ $follower->broadcaster_type }}</td>
                                    <td class="align-middle" width="179px">
                                        <a class="btn btn-primary" href="{{ route("twitch.users.show", [$follower]) }}">Show profile</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6 my-3">
            <div class="card">
                <div class="card-header" data-toggle="collapse" data-target="#followings">Following</div>
                <div id="followings" class="card-body collapse">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Broadcaster</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->followings as $following)
                                <tr>
                                    <td class="align-middle">{{ $following->username }}</td>
                                    <td class="align-middle">{{ $following->broadcaster_type }}</td>
                                    <td class="align-middle" width="179px">
                                        <a class="btn btn-primary" href="{{ route("twitch.users.show", [$following]) }}">Show profile</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section("scripts")

    <script>
        let $ctx = document.querySelector("#events")
        let eventsChart = new Chart($ctx, {
            type: "bar",
            data: {
                {{--labels: {!! $events->keys()  !!} ,--}}
                datasets: [{
                    label: '# of Events received',
                    borderColor: "#b63636",
                    borderWidth: 1,
                    data: {!! $followers->map(function ($items, $key) { return json_decode(json_encode(["x" => $key, "y" => $items->count()])); })->values()  !!},
                    backgroundColor: '#B6363666',
                }, {
                    label: '# of Events given',
                    data: {!! $followings->map(function ($items, $key) { return json_decode(json_encode(["x" => $key, "y" => $items->count()])); })->values()  !!},
                    borderColor: '#1A7CB0',
                    backgroundColor: '#1A7CB066',
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
                            min: moment().subtract(2, "month"),
                            tooltipFormat: 'lll',
                        },
                    }],
                },
            },
        })


        window.addEventListener(`resize`, () => {
            $ctx.style.height = `${document.querySelector(`#user-card`).clientHeight}px`
        })

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
    </script>

@endsection
