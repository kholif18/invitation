<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Guest;
use App\Models\Wish;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InvitationViewController extends Controller
{
    /**
     * Menampilkan undangan publik berdasarkan slug
     */
    public function show($slug, $code = null)
    {
        Log::info('Viewing invitation: ' . $slug);
        
        // Cari invitation berdasarkan slug
        $invitation = Invitation::where('slug', $slug)->firstOrFail();
        
        Log::info('Invitation found:', [
            'id' => $invitation->id,
            'status' => $invitation->status,
            'template_id' => $invitation->template_id
        ]);
        
        // Pastikan hanya invitation yang sudah dipublish yang bisa dilihat publik
        if ($invitation->status !== 'published') {
            Log::warning('Invitation not published: ' . $invitation->id);
            abort(404, 'Invitation not found or not published yet.');
        }
        
        // Load relasi yang diperlukan
        $invitation->load(['template', 'wishes']);
        
        // Cari guest berdasarkan invitation code (jika ada)
        $guest = null;
        if ($code) {
            $guest = Guest::where('invitation_code', $code)
                ->where('invitation_id', $invitation->id)
                ->first();
            
            // Tandai sebagai sudah dilihat
            if ($guest && !$guest->viewed_at) {
                $guest->markAsViewed();
            }
        }
        
        // Ambil wishes yang sudah disetujui
        $wishes = $invitation->wishes()
            ->where('is_approved', true)
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();
        
        // Dapatkan path view template
        $templateView = $invitation->getTemplateViewPathAttribute();
        
        Log::info('Template view path: ' . $templateView);
        
        // Cek apakah view template ada
        if (view()->exists($templateView)) {
            return view($templateView, compact('invitation', 'guest', 'wishes'));
        }
        
        // Fallback ke view default jika template tidak ditemukan
        Log::warning('Template view not found: ' . $templateView . ', using fallback');
        return view('invitations.fallback', compact('invitation', 'guest', 'wishes'));
    }
    
    /**
     * Menyimpan wish/ucapan dari tamu
     */
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
        
        $wish = Wish::create([
            'invitation_id' => $invitation->id,
            'guest_name' => $validated['guest_name'],
            'message' => $validated['message'],
            'attendance' => $validated['attendance'],
            'attendance_count' => $validated['attendance_count'],
            'is_approved' => true
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Ucapan berhasil dikirim',
            'data' => $wish
        ]);
    }
}
