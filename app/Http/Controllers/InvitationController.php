<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvitationController extends Controller
{
    /**
     * Display a listing of all invitations.
     */
    public function index(Request $request)
    {
        // Data dummy untuk invitations
        $invitations = $this->getDummyInvitations();
        
        // Filter berdasarkan status jika ada
        if ($request->has('status') && $request->status) {
            $invitations = array_filter($invitations, function($invitation) use ($request) {
                return $invitation['status'] === $request->status;
            });
        }
        
        // Filter berdasarkan event type jika ada
        if ($request->has('event_type') && $request->event_type) {
            $invitations = array_filter($invitations, function($invitation) use ($request) {
                return strtolower($invitation['event_type']) === strtolower($request->event_type);
            });
        }
        
        // Search berdasarkan event name
        if ($request->has('search') && $request->search) {
            $search = strtolower($request->search);
            $invitations = array_filter($invitations, function($invitation) use ($search) {
                return strpos(strtolower($invitation['event_name']), $search) !== false ||
                       strpos(strtolower($invitation['invitation_code']), $search) !== false;
            });
        }
        
        // Pagination manual untuk array
        $perPage = 10;
        $currentPage = $request->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $totalItems = count($invitations);
        $totalPages = ceil($totalItems / $perPage);
        
        $paginatedInvitations = array_slice($invitations, $offset, $perPage);
        
        return view('admin.invitations.index', compact('paginatedInvitations', 'totalItems', 'currentPage', 'totalPages'));
    }
    
    /**
     * Show the form for creating a new invitation.
     */
    public function create(Request $request)
    {
        $selectedTemplate = $request->get('template', null);
        
        // Data dummy untuk event types
        $eventTypes = [
            'wedding' => 'Wedding',
            'birthday' => 'Birthday Party',
            'corporate' => 'Corporate Event',
            'graduation' => 'Graduation Ceremony',
            'anniversary' => 'Anniversary',
            'baby_shower' => 'Baby Shower'
        ];
        
        // Data dummy untuk templates
        $templates = $this->getDummyTemplates();
        
        return view('admin.invitations.create', compact('eventTypes', 'templates', 'selectedTemplate'));
    }
    
    /**
     * Store a newly created invitation in storage.
     */
    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'event_name' => 'required|string|max:255',
            'event_type' => 'required|string',
            'event_date' => 'required|date',
            'event_location' => 'required|string|max:500',
            'description' => 'nullable|string',
            'message' => 'nullable|string',
            'rsvp_instructions' => 'nullable|string',
            'template_id' => 'nullable|string',
            'guests' => 'nullable|array',
            'send_email' => 'boolean',
            'send_sms' => 'boolean',
            'allow_plus_one' => 'boolean'
        ]);
        
        // Simpan ke database (dummy response)
        $invitationId = rand(1000, 9999);
        
        // Jika send email true, kirim email ke guests
        if ($request->has('send_email') && $request->send_email && !empty($request->guests)) {
            // Log pengiriman email (dummy)
            Log::info('Sending invitation emails to: ' . implode(', ', $request->guests));
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Invitation created successfully',
            'invitation_id' => $invitationId,
            'redirect_url' => route('invitations.index')
        ]);
    }
    
    /**
     * Display the specified invitation.
     */
    public function show($id)
    {
        // Data dummy untuk invitation detail
        $invitation = $this->getInvitationDetail($id);
        
        if (!$invitation) {
            abort(404, 'Invitation not found');
        }
        
        return view('admin.invitations.show', compact('invitation'));
    }
    
    /**
     * Show the form for editing the specified invitation.
     */
    public function edit($id)
    {
        // Data dummy untuk invitation yang akan diedit
        $invitation = $this->getInvitationDetail($id);
        
        if (!$invitation) {
            abort(404, 'Invitation not found');
        }
        
        $eventTypes = [
            'wedding' => 'Wedding',
            'birthday' => 'Birthday Party',
            'corporate' => 'Corporate Event',
            'graduation' => 'Graduation Ceremony',
            'anniversary' => 'Anniversary',
            'baby_shower' => 'Baby Shower'
        ];
        
        $templates = $this->getDummyTemplates();
        
        return view('admin.invitations.edit', compact('invitation', 'eventTypes', 'templates'));
    }
    
    /**
     * Update the specified invitation in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate request
        $validated = $request->validate([
            'event_name' => 'required|string|max:255',
            'event_type' => 'required|string',
            'event_date' => 'required|date',
            'event_location' => 'required|string|max:500',
            'description' => 'nullable|string',
            'message' => 'nullable|string',
            'rsvp_instructions' => 'nullable|string',
            'status' => 'required|in:draft,sent,pending,cancelled'
        ]);
        
        // Update database (dummy response)
        return response()->json([
            'success' => true,
            'message' => 'Invitation updated successfully',
            'redirect_url' => route('invitations.index')
        ]);
    }
    
    /**
     * Remove the specified invitation from storage.
     */
    public function destroy($id)
    {
        // Delete from database (dummy response)
        return response()->json([
            'success' => true,
            'message' => 'Invitation deleted successfully'
        ]);
    }
    
    /**
     * Display invitation templates.
     */
    public function templates()
    {
        $templates = $this->getDummyTemplates();
        
        // Group templates by category
        $groupedTemplates = [];
        foreach ($templates as $template) {
            $groupedTemplates[$template['category']][] = $template;
        }
        
        return view('admin.invitations.templates', compact('templates', 'groupedTemplates'));
    }
    
    /**
     * Send invitation to guests.
     */
    public function send(Request $request, $id)
    {
        $request->validate([
            'guests' => 'required|array',
            'guests.*' => 'email'
        ]);
        
        // Kirim invitation (dummy)
        $sentCount = count($request->guests);
        
        return response()->json([
            'success' => true,
            'message' => "Invitation sent to {$sentCount} guest(s) successfully"
        ]);
    }
    
    /**
     * Duplicate invitation.
     */
    public function duplicate($id)
    {
        // Duplicate invitation (dummy)
        $newId = rand(1000, 9999);
        
        return response()->json([
            'success' => true,
            'message' => 'Invitation duplicated successfully',
            'new_invitation_id' => $newId,
            'redirect_url' => route('invitations.edit', $newId)
        ]);
    }
    
    /**
     * Export invitations.
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        $invitations = $this->getDummyInvitations();
        
        if ($format === 'csv') {
            $filename = 'invitations_' . date('Y-m-d') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\""
            ];
            
            $callback = function() use ($invitations) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['ID', 'Event Name', 'Event Type', 'Event Date', 'Status', 'Guests Count']);
                
                foreach ($invitations as $invitation) {
                    fputcsv($file, [
                        $invitation['id'],
                        $invitation['event_name'],
                        $invitation['event_type'],
                        $invitation['event_date'],
                        $invitation['status'],
                        $invitation['guests_count']
                    ]);
                }
                
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
        }
        
        return response()->json([
            'success' => true,
            'data' => $invitations
        ]);
    }
    
    /**
     * Get statistics for invitations.
     */
    public function statistics()
    {
        $invitations = $this->getDummyInvitations();
        
        $stats = [
            'total' => count($invitations),
            'draft' => count(array_filter($invitations, fn($i) => $i['status'] === 'draft')),
            'sent' => count(array_filter($invitations, fn($i) => $i['status'] === 'sent')),
            'pending' => count(array_filter($invitations, fn($i) => $i['status'] === 'pending')),
            'cancelled' => count(array_filter($invitations, fn($i) => $i['status'] === 'cancelled')),
            'total_guests' => array_sum(array_column($invitations, 'guests_count')),
            'confirmed_guests' => array_sum(array_column($invitations, 'confirmed_guests'))
        ];
        
        $stats['attendance_rate'] = $stats['total_guests'] > 0 
            ? round(($stats['confirmed_guests'] / $stats['total_guests']) * 100, 2) 
            : 0;
        
        // Monthly data untuk chart
        $monthlyData = [];
        foreach (range(1, 12) as $month) {
            $monthlyData[date('M', mktime(0, 0, 0, $month, 1))] = rand(5, 50);
        }
        
        return view('admin.invitations.statistics', compact('stats', 'monthlyData'));
    }
    
    /**
     * Get dummy invitations data
     */
    private function getDummyInvitations()
    {
        $eventTypes = ['Wedding', 'Birthday', 'Corporate', 'Graduation', 'Anniversary'];
        $statuses = ['draft', 'sent', 'pending', 'cancelled'];
        $invitations = [];
        
        for ($i = 1; $i <= 50; $i++) {
            $eventType = $eventTypes[array_rand($eventTypes)];
            $status = $statuses[array_rand($statuses)];
            $eventDate = now()->addDays(rand(5, 90))->format('Y-m-d H:i:s');
            $createdAt = now()->subDays(rand(1, 60))->format('Y-m-d H:i:s');
            $guestsCount = rand(10, 300);
            $confirmedGuests = rand(0, $guestsCount);
            
            $invitations[] = [
                'id' => $i,
                'invitation_code' => 'INV-' . date('Y') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'event_name' => $eventType . ' Celebration #' . $i,
                'event_type' => $eventType,
                'event_date' => $eventDate,
                'event_location' => ['Grand Ballroom', 'Garden Villa', 'Convention Center', 'Beach Resort'][array_rand(['Grand Ballroom', 'Garden Villa', 'Convention Center', 'Beach Resort'])],
                'description' => 'Join us for a wonderful celebration filled with joy and happiness.',
                'message' => 'Dear friends and family, we are excited to have you celebrate with us!',
                'status' => $status,
                'guests_count' => $guestsCount,
                'confirmed_guests' => $confirmedGuests,
                'created_at' => $createdAt,
                'template_name' => ['Elegant Classic', 'Modern Minimalist', 'Floral Romance', 'Premium Gold'][array_rand(['Elegant Classic', 'Modern Minimalist', 'Floral Romance', 'Premium Gold'])]
            ];
        }
        
        // Sort by created_at descending
        usort($invitations, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        
        return $invitations;
    }
    
    /**
     * Get dummy invitation detail
     */
    private function getInvitationDetail($id)
    {
        $invitations = $this->getDummyInvitations();
        
        foreach ($invitations as $invitation) {
            if ($invitation['id'] == $id) {
                // Add dummy guests data
                $invitation['guests'] = $this->getDummyGuests($id);
                $invitation['rsvp_stats'] = $this->getDummyRSVPStats($invitation['guests_count']);
                return $invitation;
            }
        }
        
        return null;
    }
    
    /**
     * Get dummy guests for invitation
     */
    private function getDummyGuests($invitationId)
    {
        $guests = [];
        $statuses = ['confirmed', 'pending', 'declined', 'no_response'];
        
        for ($i = 1; $i <= rand(10, 50); $i++) {
            $status = $statuses[array_rand($statuses)];
            $guests[] = [
                'id' => $i,
                'name' => 'Guest ' . $i,
                'email' => 'guest' . $i . '@example.com',
                'phone' => '+62 812 3456 78' . $i,
                'rsvp_status' => $status,
                'responded_at' => $status !== 'no_response' ? now()->subDays(rand(1, 30))->format('Y-m-d H:i:s') : null,
                'plus_one' => rand(0, 1),
                'dietary_requirements' => rand(0, 1) ? 'Vegetarian' : null
            ];
        }
        
        return $guests;
    }
    
    /**
     * Get dummy RSVP statistics
     */
    private function getDummyRSVPStats($totalGuests)
    {
        $confirmed = rand(0, $totalGuests);
        $declined = rand(0, $totalGuests - $confirmed);
        $pending = $totalGuests - $confirmed - $declined;
        
        return [
            'total' => $totalGuests,
            'confirmed' => $confirmed,
            'declined' => $declined,
            'pending' => $pending,
            'no_response' => $pending,
            'confirmation_rate' => $totalGuests > 0 ? round(($confirmed / $totalGuests) * 100, 2) : 0
        ];
    }
    
    /**
     * Get dummy templates data
     */
    private function getDummyTemplates()
    {
        return [
            [
                'id' => 1,
                'name' => 'Elegant Classic',
                'category' => 'wedding',
                'color' => 'primary',
                'icon' => 'heart',
                'preview' => 'classic',
                'description' => 'Traditional and formal design perfect for weddings and formal events.',
                'is_premium' => false,
                'usage_count' => 1245
            ],
            [
                'id' => 2,
                'name' => 'Modern Minimalist',
                'category' => 'wedding',
                'color' => 'info',
                'icon' => 'abstract',
                'preview' => 'modern',
                'description' => 'Clean and contemporary style with simple elegance.',
                'is_premium' => false,
                'usage_count' => 892
            ],
            [
                'id' => 3,
                'name' => 'Floral Romance',
                'category' => 'wedding',
                'color' => 'danger',
                'icon' => 'flower',
                'preview' => 'floral',
                'description' => 'Beautiful floral patterns with romantic touches.',
                'is_premium' => true,
                'usage_count' => 567
            ],
            [
                'id' => 4,
                'name' => 'Premium Gold',
                'category' => 'wedding',
                'color' => 'warning',
                'icon' => 'crown',
                'preview' => 'gold',
                'description' => 'Luxury golden accents for exclusive events.',
                'is_premium' => true,
                'usage_count' => 321
            ],
            [
                'id' => 5,
                'name' => 'Birthday Bash',
                'category' => 'birthday',
                'color' => 'success',
                'icon' => 'cake',
                'preview' => 'birthday1',
                'description' => 'Fun and colorful design perfect for birthday celebrations.',
                'is_premium' => false,
                'usage_count' => 2103
            ],
            [
                'id' => 6,
                'name' => 'Party Time',
                'category' => 'birthday',
                'color' => 'primary',
                'icon' => 'balloon',
                'preview' => 'birthday2',
                'description' => 'Energetic design with balloons and confetti.',
                'is_premium' => false,
                'usage_count' => 1567
            ],
            [
                'id' => 7,
                'name' => 'Corporate Event',
                'category' => 'corporate',
                'color' => 'info',
                'icon' => 'building',
                'preview' => 'corporate1',
                'description' => 'Professional design for business events and conferences.',
                'is_premium' => false,
                'usage_count' => 876
            ],
            [
                'id' => 8,
                'name' => 'Business Seminar',
                'category' => 'corporate',
                'color' => 'dark',
                'icon' => 'presentation',
                'preview' => 'corporate2',
                'description' => 'Sleek design for seminars and workshops.',
                'is_premium' => true,
                'usage_count' => 432
            ],
            [
                'id' => 9,
                'name' => 'Baby Shower',
                'category' => 'baby_shower',
                'color' => 'info',
                'icon' => 'baby',
                'preview' => 'baby',
                'description' => 'Cute and adorable design for baby shower celebrations.',
                'is_premium' => false,
                'usage_count' => 654
            ],
            [
                'id' => 10,
                'name' => 'Anniversary Celebration',
                'category' => 'anniversary',
                'color' => 'danger',
                'icon' => 'heart',
                'preview' => 'anniversary',
                'description' => 'Romantic design for celebrating love and commitment.',
                'is_premium' => false,
                'usage_count' => 543
            ]
        ];
    }
    
    /**
     * Get bulk invitation statistics
     */
    public function bulkStatistics()
    {
        $invitations = $this->getDummyInvitations();
        
        $stats = [
            'total_invitations' => count($invitations),
            'total_guests' => array_sum(array_column($invitations, 'guests_count')),
            'total_confirmed' => array_sum(array_column($invitations, 'confirmed_guests')),
            'average_guests_per_invitation' => count($invitations) > 0 
                ? round(array_sum(array_column($invitations, 'guests_count')) / count($invitations), 1) 
                : 0,
            'by_status' => [
                'draft' => count(array_filter($invitations, fn($i) => $i['status'] === 'draft')),
                'sent' => count(array_filter($invitations, fn($i) => $i['status'] === 'sent')),
                'pending' => count(array_filter($invitations, fn($i) => $i['status'] === 'pending')),
                'cancelled' => count(array_filter($invitations, fn($i) => $i['status'] === 'cancelled'))
            ],
            'by_event_type' => [
                'Wedding' => count(array_filter($invitations, fn($i) => $i['event_type'] === 'Wedding')),
                'Birthday' => count(array_filter($invitations, fn($i) => $i['event_type'] === 'Birthday')),
                'Corporate' => count(array_filter($invitations, fn($i) => $i['event_type'] === 'Corporate')),
                'Graduation' => count(array_filter($invitations, fn($i) => $i['event_type'] === 'Graduation')),
                'Anniversary' => count(array_filter($invitations, fn($i) => $i['event_type'] === 'Anniversary'))
            ]
        ];
        
        return response()->json($stats);
    }
}