<?php
namespace App\Http\Controllers;

use App\Models\SupportRequest;
use Illuminate\Http\Request;

class SupportRequestUserController extends Controller
{
    public function index()
    {
        $requests = SupportRequest::where('user_id', auth()->id())->latest()->paginate(10);
        return view('support.index', compact('requests'));
    }

    public function create()
    {
        return view('support.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        SupportRequest::create([
            'user_id' => auth()->id(),
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        return redirect()->route('support.index')->with('success', 'Your request has been submitted!');
    }

    public function show($id)
    {
        $request = SupportRequest::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('support.show', compact('request'));
    }

    public function edit($id)
    {
        $request = SupportRequest::where('user_id', auth()->id())->findOrFail($id);
        return view('support.edit', compact('request'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $support = SupportRequest::where('user_id', auth()->id())->findOrFail($id);
        $support->update($data);

        return redirect()->route('support.index')->with('success', 'Request updated successfully.');
    }
    public function destroy($id)
    {
        $request = SupportRequest::where('user_id', auth()->id())
            ->where('status', 'open')
            ->findOrFail($id);

        $request->delete();

        return redirect()->route('support.index')->with('success', 'Request deleted successfully.');
    }
}
