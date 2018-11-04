@extends("admin.includes._master", ["title" => "TrashMates - Game Tool"])

@section("content")
    <div class="tools">
        <input id="game" type="text" class="input" placeholder="Search a Game on Twitch">
        <input id="lang" type="text" class="input" placeholder="Language">


    <table id="table" class="hidden">
        <thead>
            <th>Viewers Count</th>
            <th>Streams Count</th>
            <th>Percentage</th>
            <th>Above</th>
            <th>Est. Position</th>
        </thead>
        <tbody id="stats">

        </tbody>
    </table>
    </div>

@endsection()


@section("scripts")
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script defer src="{{ asset("js/tools/game.js") }}"></script>
@endsection()
