@extends('welcome')
@section('content')
    <div class="container">
        @include('alerts.success')
        @include('alerts.error')
        <form method="POST" id="loginForm" action="{{route('register.store')}}">
            @csrf
            <h1>Register</h1>
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" aria-describedby="emailHelp" name="name" id="name">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" aria-describedby="emailHelp" name="email" id="email">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="mb-3">
                <label for="cpassword" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="cpassword" name="cpassword">
            </div>
            <button type="submit" class="btn btn-primary">Register</button>

        </form>
    </div>
@endsection
@section('scripts')
    <script>
        $('form[id="loginForm"]').validate({
            rules: {
                name : 'required',
                email: 'required',
                password: 'required',
                cpassword: {
                    required: true,
                    equalTo: "#password"
                },
            },
            messages: {
                name : 'Name is required',
                email: 'Email is required',
                password: 'Password is required',
                cpassword: {
                    required: 'Confirm password is required',
                    equalTo: 'Confirm password same as password',
                },
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    </script>
@endsection
