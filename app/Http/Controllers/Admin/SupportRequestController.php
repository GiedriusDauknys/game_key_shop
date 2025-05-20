<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportRequest;
use App\Models\User;
use Illuminate\Http\Request;

class SupportRequestController extends Controller
{
    public function index()
    {
        $requests = SupportRequest::with('user')->orderBy('created_at', 'desc')->paginate(25);
        return view('admin.support.index', compact('requests'));
    }

    public function show($id)
    {
        $request = SupportRequest::with('user')->findOrFail($id);
        return view('admin.support.show', compact('request'));
    }

    public function edit($id)
    {
        $request = SupportRequest::with('user')->findOrFail($id);

        // Fetch users for the dropdown
        $users = User::pluck('name', 'id')->all();

        return view('admin.support.form', compact('request', 'users'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'response' => 'nullable|string',
            'status' => 'required|in:open,resolved',
        ]);

        $support = SupportRequest::findOrFail($id);
        $support->update($data);

        return redirect()->route('admin.support.index')->with('success', 'Support request updated.');
    }

    public function destroy($id)
    {
        $support = SupportRequest::findOrFail($id);
        $support->delete();

        return redirect()->route('admin.support.index')->with('success', 'Support request deleted.');
    }
}
