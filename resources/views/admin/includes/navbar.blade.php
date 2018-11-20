<div class="navbar" id="navbar">

	<div class="menu">
		<h5>ADMIN</h5>
		<ul>
			<a href="{{ route("admin.index") }}">
				<li>Index</li>
			</a>
		</ul>
	</div>

	<div class="menu">
		<h5>TOOLS</h5>
		<ul>
			<a href="{{ route("admin.tools.games.index") }}">
				<li>Streamer Helper Initiative Tool</li>
			</a>
		</ul>
	</div>

	<div class="menu">
		<h5>DISCORD</h5>
		<ul>
			<a href="{{ route("admin.discord.events.index") }}">
				<li>Events</li>
			</a>
			<a href="{{ route("admin.discord.messages.index") }}">
				<li>Messages</li>
			</a>
			<a href="{{ route("admin.discord.viewers.index") }}">
				<li>Viewers</li>
			</a>
		</ul>
	</div>

	<div class="menu">
		<h5>TWITCH</h5>
		<ul>
			<a href="{{ route("admin.twitch.events.index") }}">
				<li>Events</li>
			</a>
			<a href="{{ route("admin.twitch.messages.index") }}">
				<li>Messages</li>
			</a>
			<a href="{{ route("admin.twitch.viewers.index") }}">
				<li>Viewers</li>
			</a>
		</ul>
	</div>

</div>