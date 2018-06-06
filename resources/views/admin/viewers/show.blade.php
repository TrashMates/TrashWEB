@extends("admin.includes._master", ["title" => "TrashMates - Viewers List"])

@section("content")
    <div class="admin-table">
        <h2>{{ $title }}</h2>

        <h5>All Events</h5>
        @foreach($Viewer->events as $Event)
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

        <h5>All Messages</h5>
        @foreach($Viewer->messages as $Message)
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

    </div>
@endsection()