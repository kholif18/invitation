<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Guest;
use App\Models\Wish;
use Illuminate\Http\Request;

class InvitationViewController extends Controller
{
    public function show($slug, $code = null)
    {
        $invitation = Invitation::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();
        
        $guest = null;
        if ($code) {
            $guest = Guest::where('invitation_code', $code)
                ->where('invitation_id', $invitation->id)
                ->first();
            
            if ($guest && !$guest->viewed_at) {
                $guest->markAsViewed();
            }
        }
        
        $wishes = $invitation->wishes()
            ->approved()
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();
        
        return view('invitations.show', compact('invitation', 'guest', 'wishes'));
    }
    
    public function sendWish(Request $request, $slug)
    {
        $invitation = Invitation::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();
        
        $validated = $request->validate([
            'guest_name' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'attendance' => 'required|in:yes,no,maybe',
            'attendance_count' => 'required|integer|min:1|max:10'
        ]);
        
        $validated['invitation_id'] = $invitation->id;
        
        Wish::create($validated);
        
        return back()->with('success', 'Thank you for your wishes and confirmation!');
    }
}
