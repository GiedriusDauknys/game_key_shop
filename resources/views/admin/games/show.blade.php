@extends('layouts.admin')

@section('title', 'Game Details')

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ url('/admin/games/'.$game->id.'/edit') }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit Game
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <td>ID</td>
                        <td>{{ $game->id }}</td>
                    </tr>
                    <tr>
                        <td>Title</td>
                        <td>{{ $game->title }}</td>
                    </tr>
                    <tr>
                        <td>Description</td>
                        <td>{{ $game->description }}</td>
                    </tr>
                    <tr>
                        <td>Price</td>
                        <td>{{ number_format($game->price, 2) }}€</td>
                    </tr>
                    <tr>
                        <td>Release Date</td>
                        <td>{{ $game->release_date }}</td>
                    </tr>
                    <tr>
                        <td>Creator</td>
                        <td>{{ $game->creator->name }}</td>
                    </tr>
                    <tr>
                        <td>Genre</td>
                        <td>{{ $game->genres->isNotEmpty() ? implode(', ', $game->genres->pluck('name')->toArray()) : 'No genres' }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
