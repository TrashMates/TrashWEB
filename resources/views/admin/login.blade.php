@extends("admin.includes._login", ["title" => "TrashMates - Login"])

@section("content")

    <div class="center-page login-page">
        <form method="POST">

            <h4 class="title">TrashMates</h4>

            <!-- LARAVEL: CSRF Token -->
            {{ csrf_field() }}

            <input name="username" class="input" placeholder="Username" type="text">
            <input name="password" class="input" placeholder="Password" type="password">

            <button class="btn">Se connecter</button>
        </form>
    </div>

@endsection()