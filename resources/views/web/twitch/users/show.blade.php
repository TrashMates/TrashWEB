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
        <div class="col-12 col-sm-6 col-md-3">
            <div id="user-card" class="card">
                <img class="card-img-top" src="{{ $user->profile_image_url }}" alt="">
                <div class="card-body text-center">
                    <b>{{ $user->username }}</b>
                </div>
                <div class="card-footer">
                    <a class="btn btn-sm btn-outline-primary" href="https://twitch.tv/{{ $user->username }}">Twitch.tv</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-9">
            <canvas id="events" width="100%"></canvas>
        </div>
    </div>
@endsection

@section("scripts")

    <script>
        let $ctx = document.querySelector("#events")
        let eventsChart = new Chart($ctx, {
            type: "line",
            data: {
                labels: {!! $events->keys()  !!} ,
                datasets: [{
                    label: '# of Events',
                    data: {!! $events->map(function ($items, $key) { return json_decode(json_encode(["x" => $key, "y" => $items->count()])); })->values()  !!},
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
            $ctx.style.height = `${document.querySelector(`#user-card`).clientHeight}px`
        })
    </script>

@endsection
