@extends("admin.includes._master", ["title" => "TrashMates - Stalker Tool"])

@section("content")

	<div class="shit">
		<div class="title">
			<h4>Scanned Games</h4>
			<button id="gameAdd">Add a Game</button>
		</div>

		<div class="games_list">
			@foreach($games as $game)
				<a class="game" href="{{ route("admin.tools.games.show", [$game->id]) }}">
					<img src="{{ str_replace("{width}", "285", str_replace("{height}", "380", $game->picture)) }}">
					<h4>{{ $game->name }}</h4>
				</a>
			@endforeach
		</div>
	</div>

@endsection


@section("scripts")
	<script defer src="https://unpkg.com/axios/dist/axios.min.js"></script>
	<script defer src="{{ asset("js/tools/games.js") }}"></script>
@endsection