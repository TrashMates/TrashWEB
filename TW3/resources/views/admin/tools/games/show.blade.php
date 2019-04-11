@extends("admin.includes._master", ["title" => "TrashMates - Stalker Tool"])

@section("content")

	<div class="shit game">
		<div class="title">
			<h4>{{ $game->name }}</h4>
			<div class="actions">
				<button id="scanNow">+</button>
				<a href="{{ route("admin.tools.games.index") }}">
					<button><</button>
				</a>
			</div>
		</div>

		<div id="js-stats" class="stats">
			@foreach($game->stats as $stat)
				<div class="stat js-stat" data-statid="{{ $stat->id }}">{{ Carbon\Carbon::parse($stat->created_at)->format("d/m/y h:m:s") }}</div>
			@endforeach
		</div>

		<div id="settings">
			<button id="back">BACK</button>
			<select id="languages">
				<option value="all">All languages</option>
			</select>
		</div>

		<table id="streams" class="table"></table>
		<table id="words" class="table"></table>
	</div>
@endsection


@section("scripts")
	<script defer src="https://unpkg.com/axios/dist/axios.min.js"></script>
	<script defer src="{{ asset("js/tools/scans.js") }}"></script>
@endsection