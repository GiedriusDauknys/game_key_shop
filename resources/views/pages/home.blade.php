@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center mb-4">
            <div class="col-md-8 text-center">
                <p class="display-4">Explore Gametopia</p>
                <p class="lead">Your gaming realm - pick, rate, and love your adventures. Connect with gamers and developers.</p>
            </div>
        </div>


    </div>

    <div class="container py-4">

        <div class="row justify-content-center mb-4">
            <div class="col-md-4 text-center">
                <i class="fas fa-pen fa-4x mb-2 text-primary"></i>
                <h2>Review Hub</h2>
            </div>
            <div class="col-md-4 text-center">
                <i class="fas fa-list fa-4x mb-2 text-primary"></i>
                <h2>Quick Lists</h2>
            </div>
            <div class="col-md-4 text-center">
                <i class="fas fa-th fa-4x mb-2 text-primary"></i>
                <h2>Genre Sorted</h2>
            </div>
        </div>


        <div class="row justify-content-center">
            <div class="col-md-4 text-center">
                <p>Write your heart out or rate casually - your reviews fuel our community.</p>
            </div>
            <div class="col-md-4 text-center">
                <p>Save your favorites in no time and always keep them handy for quick access.</p>
            </div>
            <div class="col-md-4 text-center">
                <p>From action to puzzles - browse games sorted by types and find your next obsession.</p>
            </div>
        </div>
    </div>
    <div style="height: 100px;"></div>
@endsection
