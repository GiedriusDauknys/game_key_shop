@extends('layouts.admin')

@section('title', isset($user) ? 'Edit User' : 'Create User')

@section('content')
    <div class="card">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">
                {{ isset($user) ? 'Edit existing user' : 'Create new user' }}
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

            <form action="{{ isset($user) ? url('admin/users/' . $user->id) : url('admin/users') }}" method="POST">
                @csrf
                @isset($user)
                    @method('PATCH')
                @endisset

                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" class="form-control"
                           value="{{ old('name', $user->name ?? '') }}" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" class="form-control"
                           value="{{ old('email', $user->email ?? '') }}" required>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" class="form-control"
                        {{ isset($user) ? '' : 'required' }}>
                    @if(isset($user))
                        <small class="form-text text-muted">Leave blank to keep current password.</small>
                    @endif
                </div>

                <div class="form-group">
                    <label for="roles">Roles:</label>
                    <select name="roles[]" id="roles" class="form-control" multiple required>
                        @foreach($roles as $id => $name)
                            <option value="{{ $id }}" {{ in_array($id, old('roles', $selected_roles ?? [])) ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="games">Favorite Games:</label>
                    <select name="games[]" id="games" class="form-control" multiple>
                        <option value="none" {{ empty($selected_games) ? 'selected' : '' }}>No Game</option>
                        @foreach($games as $id => $title)
                            <option value="{{ $id }}" {{ in_array($id, old('games', $selected_games ?? [])) ? 'selected' : '' }}>
                                {{ $title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        const gamesSelect = document.getElementById("games");

                        gamesSelect.addEventListener("change", function () {
                            let selectedOptions = Array.from(gamesSelect.selectedOptions).map(option => option.value);

                            if (selectedOptions.includes("none")) {

                                gamesSelect.querySelectorAll("option").forEach(option => {
                                    if (option.value !== "none") {
                                        option.selected = false;
                                    }
                                });
                            } else {

                                gamesSelect.querySelector("option[value='none']").selected = false;
                            }
                        });
                    });
                </script>

                <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>

            </form>
        </div>
    </div>
@endsection
