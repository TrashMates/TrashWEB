@extends("admin.includes._master", ["title" => "TrashMates - Viewers List"])

@section("content")
    <div class="admin-table">
        <h2>{{ $title }}</h2>

        @foreach($Viewers as $Viewer)
            <div class="event toggleable">
                <div class="infos">
                    @if ($Viewer->role == "Viewer" || $Viewer->role == "@everyone")
                        <div class="type"><i class="fas fa-user"></i></div>
                    @elseif ($Viewer->role == "Follower")
                        <div class="type"><i class="fas fa-heart"></i></div>
                    @elseif ($Viewer->role == "Subscriber")
                        <div class="type"><i class="fas fa-star"></i></div>
                    @elseif ($Viewer->role == "Moderator" || $Viewer->role == "Streamer")
                        <div class="type"><i class="fas fa-crown"></i></div>
                    @else
                        <div class="type"><i class="fas fa-question"></i></div>
                    @endif
                    <div class="username">
                        {{ $Viewer->username }}
                        @if ($Viewer->discriminator)
                            {{ "#" . $Viewer->discriminator }}
                        @endif
                    </div>
                    <div class="date">{{ Carbon\Carbon::parse($Viewer->created_at)->diffForHumans() }}</div>
                </div>
            </div>
        @endforeach

    <!-- PAGINATION -->
    @include("admin.includes.pagination", ["count" => $count, "page" => $page])
@endsection()