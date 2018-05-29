<div class="navbar" id="navbar">

    <div class="menu">
        <h5>ADMIN</h5>
        <ul>
            <a href="{{ route("admin.index") }}"><li>Index</li></a>
        </ul>
    </div>
    <div class="menu">
        <h5>TOOLS</h5>
        <ul>
            <a href="{{ route("admin.tool.game") }}"><li>Current Streamed Games</li></a>
        </ul>
    </div>

    <div class="menu">
        <h5>DISCORD</h5>
        <ul>
            <a href="{{ route("admin.discord.events") }}"><li>Events</li></a>
            <a href="{{ route("admin.discord.messages") }}"><li>Messages</li></a>
            <a href="{{ route("admin.discord.viewers") }}"><li>Viewers</li></a>
        </ul>
    </div>

    <div class="menu">
        <h5>Twitch</h5>
        <ul>
            <a href="{{ route("admin.twitch.events") }}"><li>Events</li></a>
            <a href="{{ route("admin.twitch.messages") }}"><li>Messages</li></a>
            <a href="{{ route("admin.twitch.viewers") }}"><li>Viewers</li></a>
        </ul>
    </div>

</div>