@extends("web._includes._master")

@section("content")

    <div class="row d-flex align-items-center h-100">
        <div class="col-12 col-lg-9 p-0">
            <div class="embed-responsive embed-responsive-16by9">
                <iframe id="twitchPlayerIframe" class="embed-responsive-item" src="https://player.twitch.tv/?channel=trashmates" frameborder="0" allowfullscreen scrolling="no"></iframe>
            </div>
        </div>

        <div id="twitchChatIframe" class="d-none d-lg-block col-12 col-lg-3 p-0 flex-grow-1 h-100">
            <iframe class="embed-responsive-item" src="https://www.twitch.tv/embed/trashmates/chat?darkpopout" frameborder="0" scrolling="no" width="100%" height="100%"></iframe>
        </div>
    </div>

@endsection

@section("navbar")

        <a id="chatDisplay" class="nav-item nav-link d-block d-lg-none">Display chat</a>

@endsection

@section("scripts")

    <script>
        let $twitchChatIframe = document.querySelector(`#twitchChatIframe`)

        document.querySelector(`body`).style.background = "black"
        document.querySelector(`#chatDisplay`).addEventListener(`click`, (e) => {
            e.preventDefault()
            e.stopPropagation()

            $twitchChatIframe.classList.toggle(`d-none`)
            $twitchChatIframe.classList.toggle(`position-absolute`)
            $twitchChatIframe.style.opacity = $twitchChatIframe.style.opacity === "0.9" ? "1.0" : "0.9"
        })
    </script>

@endsection
