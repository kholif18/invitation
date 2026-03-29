<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Invitation;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function indexByInvitation(Invitation $invitation)
    {
        $guests = $invitation->guests()->paginate(20);
        return view('admin.guests.index', compact('invitation', 'guests'));
    }

    // RSVP admin (optional, bisa edit status manual)
    public function update(Request $request, Guest $guest)
    {
        $request->validate([
            'status' => 'required|in:pending,hadir,tidak hadir',
            'message' => 'nullable|string'
        ]);

        $guest->update([
            'status' => $request->status,
            'message' => $request->message
        ]);

        return back()->with('success', 'Guest updated successfully!');
    }

    // Halaman tamu akses link undangan (guest view)
    public function showByLink($link)
    {
        $invitation = Invitation::where('link', $link)->firstOrFail();
        $guests = $invitation->guests;
        return view('guest.invitation', compact('invitation', 'guests'));
    }

    public function rsvp(Request $request, $link)
    {
        $invitation = Invitation::where('link', $link)->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:hadir,tidak hadir',
            'message' => 'nullable|string',
        ]);

        $invitation->guests()->updateOrCreate(
            ['name' => $request->name],
            ['status' => $request->status, 'message' => $request->message]
        );

        return redirect()->back()->with('success', 'Thank you for your response!');
    }
}
