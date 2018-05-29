@extends("admin.includes._master", ["title" => "TrashMates - Game Tool"])

@section("content")
    <div class="tools">
        <input id="game" type="text" class="input" placeholder="Search a Game on Twitch">

        <!-- STATS: STREAMS -->
        <div class="stats hide">
            <h5>Nombre de streamers par langue</h5>
            <canvas id="streamers-stats"></canvas>
        </div>

        <!-- STATS: REPARTITION VIEWERS / LANGUAGE -->
        <div class="stats hide">
            <h5>Nombre de viewers par langue</h5>
            <canvas id="viewers-stats"></canvas>
        </div>
    </div>
@endsection()


@section("scripts")
    <script defer src="{{ asset("js/tools/game.js") }}"></script>
@endsection()
