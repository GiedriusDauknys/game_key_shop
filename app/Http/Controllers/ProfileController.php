<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('pages.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $changes = [];


        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => ['nullable', 'min:6', 'confirmed'],
            'old_password' => $request->filled('password') ? 'required' : '',
        ], [
            'password.confirmed' => 'Passwords do not match.',
        ]);


        if ($request->name !== $user->name) {
            $changes[] = 'Username';
            $user->name = $request->name;
        }

        if ($request->email !== $user->email) {
            $changes[] = 'Email';
            $user->email = $request->email;
        }

        if ($request->filled('password')) {
            if (!Hash::check($request->old_password, $user->password)) {
                return back()->withErrors(['old_password' => 'Incorrect current password.']);
            }
            $changes[] = 'Password';
            $user->password = Hash::make($request->password);
        }


        if (!empty($changes)) {
            $user->save();
            $message = implode(', ', $changes) . ' updated successfully.';
            return redirect()->back()->with('success', $message);
        }

        return redirect()->back()->with('info', 'No changes were made.');
    }
}
