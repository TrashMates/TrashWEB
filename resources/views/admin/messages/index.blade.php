@extends("admin.includes._master", ["title" => "TrashMates - Messages List"])

@section("content")
    <div class="admin-table">
        <h2>{{ $title }}</h2>

        @foreach($Messages as $Message)
            <div class="event">
                <div class="infos toggleable">
                    @if($Message->viewer->role == "Follower")
                        <div class="type"><i class="fas fa-heart"></i></div>
                    @elseif($Message->type == "Subscriber")
                        <div class="type"><i class="fas fa-star"></i></div>
                    @elseif($Message->type == "Moderator" || $Message->type == "Streamer")
                        <div class="type"><i class="fas fa-crown"></i></div>
                    @else
                        <div class="type"><i class="fas fa-user"></i></div>
                    @endif
                    <div class="username">
                        {{ $Message->viewer->username }}
                        @if ($Message->viewer->discriminator)
                            {{ "#" . $Message->viewer->discriminator }}
                        @endif
                    </div>
                    <div class="date">{{ Carbon\Carbon::parse($Message->created_at)->diffForHumans() }}</div>
                </div>
                <div class="content">
                    {{ $Message->content }}
                </div>
            </div>
        @endforeach

        <!-- PAGINATION -->
        @include("admin.includes.pagination", ["count" => $count, "page" => $page])
    </div>
@endsection()