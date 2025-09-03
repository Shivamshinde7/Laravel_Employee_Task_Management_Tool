@extends('layouts.app')

@section('content')
<div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="card p-4 shadow w-100" style="max-width: 400px;">
        <h2 class="text-center mb-4 text-primary">Login</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div> 
            @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

        <div class="mb-3">
    <label for="login" class="form-label">Email or Username</label>
    <input type="text" name="login" id="login" class="form-control" required autofocus>
</div>


            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>

            <button type="submit" class="btn btn-primary w-100">Login</button>

            <div class="text-center mt-3">
                <small>Don't have an account? <a href="{{ route('register') }}">Register</a></small>
            </div>
        </form>
    </div>
</div>
@endsection
