@extends("trashmates.includes.master")
@section("content")
	<div class="row">
		<div class="col-12 px-0 max-height">
			<div class="embed-responsive embed-responsive-16by9 max-height">
				<div id="stream"></div>
			</div>
		</div>
		<div class="col-12 px-0">
			<iframe title="Twitch Chat" src="https://www.twitch.tv/embed/trashmates/chat?darkpopout&no-mobile-redirect=true" frameborder="0" scrolling="no" height="500" width="100%"></iframe>
		</div>
	</div>

@endsection