<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    public function index()
    {
        $invitations = Invitation::latest()->paginate(10);
        return view('admin.invitations.index', compact('invitations'));
    }

    public function create()
    {
        return view('admin.invitations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'time' => 'nullable',
            'location' => 'required|string',
            'theme' => 'nullable|string',
        ]);

        $invitation = Invitation::create([
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'time' => $request->time,
            'location' => $request->location,
            'theme' => $request->theme,
            'created_by' => auth()->id(),
            'link' => Str::random(20) // token unik
        ]);

        return redirect()->route('invitations.index')->with('success', 'Invitation created successfully!');
    }

    public function edit(Invitation $invitation)
    {
        return view('admin.invitations.edit', compact('invitation'));
    }

    public function update(Request $request, Invitation $invitation)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'time' => 'nullable',
            'location' => 'required|string',
            'theme' => 'nullable|string',
        ]);

        $invitation->update($request->all());

        return redirect()->route('invitations.index')->with('success', 'Invitation updated successfully!');
    }

    public function destroy(Invitation $invitation)
    {
        $invitation->delete();
        return redirect()->route('invitations.index')->with('success', 'Invitation deleted successfully!');
    }
}
