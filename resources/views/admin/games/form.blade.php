@extends('layouts.admin')

@section('title', isset($game) ? 'Edit Game' : 'Create Game')

@section('content')
    <div class="card">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">
                {{ isset($game) ? 'Edit existing game' : 'Create new game' }}
            </h6>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ isset($game) ? url('admin/games/' . $game->id) : url('admin/games') }}"
                  method="POST">
                @csrf
                @isset($game)
                    @method('PATCH')
                @endisset

                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" name="title" id="title" class="form-control"
                           value="{{ old('title', $game->title ?? '') }}" required>
                </div>

                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea name="description" id="description" class="form-control">{{ old('description', $game->description ?? '') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="price">Price (€):</label>
                    <input type="number" name="price" id="price" class="form-control" step="0.01"
                           value="{{ old('price', $game->price ?? '') }}" required>
                </div>

                <div class="form-group">
                    <label for="release_date">Release Date:</label>
                    <input type="date" name="release_date" id="release_date" class="form-control"
                           value="{{ old('release_date', $game->release_date ?? '') }}" required>
                </div>

                <div class="form-group">
                    <label for="creator_id">Creator:</label>
                    <select name="creator_id" id="creator_id" class="form-control" required>
                        @foreach($creators as $id => $name)
                            <option value="{{ $id }}" {{ old('creator_id', $game->creator_id ?? '') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="genres">Genres:</label>
                    <select name="genres[]" id="genres" class="form-control" multiple required>
                        @foreach($genres as $id => $name)
                            <option value="{{ $id }}" {{ in_array($id, old('genres', $selected_genres ?? [])) ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>

            </form>
        </div>
    </div>
@endsection
