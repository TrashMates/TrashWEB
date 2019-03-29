<div class="row">
    <div class="col-12 p-0">
        <nav class="navbar navbar-expand-lg navbar-dark tm-bg-primary">
            <a class="navbar-brand" href="{{ route("web.index") }}">TrashMates</a>

            <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse"><span class="navbar-toggler-icon"></span></button>
            <div id="navbarCollapse" class="collapse navbar-collapse">
                <ul class="navbar-nav mt-2 mt-lg-0">
                    @yield("navbar")

                    <li class="nav-item"><a class="nav-link" href="#">Discord</a></li>
                    <li class="nav-item dropdown">
                        <a id="twitchDropdown" class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">Twitch</a>
                        <div class="dropdown-menu" aria-labelledby="twitchDropdown">
                            <a class="dropdown-item" href="{{ route("twitch.games.index") }}">Games</a>
                            <a class="dropdown-item" href="{{ route("twitch.users.index") }}">Users</a>
                            <div class="dropdown-divider"></div>
                        </div>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="#">Log In</a></li>
                </ul>
            </div>
        </nav>
    </div>
</div>
