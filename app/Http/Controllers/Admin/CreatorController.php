<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Creator;
use Illuminate\Http\Request;

class CreatorController extends Controller
{
    public function index()
    {
        $creators = Creator::paginate(25);
        return view('admin.creators.index', compact('creators'));
    }

    public function create()
    {
        return view('admin.creators.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        Creator::create([
            'name' => $request->name
        ]);

        return redirect('admin/creators')->with('success', 'Creator added successfully.');
    }

    public function edit(Creator $creator)
    {
        return view('admin.creators.form', compact('creator'));
    }

    public function update(Request $request, Creator $creator)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $creator->update([
            'name' => $request->name
        ]);

        return redirect('admin/creators')->with('success', 'Creator added successfully.');
    }

    public function destroy(Creator $creator)
    {
        $creator->delete();
        return redirect('admin/creators')->with('success', 'Creator deleted successfully.');
    }
}
