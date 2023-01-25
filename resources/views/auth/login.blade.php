@extends('welcome')
@section('content')
    <div class="container">
        @include('alerts.success')
        @include('alerts.error')
        <form method="POST" id="loginForm" action="{{route('login.attempt')}}">
            @csrf
            <h1>Login</h1>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email address</label>
                <input type="email" class="form-control" aria-describedby="emailHelp" name="email" id="email">
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <button type="submit" class="btn btn-primary">LogIn</button>
        </form>
    </div>
@endsection
@section('scripts')
    <script>
        $('form[id="loginForm"]').validate({
            rules: {
                email: 'required',
                password: 'required',
            },
            messages: {
                email: 'email is required',
                password: 'password is required',
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    </script>
@endsection
