@extends('layouts.app')

@section('title', 'Profile Settings')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center">Profile Settings</h2>


                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif


                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}" id="profileForm" class="p-4 border rounded bg-light">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label for="name" class="form-label">Username</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}">
                        @error('name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}">
                        @error('name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" name="password" id="password" class="form-control">
                        @error('name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                        @error('name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" id="saveChangesBtn" class="btn btn-primary w-100">Save Changes</button>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="passwordModalLabel">Confirm Your Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="old_password" class="form-label">Enter Your Current Password:</label>
                    <input type="password" name="old_password" id="old_password" class="form-control">
                    <div id="old_password_error" class="text-danger d-none">Incorrect password.</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="confirmPasswordBtn" class="btn btn-primary">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <div style="height: 100px;"></div>
@endsection
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById("profileForm");
        const saveChangesBtn = document.getElementById("saveChangesBtn");
        const confirmPasswordBtn = document.getElementById("confirmPasswordBtn");
        const modal = new bootstrap.Modal(document.getElementById("passwordModal"));

        let isPasswordChanged = false;


        document.querySelector("input[name='password']").addEventListener("input", function () {
            isPasswordChanged = this.value.length > 0;
        });

        document.querySelector("input[name='password_confirmation']").addEventListener("input", function () {
            isPasswordChanged = document.querySelector("input[name='password']").value.length > 0;
        });


        saveChangesBtn.addEventListener("click", function (event) {
            if (isPasswordChanged) {
                event.preventDefault();
                modal.show();
            } else {
                form.submit();
            }
        });


        confirmPasswordBtn.addEventListener("click", function () {
            let oldPasswordInput = document.getElementById("old_password").value;

            if (!oldPasswordInput) {
                document.getElementById("old_password_error").classList.remove("d-none");
                return;
            }

            let hiddenInput = document.createElement("input");
            hiddenInput.type = "hidden";
            hiddenInput.name = "old_password";
            hiddenInput.value = oldPasswordInput;
            form.appendChild(hiddenInput);

            modal.hide();
            form.submit();
        });
    });
</script>
