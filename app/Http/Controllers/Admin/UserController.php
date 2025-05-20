<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\GameKey;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function index()
    {
        $users = User::with('games')->paginate(25);
        return view('admin.users.index', compact('users'));
    }


    public function create()
    {
        $games = Game::pluck('title', 'id');
        $roles = Role::pluck('name', 'id');

        return view('admin.users.form', compact('games', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|max:32',
            'games_id' => 'nullable|exists:games,id',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);


        $roleNames = Role::whereIn('id', $request->roles)->pluck('name')->toArray();

        if (!empty($roleNames)) {
            $user->syncRoles($roleNames);
        } else {
            $user->assignRole('member');
        }


        if ($request->has('games')) {
            $games = $request->games;


            if (in_array("none", $games)) {
                $games = [];
            }


            $user->games()->sync($games);
        }

        return redirect('admin/users')->with('success', 'User created successfully.');
    }


    public function show($id)
    {
        $user = User::with('games')->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }


    public function edit($id)
    {
        $user = User::findOrFail($id);

        $selected_games = $user->games->pluck('id')->toArray();

        $games = Game::pluck('title', 'id');
        $roles = Role::pluck('name', 'id');

        $selected_roles = $user->roles->pluck('id')->toArray();

        return view('admin.users.form', compact('user', 'selected_games', 'games', 'roles', 'selected_roles'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'games_id' => 'nullable|exists:games,id',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
            'password' => 'nullable|string|min:8|max:32',
        ]);

        $user = User::findOrFail($id);


        $data = $request->only(['name', 'email']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }


        $user->update($data);


        $roleNames = Role::whereIn('id', $request->roles)->pluck('name')->toArray();
        $user->syncRoles($roleNames);


        if ($request->has('games')) {
            $games = $request->games;


            if (in_array("none", $games)) {
                $games = [];
            }


            $user->games()->sync($games);
        }
        $user->roles()->sync($request->roles);

        return redirect('admin/users')->with('success', 'User updated successfully.');
    }


    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->games()->detach();
        $user->delete();

        return redirect('admin/users')->with('success', 'User deleted successfully.');
    }


}
