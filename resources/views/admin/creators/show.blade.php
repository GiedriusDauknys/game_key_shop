@extends('layouts.admin')

@section('title', 'Creator Details')

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ route('creators.edit', $creator) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit Creator
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <td>ID</td>
                        <td>{{ $creator->id }}</td>
                    </tr>
                    <tr>
                        <td>Name</td>
                        <td>{{ $creator->name }}</td>
                    </tr>
                    <tr>
                        <td>Games Created</td>
                        <td>
                            @if($creator->games->isNotEmpty())
                                <ul class="mb-0">
                                    @foreach($creator->games as $game)
                                        <li>{{ $game->title }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <em>No games associated</em>
                            @endif
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
