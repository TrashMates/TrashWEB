@extends("admin.includes._master", ["title" => "TrashMates - Administration"])


@section("content")
    <div class="admin-index">
        <div class="stats">
            <canvas id="stats" height="300px"></canvas>
        </div>
    </div>
@endsection()

@section("scripts")
    <script defer src="{{ asset("js/stats.js") }}"></script>
@endsection()
