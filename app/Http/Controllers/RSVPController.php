<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class RSVPController extends Controller
{
    /**
     * Display RSVP management page
     */
    public function index()
    {
        return view('admin.invitations.rsvp');
    }

    /**
     * Get all RSVP data (for AJAX)
     */
    public function getData(Request $request)
    {
        // Dummy data for demonstration
        $rsvpData = $this->getDummyRSVPData();
        
        // Apply filters
        if ($request->has('search') && $request->search) {
            $search = strtolower($request->search);
            $rsvpData = array_filter($rsvpData, function($item) use ($search) {
                return strpos(strtolower($item['guest_name']), $search) !== false ||
                       strpos(strtolower($item['email']), $search) !== false;
            });
        }
        
        if ($request->has('status') && $request->status) {
            $rsvpData = array_filter($rsvpData, function($item) use ($request) {
                return $item['status'] === $request->status;
            });
        }
        
        if ($request->has('invitation_id') && $request->invitation_id !== 'all') {
            $rsvpData = array_filter($rsvpData, function($item) use ($request) {
                return $item['invitation_id'] == $request->invitation_id;
            });
        }
        
        // Pagination
        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);
        $total = count($rsvpData);
        $data = array_slice(array_values($rsvpData), ($page - 1) * $perPage, $perPage);
        
        return response()->json([
            'success' => true,
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'last_page' => ceil($total / $perPage)
        ]);
    }

    /**
     * Get RSVP statistics
     */
    public function getStatistics(Request $request)
    {
        $rsvpData = $this->getDummyRSVPData();
        
        $totalSent = count($rsvpData);
        $confirmed = count(array_filter($rsvpData, fn($r) => $r['status'] === 'confirmed'));
        $pending = count(array_filter($rsvpData, fn($r) => $r['status'] === 'pending'));
        $declined = count(array_filter($rsvpData, fn($r) => $r['status'] === 'declined'));
        $confirmedAttendees = array_sum(array_column(array_filter($rsvpData, fn($r) => $r['status'] === 'confirmed'), 'attendees'));
        $plusOneRequests = count(array_filter($rsvpData, fn($r) => $r['plus_one'] === true));
        $parkingNeeded = count(array_filter($rsvpData, fn($r) => $r['parking'] === true));
        $dietaryRestrictions = count(array_filter($rsvpData, fn($r) => $r['dietary'] && $r['dietary'] !== 'None' && $r['dietary'] !== ''));
        
        return response()->json([
            'success' => true,
            'data' => [
                'total_sent' => $totalSent,
                'confirmed' => $confirmed,
                'pending' => $pending,
                'declined' => $declined,
                'confirmed_attendees' => $confirmedAttendees,
                'plus_one_requests' => $plusOneRequests,
                'parking_needed' => $parkingNeeded,
                'dietary_restrictions' => $dietaryRestrictions,
                'attendance_rate' => $totalSent > 0 ? round(($confirmed / $totalSent) * 100) : 0,
                'confirmed_percent' => $totalSent > 0 ? round(($confirmed / $totalSent) * 100) : 0,
                'pending_percent' => $totalSent > 0 ? round(($pending / $totalSent) * 100) : 0,
                'declined_percent' => $totalSent > 0 ? round(($declined / $totalSent) * 100) : 0,
            ]
        ]);
    }

    /**
     * Get single RSVP detail
     */
    public function show($id)
    {
        $rsvpData = $this->getDummyRSVPData();
        $rsvp = collect($rsvpData)->firstWhere('id', (int)$id);
        
        if (!$rsvp) {
            return response()->json([
                'success' => false,
                'message' => 'RSVP not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $rsvp
        ]);
    }

    /**
     * Update RSVP response
     */
    public function update(Request $request, $id)
    {
        // Dummy update logic
        return response()->json([
            'success' => true,
            'message' => 'RSVP updated successfully',
            'data' => $request->all()
        ]);
    }

    /**
     * Delete RSVP response
     */
    public function destroy($id)
    {
        return response()->json([
            'success' => true,
            'message' => 'RSVP deleted successfully'
        ]);
    }

    /**
     * Bulk update RSVP responses
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'status' => 'required|in:confirmed,pending,declined'
        ]);
        
        return response()->json([
            'success' => true,
            'message' => count($request->ids) . ' RSVP responses updated successfully'
        ]);
    }

    /**
     * Bulk delete RSVP responses
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array'
        ]);
        
        return response()->json([
            'success' => true,
            'message' => count($request->ids) . ' RSVP responses deleted successfully'
        ]);
    }

    /**
     * Send reminders to pending guests
     */
    public function sendReminders(Request $request)
    {
        $request->validate([
            'target' => 'required|in:pending,all,custom',
            'message' => 'required|string',
            'send_via_email' => 'boolean',
            'send_via_whatsapp' => 'boolean'
        ]);
        
        $targetCount = $request->target === 'pending' ? 3 : 8; // Dummy count
        
        return response()->json([
            'success' => true,
            'message' => "Reminders sent to {$targetCount} guests successfully"
        ]);
    }

    /**
     * Send reminder to single guest
     */
    public function sendReminder($id)
    {
        return response()->json([
            'success' => true,
            'message' => 'Reminder sent successfully'
        ]);
    }

    /**
     * Export RSVP data to CSV
     */
    public function exportCSV(Request $request)
    {
        $rsvpData = $this->getDummyRSVPData();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="rsvp_data_' . date('Y-m-d') . '.csv"',
        ];
        
        $callback = function() use ($rsvpData) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, ['Guest Name', 'Email', 'Phone', 'Invitation', 'Status', 'Attendees', 'Plus One', 'Parking', 'Dietary', 'Response Date', 'Notes']);
            
            // Add data
            foreach ($rsvpData as $row) {
                fputcsv($file, [
                    $row['guest_name'],
                    $row['email'],
                    $row['phone'],
                    $row['invitation'],
                    $row['status'],
                    $row['attendees'],
                    $row['plus_one'] ? 'Yes' : 'No',
                    $row['parking'] ? 'Yes' : 'No',
                    $row['dietary'],
                    $row['response_date'],
                    $row['notes']
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export RSVP data to Excel (dummy)
     */
    public function exportExcel(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Excel export will be available soon'
        ]);
    }

    /**
     * Export RSVP data to PDF (dummy)
     */
    public function exportPDF(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'PDF export will be available soon'
        ]);
    }

    /**
     * Generate RSVP report
     */
    public function generateReport(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Report generated successfully',
            'report_url' => '/reports/rsvp_' . date('Y-m-d') . '.pdf'
        ]);
    }

    /**
     * Get RSVP by invitation ID
     */
    public function getByInvitation($invitationId)
    {
        $rsvpData = $this->getDummyRSVPData();
        $filtered = array_filter($rsvpData, fn($r) => $r['invitation_id'] == $invitationId);
        
        return response()->json([
            'success' => true,
            'data' => array_values($filtered),
            'count' => count($filtered)
        ]);
    }

    /**
     * Seating arrangement page
     */
    public function seating()
    {
        return view('admin.invitations.seating');
    }

    /**
     * Assign seating for guests
     */
    public function assignSeating(Request $request)
    {
        $request->validate([
            'guest_id' => 'required|integer',
            'table_number' => 'required|string'
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Seating assigned successfully'
        ]);
    }

    /**
     * Get dummy RSVP data for testing
     */
    private function getDummyRSVPData()
    {
        return [
            [
                'id' => 1,
                'guest_name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '+628123456789',
                'invitation_id' => 1,
                'invitation' => 'John & Sarah\'s Wedding',
                'status' => 'confirmed',
                'attendees' => 2,
                'plus_one' => true,
                'parking' => true,
                'dietary' => 'Vegetarian',
                'response_date' => '2024-12-20',
                'notes' => 'Looking forward to it!',
                'created_at' => '2024-12-15 10:00:00',
                'updated_at' => '2024-12-20 15:30:00'
            ],
            [
                'id' => 2,
                'guest_name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'phone' => '+628987654321',
                'invitation_id' => 1,
                'invitation' => 'John & Sarah\'s Wedding',
                'status' => 'confirmed',
                'attendees' => 1,
                'plus_one' => false,
                'parking' => false,
                'dietary' => 'None',
                'response_date' => '2024-12-19',
                'notes' => '',
                'created_at' => '2024-12-14 14:20:00',
                'updated_at' => '2024-12-19 09:15:00'
            ],
            [
                'id' => 3,
                'guest_name' => 'Robert Johnson',
                'email' => 'robert@example.com',
                'phone' => '+628555555555',
                'invitation_id' => 1,
                'invitation' => 'John & Sarah\'s Wedding',
                'status' => 'pending',
                'attendees' => 0,
                'plus_one' => false,
                'parking' => false,
                'dietary' => '',
                'response_date' => '',
                'notes' => '',
                'created_at' => '2024-12-10 11:00:00',
                'updated_at' => '2024-12-10 11:00:00'
            ],
            [
                'id' => 4,
                'guest_name' => 'Maria Garcia',
                'email' => 'maria@example.com',
                'phone' => '+628777777777',
                'invitation_id' => 1,
                'invitation' => 'John & Sarah\'s Wedding',
                'status' => 'declined',
                'attendees' => 0,
                'plus_one' => false,
                'parking' => false,
                'dietary' => '',
                'response_date' => '2024-12-18',
                'notes' => 'Sorry, cannot attend',
                'created_at' => '2024-12-12 16:45:00',
                'updated_at' => '2024-12-18 08:30:00'
            ],
            [
                'id' => 5,
                'guest_name' => 'David Chen',
                'email' => 'david@example.com',
                'phone' => '+628999999999',
                'invitation_id' => 1,
                'invitation' => 'John & Sarah\'s Wedding',
                'status' => 'confirmed',
                'attendees' => 3,
                'plus_one' => true,
                'parking' => true,
                'dietary' => 'Halal',
                'response_date' => '2024-12-17',
                'notes' => 'Will bring the kids',
                'created_at' => '2024-12-13 13:20:00',
                'updated_at' => '2024-12-17 10:45:00'
            ],
            [
                'id' => 6,
                'guest_name' => 'Sarah Wilson',
                'email' => 'sarah@example.com',
                'phone' => '+628111111111',
                'invitation_id' => 2,
                'invitation' => 'Michael & Emma\'s Wedding',
                'status' => 'confirmed',
                'attendees' => 2,
                'plus_one' => true,
                'parking' => true,
                'dietary' => 'None',
                'response_date' => '2024-12-16',
                'notes' => '',
                'created_at' => '2024-12-11 09:30:00',
                'updated_at' => '2024-12-16 14:20:00'
            ],
            [
                'id' => 7,
                'guest_name' => 'James Brown',
                'email' => 'james@example.com',
                'phone' => '+628222222222',
                'invitation_id' => 2,
                'invitation' => 'Michael & Emma\'s Wedding',
                'status' => 'pending',
                'attendees' => 0,
                'plus_one' => false,
                'parking' => false,
                'dietary' => '',
                'response_date' => '',
                'notes' => '',
                'created_at' => '2024-12-09 15:15:00',
                'updated_at' => '2024-12-09 15:15:00'
            ],
            [
                'id' => 8,
                'guest_name' => 'Lisa Anderson',
                'email' => 'lisa@example.com',
                'phone' => '+628333333333',
                'invitation_id' => 3,
                'invitation' => 'David & Lisa\'s Wedding',
                'status' => 'confirmed',
                'attendees' => 2,
                'plus_one' => false,
                'parking' => true,
                'dietary' => 'Gluten Free',
                'response_date' => '2024-12-15',
                'notes' => 'Excited for the wedding!',
                'created_at' => '2024-12-08 12:00:00',
                'updated_at' => '2024-12-15 11:30:00'
            ]
        ];
    }
}
