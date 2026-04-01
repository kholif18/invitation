<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmailCampaignController extends Controller
{
    /**
     * Display list of email campaigns
     */
    public function index()
    {
        return view('admin.communications.email.index');
    }
    
    /**
     * Get email campaigns data (AJAX)
     */
    public function getData(Request $request)
    {
        $campaigns = $this->getDummyCampaigns();
        
        // Apply filters
        if ($request->has('status') && $request->status) {
            $campaigns = array_filter($campaigns, function($campaign) use ($request) {
                return $campaign['status'] === $request->status;
            });
        }
        
        if ($request->has('search') && $request->search) {
            $search = strtolower($request->search);
            $campaigns = array_filter($campaigns, function($campaign) use ($search) {
                return strpos(strtolower($campaign['name']), $search) !== false ||
                       strpos(strtolower($campaign['subject']), $search) !== false;
            });
        }
        
        return response()->json([
            'success' => true,
            'data' => array_values($campaigns)
        ]);
    }
    
    /**
     * Show create campaign form
     */
    public function create()
    {
        return view('admin.communications.email.create');
    }
    
    /**
     * Store new campaign
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'recipients' => 'required|string',
            'schedule_date' => 'nullable|date'
        ]);
        
        // Dummy store logic
        return response()->json([
            'success' => true,
            'message' => 'Campaign created successfully',
            'campaign_id' => rand(100, 999)
        ]);
    }
    
    /**
     * Show campaign details
     */
    public function show($id)
    {
        $campaigns = $this->getDummyCampaigns();
        $campaign = collect($campaigns)->firstWhere('id', (int)$id);
        
        if (!$campaign) {
            abort(404);
        }
        
        return view('admin.communications.email.show', compact('campaign'));
    }
    
    /**
     * Edit campaign
     */
    public function edit($id)
    {
        $campaigns = $this->getDummyCampaigns();
        $campaign = collect($campaigns)->firstWhere('id', (int)$id);
        
        return view('admin.communications.email.edit', compact('campaign'));
    }
    
    /**
     * Update campaign
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string'
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Campaign updated successfully'
        ]);
    }
    
    /**
     * Delete campaign
     */
    public function destroy($id)
    {
        return response()->json([
            'success' => true,
            'message' => 'Campaign deleted successfully'
        ]);
    }
    
    /**
     * Send campaign immediately
     */
    public function send($id)
    {
        return response()->json([
            'success' => true,
            'message' => 'Campaign sent successfully',
            'recipients' => rand(50, 500)
        ]);
    }
    
    /**
     * Schedule campaign
     */
    public function schedule(Request $request, $id)
    {
        $request->validate([
            'schedule_date' => 'required|date|after:now'
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Campaign scheduled successfully',
            'schedule_date' => $request->schedule_date
        ]);
    }
    
    /**
     * Duplicate campaign
     */
    public function duplicate($id)
    {
        return response()->json([
            'success' => true,
            'message' => 'Campaign duplicated successfully',
            'new_campaign_id' => rand(100, 999)
        ]);
    }
    
    /**
     * Cancel scheduled campaign
     */
    public function cancel($id)
    {
        return response()->json([
            'success' => true,
            'message' => 'Campaign cancelled successfully'
        ]);
    }
    
    /**
     * Get campaign statistics
     */
    public function statistics($id)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'sent' => rand(100, 500),
                'delivered' => rand(90, 98) . '%',
                'opened' => rand(45, 85) . '%',
                'clicked' => rand(20, 60) . '%',
                'bounced' => rand(1, 5) . '%'
            ]
        ]);
    }
    
    /**
     * Get campaign analytics
     */
    public function analytics($id)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'opens_by_date' => [
                    'Day 1' => rand(50, 200),
                    'Day 2' => rand(30, 150),
                    'Day 3' => rand(20, 100),
                    'Day 4' => rand(10, 80),
                    'Day 5' => rand(5, 50),
                    'Day 6' => rand(2, 30),
                    'Day 7' => rand(1, 20)
                ],
                'device_stats' => [
                    'mobile' => 65,
                    'desktop' => 30,
                    'tablet' => 5
                ]
            ]
        ]);
    }
    
    /**
     * Bulk send campaigns
     */
    public function bulkSend(Request $request)
    {
        $request->validate([
            'campaign_ids' => 'required|array'
        ]);
        
        return response()->json([
            'success' => true,
            'message' => count($request->campaign_ids) . ' campaigns sent successfully'
        ]);
    }
    
    /**
     * Bulk delete campaigns
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'campaign_ids' => 'required|array'
        ]);
        
        return response()->json([
            'success' => true,
            'message' => count($request->campaign_ids) . ' campaigns deleted successfully'
        ]);
    }
    
    /**
     * Export campaigns to CSV
     */
    public function exportCSV()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="email_campaigns_' . date('Y-m-d') . '.csv"',
        ];
        
        $callback = function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Campaign Name', 'Subject', 'Status', 'Recipients', 'Sent Date', 'Open Rate']);
            
            $campaigns = $this->getDummyCampaigns();
            foreach ($campaigns as $campaign) {
                fputcsv($file, [
                    $campaign['id'],
                    $campaign['name'],
                    $campaign['subject'],
                    $campaign['status'],
                    $campaign['recipients'],
                    $campaign['sent_date'],
                    $campaign['open_rate'] . '%'
                ]);
            }
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Export campaign report
     */
    public function exportReport($id)
    {
        return response()->json([
            'success' => true,
            'message' => 'Report generated successfully',
            'download_url' => '/reports/campaign_' . $id . '_' . date('Y-m-d') . '.pdf'
        ]);
    }
    
    /**
     * Get dummy campaign data
     */
    private function getDummyCampaigns()
    {
        return [
            [
                'id' => 1,
                'name' => 'Wedding Invitation',
                'subject' => 'You\'re Invited to John & Sarah\'s Wedding',
                'status' => 'Sent',
                'recipients' => 245,
                'sent_date' => '2024-12-20',
                'open_rate' => 78
            ],
            [
                'id' => 2,
                'name' => 'RSVP Reminder',
                'subject' => 'Don\'t Forget to RSVP!',
                'status' => 'Sent',
                'recipients' => 120,
                'sent_date' => '2024-12-18',
                'open_rate' => 65
            ],
            [
                'id' => 3,
                'name' => 'Wedding Update',
                'subject' => 'Important Update About the Wedding',
                'status' => 'Scheduled',
                'recipients' => 245,
                'sent_date' => '2024-12-25',
                'open_rate' => 0
            ],
            [
                'id' => 4,
                'name' => 'Thank You Message',
                'subject' => 'Thank You for Celebrating With Us!',
                'status' => 'Draft',
                'recipients' => 0,
                'sent_date' => '-',
                'open_rate' => 0
            ]
        ];
    }
}
