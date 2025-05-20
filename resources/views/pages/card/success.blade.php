@extends('layouts.app')

@section('content')
    <div class="container text-center py-5">
        <h2>Payment Successful!</h2>
        <p>Thank you for your purchase!</p>
        <p>You can find your game key in the <strong>My Keys</strong> section.</p>

        <div class="mt-4 d-flex justify-content-center gap-3">
            <a href="{{ route('home') }}" class="btn btn-primary">Return to Home</a>
            <a href="{{ route('user.keys') }}" class="btn btn-success">View My Keys</a>
        </div>
    </div>
@endsection
