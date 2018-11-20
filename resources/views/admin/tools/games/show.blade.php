@extends("admin.includes._master", ["title" => "TrashMates - Stalker Tool"])

@section("content")

	<div class="shit">
		<div class="title">
			<h4>{{ $game->name }}</h4>
			<div class="actions">
				<button id="scanNow">+</button>
				<a href="{{ route("admin.tools.games.index") }}">
					<button><</button>
				</a>
			</div>
		</div>

		<div class="scans_list">
			@foreach ($game->stats as $stat)
				<div class="scan js-stat-date" data-statid="{{ $stat->id }}">
					<h4>{{ \Carbon\Carbon::parse($stat->created_at)->toDayDateTimeString() }}</h4>
				</div>
			@endforeach
		</div>
			@foreach($game->stats as $stat)
		<div class="stats">
				<div id="stat-{{ $stat->id }}" class="stat js-stat" style="display: none;">
					<h4>{{ \Carbon\Carbon::parse($stat->created_at)->format('l j F Y, H:i:s') }}</h4>
					<select id="lang" name="lang">
						<option>all</option>
						@foreach($stat->streams->pluck("language")->unique() as $lang)
							<option>{{ $lang }}</option>
						@endforeach
					</select>
					<table id="streams" class="sortable">
						<thead>
							<th>#</th>
							<th>Title</th>
							<th>User</th>
							<th>Language</th>
							<th>Viewers</th>
						</thead>
						<tbody>
							@foreach($stat->streams as $key => $stream)
								<tr>
									<td class="center">{{ $key }}</td>
									<td class="title">{{ $stream->title }}</td>
									<td class="center"><a href="https://twitch.tv/{{ $stream->channel->username }}" target="_blank">{{ $stream->channel->username }}</a></td>
									<td class="center">{{ $stream->language }}</td>
									<td class="center">{{ $stream->viewers }}</td>
								</tr>
							@endforeach
							<tr class="total">
								<td class="center" colspan="4">TOTAL</td>
								<td class="center">{{ $stat->streams->sum("viewers") }}</td>
							</tr>
						</tbody>
					</table>
				</div>
			@endforeach
			<div class="stat">
				<h4>Words stats</h4>
				<table id="words" class="sortable" style="display: none">
					<thead>
						<th>Word</th>
						<th class="sorttable_numeric">Count</th>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>

@endsection


@section("scripts")
	<script defer src="https://unpkg.com/axios/dist/axios.min.js"></script>
	<script defer src="{{ asset("js/tools/scans.js") }}"></script>
@endsection