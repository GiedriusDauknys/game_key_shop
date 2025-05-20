@extends('layouts.admin')

@section('title', isset($creator) ? 'Edit Creator' : 'Create Creator')

@section('content')
    <div class="card">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">
                {{ isset($creator) ? 'Edit Creator' : 'Create New Creator' }}
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

            <form action="{{ isset($creator) ? route('creators.update', $creator) : route('creators.store') }}" method="POST">
                @csrf
                @isset($creator)
                    @method('PUT')
                @endisset

                <div class="form-group">
                    <label for="name">Creator Name:</label>
                    <input type="text" name="name" id="name" class="form-control"
                           value="{{ old('name', $creator->name ?? '') }}" required>
                </div>

                <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection
