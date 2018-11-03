@extends("admin.includes._master", ["title" => "TrashMates - Stalker Tool"])

@section("content")

<div class="tools">
    <input id="channel" type="text" class="input" placeholder="Search a Channel on Twitch">

    <div id="informations" class="informations"></div>
</div>

@endsection


@section("scripts")
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script defer src="{{ asset("js/tools/stalker.js") }}"></script>
@endsection