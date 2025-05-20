@extends('layouts.admin')

@section('title', 'Games')

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ url('admin/games/create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add Game</a>
        </div>
        <div class="card-body">
            @if(Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ Session::get('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    @php
                        Session::forget('success');
                    @endphp
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Release Date</th>
                        <th>Creator</th>
                        <th>Genre</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($games as $game)
                        <tr>
                            <td>{{ $game->id }}</td>
                            <td>{{ $game->title }}</td>
                            <td>{{ Str::limit($game->description, 50) }}</td>
                            <td>{{ $game->price ?? 'N/A' }}€</td>
                            <td>{{ $game->release_date }}</td>
                            <td>{{ $game->creator->name }}</td>
                            <td>
                                {{ $game->genres->isNotEmpty() ? implode(', ', $game->genres->pluck('name')->toArray()) : 'N/A' }}
                            </td>
                            <td>
                                <a href="{{ url('admin/games/'.$game->id.'/edit') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="{{ url('admin/games/'.$game->id) }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <form action="{{ url('admin/games/' . $game->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this game?')">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $games->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
