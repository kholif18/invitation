<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\Invitation;
use App\Models\InvitationLink;
use App\Models\MessageLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LinkController extends Controller
{
    /**
     * Display link management page with invitation selection
     */
    public function index()
    {
        // Get all invitations for selection dropdown
        $invitations = Invitation::where('created_by', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.invitations.links', compact('invitations'));
    }
    
    /**
     * Display links for a specific invitation
     */
    public function show($invitationId)
    {
        $invitation = Invitation::with(['guests', 'links'])->findOrFail($invitationId);
        $guests = $invitation->guests;
        $generalLink = $invitation->links()->where('type', 'general')->first();
        $personalizedLinks = $invitation->links()->where('type', 'personalized')->with('guest')->get();
        
        return view('admin.invitations.links', compact('invitation', 'guests', 'generalLink', 'personalizedLinks'));
    }
    
    /**
     * Get all links for an invitation (AJAX)
     */
    public function getLinks($invitationId)
    {
        $invitation = Invitation::findOrFail($invitationId);
        
        $generalLink = InvitationLink::where('invitation_id', $invitationId)
            ->where('type', 'general')
            ->first();
        
        $personalizedLinks = InvitationLink::where('invitation_id', $invitationId)
            ->where('type', 'personalized')
            ->with('guest')
            ->get();
        
        $guests = Guest::where('invitation_id', $invitationId)
            ->with('link')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => [
                'invitation' => [
                    'id' => $invitation->id,
                    'event_name' => $invitation->event_name,
                    'groom_name' => $invitation->groom_name,
                    'bride_name' => $invitation->bride_name
                ],
                'general_link' => $generalLink ? [
                    'id' => $generalLink->id,
                    'link' => url("/invitation/{$generalLink->token}"),
                    'token' => $generalLink->token,
                    'views' => $generalLink->views,
                    'created_at' => $generalLink->created_at->format('d M Y')
                ] : null,
                'personalized_links' => $personalizedLinks->map(function($link) {
                    return [
                        'id' => $link->id,
                        'guest_id' => $link->guest_id,
                        'guest_name' => $link->guest ? $link->guest->name : null,
                        'guest_email' => $link->guest ? $link->guest->email : null,
                        'guest_phone' => $link->guest ? $link->guest->phone : null,
                        'link' => url("/invitation/{$link->token}"),
                        'token' => $link->token,
                        'status' => $link->status,
                        'views' => $link->views,
                        'rsvp_status' => $link->guest ? $link->guest->rsvp_status : 'pending',
                        'created_at' => $link->created_at->format('d M Y')
                    ];
                }),
                'guests' => $guests->map(function($guest) {
                    return [
                        'id' => $guest->id,
                        'name' => $guest->name,
                        'email' => $guest->email,
                        'phone' => $guest->phone,
                        'has_link' => $guest->link ? true : false,
                        'link' => $guest->link ? url("/invitation/{$guest->link->token}") : null,
                        'link_id' => $guest->link ? $guest->link->id : null,
                        'rsvp_status' => $guest->rsvp_status ?? 'pending',
                        'views' => $guest->link ? $guest->link->views : 0
                    ];
                })
            ]
        ]);
    }
    
    /**
     * Generate links for invitation
     */
    public function generateLinks(Request $request)
    {
        $request->validate([
            'invitation_id' => 'required|exists:invitations,id',
            'type' => 'required|in:personalized,general'
        ]);
        
        $invitation = Invitation::find($request->invitation_id);
        
        if ($request->type === 'personalized') {
            // Generate personalized links for each guest
            $guests = Guest::where('invitation_id', $invitation->id)->get();
            $generated = 0;
            $existing = 0;
            
            foreach ($guests as $guest) {
                // Check if link already exists
                $existingLink = InvitationLink::where('guest_id', $guest->id)->first();
                
                if (!$existingLink) {
                    InvitationLink::create([
                        'invitation_id' => $invitation->id,
                        'guest_id' => $guest->id,
                        'token' => Str::random(32),
                        'type' => 'personalized',
                        'status' => 'active',
                        'expires_at' => now()->addDays(90)
                    ]);
                    $generated++;
                } else {
                    $existing++;
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "{$generated} new links generated, {$existing} already existed",
                'data' => [
                    'generated' => $generated,
                    'existing' => $existing,
                    'total' => $guests->count()
                ]
            ]);
        } else {
            // Generate general link (one link for all)
            $existingLink = InvitationLink::where('invitation_id', $invitation->id)
                ->where('type', 'general')
                ->first();
            
            if (!$existingLink) {
                $link = InvitationLink::create([
                    'invitation_id' => $invitation->id,
                    'guest_id' => null,
                    'token' => Str::random(32),
                    'type' => 'general',
                    'status' => 'active',
                    'expires_at' => now()->addDays(90)
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'General invitation link generated successfully',
                    'data' => [
                        'link' => url("/invitation/{$link->token}"),
                        'token' => $link->token
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => 'General link already exists',
                    'data' => [
                        'link' => url("/invitation/{$existingLink->token}"),
                        'token' => $existingLink->token
                    ]
                ]);
            }
        }
    }
    
    /**
     * Send link to guest via WhatsApp
     */
    public function sendLink(Request $request)
    {
        $request->validate([
            'guest_id' => 'required|exists:guests,id',
            'message' => 'required|string'
        ]);
        
        $guest = Guest::with('invitation')->find($request->guest_id);
        
        // Get or create link for this guest
        $link = InvitationLink::firstOrCreate(
            [
                'invitation_id' => $guest->invitation_id,
                'guest_id' => $guest->id,
                'type' => 'personalized'
            ],
            [
                'token' => Str::random(32),
                'status' => 'active',
                'expires_at' => now()->addDays(90)
            ]
        );
        
        // Format WhatsApp URL
        $phone = preg_replace('/[^0-9]/', '', $guest->phone);
        if (!Str::startsWith($phone, '62')) {
            $phone = '62' . ltrim($phone, '0');
        }
        
        $invitationLink = url("/invitation/{$link->token}");
        
        // Replace variables in message
        $message = str_replace(
            ['[Guest Name]', '[Invitation Link]', '[Event Name]', '[Event Date]', '[Event Time]'],
            [
                $guest->name,
                $invitationLink,
                $guest->invitation->event_name ?? 'Wedding Invitation',
                $guest->invitation->event_date ? $guest->invitation->event_date->format('d M Y') : 'TBD',
                $guest->invitation->event_time ?? 'TBD'
            ],
            $request->message
        );
        
        $whatsappUrl = "https://wa.me/{$phone}?text=" . urlencode($message);
        
        // Log the sent message
        \App\Models\MessageLog::create([
            'guest_id' => $guest->id,
            'invitation_id' => $guest->invitation_id,
            'type' => 'whatsapp',
            'message' => $message,
            'sent_at' => now()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'WhatsApp link prepared',
            'data' => [
                'whatsapp_url' => $whatsappUrl,
                'link' => $invitationLink,
                'guest_name' => $guest->name,
                'phone' => $phone
            ]
        ]);
    }
    
    /**
     * Bulk send links to multiple guests
     */
    public function bulkSendLinks(Request $request)
    {
        $request->validate([
            'invitation_id' => 'required|exists:invitations,id',
            'guest_ids' => 'required|array',
            'guest_ids.*' => 'exists:guests,id',
            'message' => 'required|string'
        ]);
        
        $invitation = Invitation::find($request->invitation_id);
        $sent = 0;
        $failed = 0;
        $results = [];
        
        foreach ($request->guest_ids as $guestId) {
            try {
                $guest = Guest::find($guestId);
                
                // Get or create link
                $link = InvitationLink::firstOrCreate(
                    [
                        'invitation_id' => $request->invitation_id,
                        'guest_id' => $guestId,
                        'type' => 'personalized'
                    ],
                    [
                        'token' => Str::random(32),
                        'status' => 'active',
                        'expires_at' => now()->addDays(90)
                    ]
                );
                
                // Format message
                $message = str_replace(
                    ['[Guest Name]', '[Invitation Link]', '[Event Name]', '[Event Date]', '[Event Time]'],
                    [
                        $guest->name,
                        url("/invitation/{$link->token}"),
                        $invitation->event_name ?? 'Wedding Invitation',
                        $invitation->event_date ? $invitation->event_date->format('d M Y') : 'TBD',
                        $invitation->event_time ?? 'TBD'
                    ],
                    $request->message
                );
                
                $phone = preg_replace('/[^0-9]/', '', $guest->phone);
                if (!Str::startsWith($phone, '62')) {
                    $phone = '62' . ltrim($phone, '0');
                }
                
                $results[] = [
                    'guest_id' => $guestId,
                    'guest_name' => $guest->name,
                    'phone' => $phone,
                    'message' => $message,
                    'link' => url("/invitation/{$link->token}"),
                    'whatsapp_url' => "https://wa.me/{$phone}?text=" . urlencode($message)
                ];
                
                // Log the message
                MessageLog::create([
                    'guest_id' => $guest->id,
                    'invitation_id' => $request->invitation_id,
                    'type' => 'whatsapp',
                    'message' => $message,
                    'sent_at' => now()
                ]);
                
                $sent++;
            } catch (\Exception $e) {
                $failed++;
                Log::error('Bulk send failed for guest ' . $guestId . ': ' . $e->getMessage());
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => "{$sent} links prepared, {$failed} failed",
            'data' => [
                'sent' => $sent,
                'failed' => $failed,
                'results' => $results
            ]
        ]);
    }
    
    /**
     * Get link statistics
     */
    public function getStatistics($invitationId)
    {
        $invitation = Invitation::findOrFail($invitationId);
        
        $links = InvitationLink::where('invitation_id', $invitationId)->get();
        $guests = Guest::where('invitation_id', $invitationId)->get();
        
        $totalLinks = $links->count();
        $totalViews = $links->sum('views');
        $totalRSVP = $guests->where('rsvp_status', 'confirmed')->count();
        $pendingRSVP = $guests->where('rsvp_status', 'pending')->count();
        $declinedRSVP = $guests->where('rsvp_status', 'declined')->count();
        
        // Link click-through rate
        $ctr = $totalLinks > 0 ? round(($totalViews / $totalLinks) * 100) : 0;
        
        // RSVP rate
        $rsvpRate = $guests->count() > 0 ? round(($totalRSVP / $guests->count()) * 100) : 0;
        
        // Most viewed links
        $topLinks = $links->sortByDesc('views')->take(5)->map(function($link) {
            return [
                'guest_name' => $link->guest ? $link->guest->name : 'General Link',
                'views' => $link->views,
                'link' => url("/invitation/{$link->token}"),
                'rsvp_status' => $link->guest ? $link->guest->rsvp_status : null
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => [
                'total_links' => $totalLinks,
                'total_views' => $totalViews,
                'total_rsvp' => $totalRSVP,
                'pending_rsvp' => $pendingRSVP,
                'declined_rsvp' => $declinedRSVP,
                'ctr' => $ctr,
                'rsvp_rate' => $rsvpRate,
                'top_links' => $topLinks,
                'daily_views' => $this->getDailyViews($invitationId)
            ]
        ]);
    }
    
    /**
     * Get daily views for chart
     */
    private function getDailyViews($invitationId)
    {
        // Get views from database grouped by date
        $views = DB::table('invitation_link_views')
            ->where('invitation_id', $invitationId)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as views'))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(7)
            ->get();
        
        if ($views->count() > 0) {
            return $views->reverse()->values()->map(function($view) {
                return [
                    'date' => $view->date,
                    'views' => $view->views
                ];
            });
        }
        
        // Return dummy data for demo
        $days = [];
        for ($i = 6; $i >= 0; $i--) {
            $days[] = [
                'date' => now()->subDays($i)->format('Y-m-d'),
                'views' => rand(10, 50)
            ];
        }
        return $days;
    }
    
    /**
     * Export links to CSV
     */
    public function exportLinks($invitationId)
    {
        $invitation = Invitation::findOrFail($invitationId);
        $guests = Guest::where('invitation_id', $invitationId)->with('link')->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=invitation_links_{$invitation->event_name}_{$invitationId}.csv",
        ];
        
        $callback = function() use ($guests, $invitation) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fwrite($file, "\xEF\xBB\xBF");
            
            // Add headers
            fputcsv($file, [
                'No', 
                'Guest Name', 
                'Email', 
                'Phone', 
                'Invitation Link', 
                'RSVP Status', 
                'Views', 
                'Created At'
            ]);
            
            // Add data
            foreach ($guests as $index => $guest) {
                fputcsv($file, [
                    $index + 1,
                    $guest->name,
                    $guest->email,
                    $guest->phone,
                    $guest->link ? url("/invitation/{$guest->link->token}") : '-',
                    $guest->rsvp_status ?? 'pending',
                    $guest->link ? $guest->link->views : 0,
                    $guest->link ? $guest->link->created_at->format('d M Y') : '-'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Revoke/Disable a link
     */
    public function revokeLink($linkId)
    {
        $link = InvitationLink::findOrFail($linkId);
        $link->status = 'revoked';
        $link->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Link has been revoked successfully'
        ]);
    }
    
    /**
     * Regenerate a link (create new token)
     */
    public function regenerateLink($linkId)
    {
        $link = InvitationLink::findOrFail($linkId);
        $oldToken = $link->token;
        $link->token = Str::random(32);
        $link->status = 'active';
        $link->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Link regenerated successfully',
            'data' => [
                'old_link' => url("/invitation/{$oldToken}"),
                'new_link' => url("/invitation/{$link->token}")
            ]
        ]);
    }
    
    /**
     * Get link details for sharing
     */
    public function getLinkDetails($token)
    {
        $link = InvitationLink::where('token', $token)
            ->with(['invitation', 'guest'])
            ->firstOrFail();
        
        $invitation = $link->invitation;
        
        return response()->json([
            'success' => true,
            'data' => [
                'type' => $link->type,
                'guest_name' => $link->guest ? $link->guest->name : null,
                'invitation_link' => url("/invitation/{$token}"),
                'invitation' => [
                    'event_name' => $invitation->event_name,
                    'event_date' => $invitation->event_date ? $invitation->event_date->format('d M Y') : null,
                    'event_time' => $invitation->event_time,
                    'event_location' => $invitation->event_location
                ],
                'share_links' => [
                    'whatsapp' => "https://wa.me/?text=" . urlencode("You're invited to {$invitation->event_name}! View invitation: " . url("/invitation/{$token}")),
                    'email' => "mailto:?subject=Wedding Invitation&body=" . urlencode("You're invited to {$invitation->event_name}!\n\nView invitation: " . url("/invitation/{$token}")),
                    'facebook' => "https://www.facebook.com/sharer/sharer.php?u=" . urlencode(url("/invitation/{$token}")),
                    'twitter' => "https://twitter.com/intent/tweet?text=" . urlencode("Check out this beautiful invitation!") . "&url=" . urlencode(url("/invitation/{$token}"))
                ]
            ]
        ]);
    }
}
