@extends("web._includes._master")

@section("content")

    @component("web._includes.components.title")
        <h5 class="mb-0">Twitch Stream - {{ $stream->title }}</h5>

        <div>
            @empty($user->stopped_at)
                <div class="badge badge-primary">LIVE</div>
            @endempty
        </div>
    @endcomponent




    <div class="row">
        <div class="col-12 col-sm-6 col-lg-3">
            <div id="user-card" class="card">
                <img id="user-image" class="card-img-top" src="{{ $stream->user->profile_image_url }}" alt="">
                <div class="card-body text-center">
                    <b>{{ $stream->user->username }}</b>
                    @if($stream->user->description !== "")
                        <hr>
                        <p class="mb-0">
                            {{ $stream->user->description }}
                        </p>
                    @endisset
                </div>
                <div class="card-footer">
                    <a class="btn btn-sm btn-raised btn-primary btn-block" href="{{ route("twitch.users.show", [$stream->user]) }}">Go to user</a>
                    <a class="btn btn-sm btn-outline-secondary btn-block" href="https://twitch.tv/{{ $stream->user->username }}" target="_blank">Twitch.tv channel</a>
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


    </div>
@endsection


@section("scripts")

    <script>
        // ---
        // - ChartJS
        // ---

        let $ctx = document.querySelector("#events")
        new Chart($ctx, {
            type: "line",
            data: {
                datasets: [{
                    label: "# Viewers",
                    borderWidth: 1,
                    borderColor: "#FF000055",
                    backgroundColor: "#FF000022",
                    data: {!! ($stream->metadata->map(function ($item, $key) { return ["x" => $item->created_at, "y" => $item->viewers]; })->values()) !!},
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
                            unit: 'minute',
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
