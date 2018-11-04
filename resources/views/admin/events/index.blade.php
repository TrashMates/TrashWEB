@extends("admin.includes._master", ["title" => "TrashMates - Events List"])

@section("content")
	<div class="admin-table">
		<h2>{{ $title }}</h2>

		@foreach($Events as $Event)
			<div class="event">
				<div class="infos toggleable">
					@if($Event->type == "VIEWER_CREATED" || $Event->type == "MEMBER_JOINED")
						<div class="type"><i class="fas fa-user-plus"></i></div>
					@elseif($Event->type == "VIEWER_FOLLOWED")
						<div class="type"><i class="fas fa-heart"></i></div>
					@elseif($Event->type == "VIEWER_SUBSCRIBED")
						<div class="type"><i class="fas fa-star"></i></div>
					@elseif($Event->type == "VIEWER_UPDATED" || $Event->type == "MEMBER_UPDATED")
						<div class="type"><i class="fas fa-edit"></i></div>
					@else
						<div class="type"><i class="fas fa-question"></i></div>
					@endif
					<div class="username">
						{{ $Event->viewer->username }}
						@if ($Event->viewer->discriminator)
							{{ "#" . $Event->viewer->discriminator }}
						@endif
					</div>
					<div class="date">{{ Carbon\Carbon::parse($Event->created_at)->diffForHumans() }}</div>
				</div>
				<div class="content">
					{{ $Event->content }}
				</div>
			</div>
		@endforeach

	<!-- PAGINATION -->
		@include("admin.includes.pagination", ["count" => $count, "page" => $page])
	</div>
@endsection()